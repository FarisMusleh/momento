from flask import Flask, request, jsonify, render_template
from sentence_transformers import SentenceTransformer
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from PIL import Image
import torch
from transformers import AutoModelForImageClassification, ViTImageProcessor, CLIPProcessor, CLIPModel
from categories import categories  # Ensure categories is defined in a separate file
from flask_cors import CORS
import re

# Initialize Flask app
app = Flask(__name__)
CORS(app)

# Load Sentence-BERT model for NLP-based similarity
nlp_model = SentenceTransformer('all-MiniLM-L6-v2')

# Precompute category embeddings using Sentence-BERT
category_embeddings = nlp_model.encode(categories, convert_to_tensor=True)

# Load NSFW classification models
nsfw_model = AutoModelForImageClassification.from_pretrained("Falconsai/nsfw_image_detection")
nsfw_processor = ViTImageProcessor.from_pretrained("Falconsai/nsfw_image_detection")

# Load CLIP model and processor
clip_model_name = "openai/clip-vit-base-patch32"
clip_model = CLIPModel.from_pretrained(clip_model_name)
clip_processor = CLIPProcessor.from_pretrained(clip_model_name)

# Helper function for NSFW classification
def classify_nsfw_image(image):
    with torch.no_grad():
        inputs = nsfw_processor(images=image, return_tensors="pt")
        outputs = nsfw_model(**inputs)
        logits = outputs.logits

    predicted_label = logits.argmax(-1).item()
    label = nsfw_model.config.id2label[predicted_label]
    return label

# Helper function for CLIP-based classification
def classify_clip_image(image):
    inputs = clip_processor(text=categories, images=image, return_tensors="pt", padding=True)
    outputs = clip_model(**inputs)

    # Get probabilities
    probs = outputs.logits_per_image.softmax(dim=1)[0]

    # Get top 5 matches
    top_n = 5
    threshold = 0.05
    sorted_indices = torch.argsort(probs, descending=True)[:top_n]

    # Filter results based on threshold and return only labels
    top_labels = [categories[i] for i in sorted_indices if probs[i].item() > threshold]
    return top_labels

# Helper function for NLP-based similarity search
def search_similar_category(query):
    query = query.strip()  # Remove leading and trailing spaces
    query = re.sub(r'\s+', ' ', query)  # Replace multiple spaces with a single space
    query = re.sub(r'[^a-zA-Z0-9\s]', '', query)  # Remove special characters (except spaces)
    query = query.lower()
    """Find similar categories using NLP-based similarity search."""
    # Encode the search query to vector
    query_embedding = nlp_model.encode([query], convert_to_tensor=True)

    # Calculate cosine similarity between the query and category embeddings
    similarities = cosine_similarity(query_embedding.cpu().numpy(), category_embeddings.cpu().numpy())
    
    # Get the top 5 most similar categories
    top_indices = np.argsort(similarities[0])[::-1][:5]
    similar_categories = [categories[i] for i in top_indices]

    return similar_categories


@app.route('/search', methods=['GET'])
def search():
    query = request.args.get('query')

    if not query:
        return jsonify({"error": "Query parameter is required"}), 400

    # Get similar categories for the search query
    similar_categories = search_similar_category(query)
    
    return jsonify({"similar_categories": similar_categories})

@app.route('/classify/nsfw', methods=['POST'])
def classify_nsfw():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'}), 400

    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400

    try:
        img = Image.open(file.stream)

        # Classify using NSFW model
        label = classify_nsfw_image(img)
        return jsonify({'label': label})  # Return NSFW classification result

    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/classify/clip', methods=['POST'])
def classify_clip():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'}), 400

    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400

    try:
        img = Image.open(file.stream)

        # Get top matching labels using CLIP model
        labels = classify_clip_image(img)

        if labels:
            return jsonify({"labels": labels})
        else:
            return jsonify({"error": "No relevant category found"}), 404

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
