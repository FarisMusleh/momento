from flask import Flask, request, jsonify
from flask_cors import CORS
from PIL import Image
import torch
import re
import spacy
from sentence_transformers import SentenceTransformer
from transformers import (
    AutoModelForImageClassification,
    ViTImageProcessor,
    CLIPProcessor,
    CLIPModel,
    BlipProcessor,
    BlipForConditionalGeneration
)
from categories import categories, category_alias_map
import faiss
from better_profanity import profanity

app = Flask(__name__)
CORS(app)

nlp_model = SentenceTransformer('all-MiniLM-L6-v2')
category_embeddings = nlp_model.encode(categories, convert_to_tensor=True)

category_matrix = category_embeddings.cpu().numpy().astype('float32')
faiss_index = faiss.IndexFlatL2(category_matrix.shape[1])
faiss_index.add(category_matrix)

nsfw_model = AutoModelForImageClassification.from_pretrained("Falconsai/nsfw_image_detection")
nsfw_processor = ViTImageProcessor.from_pretrained("Falconsai/nsfw_image_detection")

clip_model = CLIPModel.from_pretrained("openai/clip-vit-base-patch32")
clip_processor = CLIPProcessor.from_pretrained("openai/clip-vit-base-patch32")

blip_processor = BlipProcessor.from_pretrained("Salesforce/blip-image-captioning-base")
blip_model = BlipForConditionalGeneration.from_pretrained("Salesforce/blip-image-captioning-base")

nlp = spacy.load("en_core_web_sm")

profanity.load_censor_words()

RE_MULTISPACE = re.compile(r'\s+')
RE_SPECIAL = re.compile(r'[^a-zA-Z0-9\s]')
VIOLENT_KEYWORDS = {"blood", "bloody", "gore", "gory", "violent", "violence", "injury", "death", "explosion", "gun","war","dead"}

def clean_text(text):
    text = text.strip().lower()
    text = RE_MULTISPACE.sub(' ', text)
    text = RE_SPECIAL.sub('', text)
    return text

def classify_nsfw_image(image):
    with torch.no_grad():
        inputs = nsfw_processor(images=image, return_tensors="pt")
        outputs = nsfw_model(**inputs)
        label_id = outputs.logits.argmax(-1).item()
        return nsfw_model.config.id2label[label_id]

def extract_keywords_spacy(caption):
    doc = nlp(caption)
    keywords = set()
    keywords.update(ent.text for ent in doc.ents if ent.label_ in ["GPE", "LOC", "PERSON", "ORG"])
    keywords.update(token.text for token in doc if token.pos_ in ["NOUN", "PROPN"] and not token.is_stop)
    return list(keywords)

def classify_clip_image(image, threshold=0.2, top_n=5):
    clip_inputs = clip_processor(text=categories, images=image, return_tensors="pt", padding=True)
    with torch.no_grad():
        clip_outputs = clip_model(**clip_inputs)
        probs = clip_outputs.logits_per_image.softmax(dim=1)[0]

    sorted_indices = torch.argsort(probs, descending=True)
    top_labels = [categories[i] for i in sorted_indices[:top_n] if probs[i].item() > threshold]

    blip_inputs = blip_processor(image, return_tensors="pt")
    with torch.no_grad():
        blip_output = blip_model.generate(**blip_inputs)
    caption = blip_processor.decode(blip_output[0], skip_special_tokens=True)

    extracted_keywords = extract_keywords_spacy(caption)
    detected_keywords = set(kw.lower() for kw in extracted_keywords)
    is_gory = bool(VIOLENT_KEYWORDS.intersection(set(detected_keywords) | set(top_labels)))
    return {
        'labels': extracted_keywords,
        'caption': caption,
        'is_gory': is_gory,
        'clip_labels': top_labels 
    }


def search_similar_category(query, top_k=3):
    query = clean_text(query)
    query_embedding = nlp_model.encode([query], convert_to_tensor=True).cpu().numpy().astype('float32')
    distances, indices = faiss_index.search(query_embedding, top_k)
    expanded_categories = []
    for i, idx in enumerate(indices[0]):
        category = categories[idx]
        if i == 0 and category in category_alias_map:
            expanded_categories.extend(category_alias_map[category])
        else:
            expanded_categories.append(category)   
    return list(dict.fromkeys(expanded_categories)) 


@app.route('/search', methods=['GET'])
def search():
    query = request.args.get('query')
    if not query:
        return jsonify({"error": "Query parameter is required"}), 400
    similar_categories = search_similar_category(query)
    return jsonify({"similar_categories": similar_categories})

@app.route('/classify/nsfw', methods=['POST'])
def classify_nsfw():
    file = request.files.get('file')
    if not file or file.filename == '':
        return jsonify({'error': 'No file uploaded'}), 400
    try:
        img = Image.open(file.stream).convert("RGB")
        label = classify_nsfw_image(img)
        return jsonify({'label': label})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/classify/clip', methods=['POST'])
def classify_clip():
    file = request.files.get('file')
    if not file or file.filename == '':
        return jsonify({'error': 'No file uploaded'}), 400
    try:
        img = Image.open(file.stream).convert("RGB")
        result = classify_clip_image(img)
        return jsonify(result)
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/check-text', methods=['POST'])
def check_text():
    data = request.get_json()
    text = data.get('text', '')

    is_profane = profanity.contains_profanity(text)
    censored = profanity.censor(text)

    return jsonify({'text': text, 'is_bad': is_profane, 'censored': censored})

if __name__ == '__main__':
    app.run(debug=True)
