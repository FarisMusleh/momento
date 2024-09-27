<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Viewer</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #e9ecef;
        }
        h1 {
            margin-bottom: 20px;
            color: #343a40;
        }
        #imageGallery {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1000px;
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
        }
        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .back-link {
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Saved Images</h1>
    <div id="imageGallery"></div>
    <a href="index.html" class="back-link">Back to Gallery</a>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.forEach((value, key) => {
                const img = document.createElement('img');
                console.log(value);
				img.src = value;
                const container = document.createElement('div');
                container.classList.add('image-container');
                container.appendChild(img);
                imageGallery.appendChild(container);
        });
    </script>
</body>
</html>