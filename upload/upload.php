<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Image Gallery</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
            color: #343a40;
            font-size: 2.5em;
        }
        #fileInput {
            margin-bottom: 20px;
            padding: 10px;
            border: 2px solid #007BFF;
            border-radius: 5px;
            background-color: #ffffff;
            cursor: pointer;
            font-size: 1.1em;
            transition: border-color 0.3s;
        }
        #fileInput:hover {
            border-color: #0056b3;
        }
        #galleryWrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1000px;
            width: 100%;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        #imageGallery {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 20px;
            width: 100%;
        }
        .image-container {
            position: relative;
            flex: 1 1 calc(25% - 20px);
            max-width: calc(25% - 20px);
            height: 200px;
            overflow: hidden;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .image-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px 10px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        #saveButton {
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #saveButton:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Upload Your Images</h1>
    <input type="file" id="fileInput" accept="image/*" multiple>
    <div id="galleryWrapper">
        <div id="imageGallery"></div>
    </div>
    <button id="saveButton">Save</button>

    <script>
        const fileInput = document.getElementById('fileInput');
        const imageGallery = document.getElementById('imageGallery');
        const saveButton = document.getElementById('saveButton');
        const imageUrls = [];

        fileInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            files.forEach(file => {
                addImageToGallery(file);
            });
            fileInput.value = ''; // Clear input after selection
        });

        function addImageToGallery(file) {
            const img = document.createElement('img');
            const imageUrl = URL.createObjectURL(file);
			console.log(encodeURIComponent(imageUrl));
            img.src = imageUrl;
            imageUrls.push(imageUrl); // Store the URL for saving

            const container = document.createElement('div');
            container.classList.add('image-container');
            container.onclick = function() {
                window.location.href = "index.html?image=" + imageUrl;
            };

            const deleteBtn = document.createElement('button');
            deleteBtn.classList.add('delete-btn');
            deleteBtn.textContent = 'Delete';
            deleteBtn.onclick = function(event) {
                event.stopPropagation(); // Prevent click on container
                imageGallery.removeChild(container);
                // Remove the image URL from the array
                const index = imageUrls.indexOf(imageUrl);
                if (index > -1) {
                    imageUrls.splice(index, 1);
                }
            };

            container.appendChild(img);
            container.appendChild(deleteBtn);
            imageGallery.appendChild(container);
        }

        saveButton.addEventListener('click', function() {
            if (imageUrls.length > 0) {
				
                const queryParam = imageUrls.map(url => "image="+encodeURIComponent(url)).join('&');
                window.location.href = "show.php?" + queryParam;
            } else {
                alert("Please upload at least one image before saving.");
            }
        });
    </script>
</body>
</html>