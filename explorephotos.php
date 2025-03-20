<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
if(!isset($_SESSION['data'])){
	header('location:index.php');
	exit();
}
$data = $_SESSION['data'];
require('pdo.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <title>Photos</title>
<style>
.hero-section {
		padding: 100px 0;
		text-align: center;
		background-image: url('./img/test.jpg');
		background-size: cover;
		background-repeat: no-repeat;
	}
	.search-container {
		position: relative;
		max-width: 600px;
		margin: 0 auto;
		
	}
	.search-input {
		width: 100%;
		padding: 12px 20px;
		border: 2px solid #ddd;
		border-radius: 30px;
		font-size: 16px;
		transition: all 0.3s ease;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
	}
	.search-input:focus {
		border-color:rgb(64, 65, 66);
		box-shadow: 0 2px 10px rgba(0, 123, 255, 0.25);
		outline: none;
	}
	.search-button {
		position: absolute;
		right: 10px;
		top: 50%;
		transform: translateY(-50%);
		background:rgb(31, 29, 29);
		color: white;
		border: none;
		padding:   10px 15px ;
		border-radius: 100%;
		cursor: pointer;
		transition: background 0.3s ease;
	}
	.search-button:hover {
		background:rgb(49, 49, 49);
	}
	
	.photographer-card {
      max-width: 400px;
      margin: 0 auto;
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background: #fff;
    }
    .photographer-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    .photographer-card img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }
    .photographer-card .card-body {
      padding: 2rem;
      text-align: center;
    }
    .photographer-card .card-title {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      color: #333;
    }
    .photographer-card .card-text {
      font-size: 1rem;
      color: #666;
      margin-bottom: 1.5rem;
    }
    .photographer-card .btn {
      font-size: 0.9rem;
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      transition: all 0.3s ease;
    }
    .photographer-card .btn-primary {
      background-color:rgb(46, 45, 45);
      border: none;
    }
    .photographer-card .btn-primary:hover {
      background-color:rgb(62, 62, 63);
    }
    .photographer-card .btn-outline-primary {
      border-color: #007bff;
      color: #007bff;
    }
    .photographer-card .btn-outline-primary:hover {
      background-color: #007bff;
      color: #fff;
    }
/* ******* */
		 .search-dropdown {
            position: absolute;
			right:60px;
			top:3px;
			background-color: transparent;
			text-align:left;
        }

        .search-dropdown-btn {
            background: white;
            padding: 10px 18px;
            font-size: 16px;
            border: 0px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }
		
        .search-dropdown-btn:hover {
            background: rgb();
        }

        .arrow {
            width: 7px;
            height: 7px;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
            transform: rotate(-45deg);
            transition: transform 0.3s ease;
        }

        .search-dropdown.active .arrow {
            transform: rotate(135deg);
        }

        .search-dropdown-content {
            position: absolute;
            top: 110%;
            left: 0;
            background: white;
            min-width: 160px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            overflow: hidden;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        }

        .search-dropdown-content a {
            display: block;
            padding: 12px 16px;
            text-decoration: none;
            color: #333;
            transition: background 0.3s;
        }

        .search-dropdown-content a:hover {
            background: #f5f5f5;
        }

        .search-dropdown.active .search-dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

/* General Layout */
.container-fluid {
    padding: 0 2rem;
}

.image-gallery {
    column-count: 3; /* Ensures 3 columns */
    column-gap: 1rem; /* Space between columns */
}

/* Image Container */
.image-container {
    position: relative;
    display: inline-block;
    width: 100%;
    break-inside: avoid; /* Ensures images don't break between columns */
    margin-bottom: 1.5rem;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.4s ease-in-out, box-shadow 0.3s ease;
}

.image-container:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-container:hover .card-img-top {
    transform: scale(1.1);
}

/* Overlay with like button and views */
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.4);
    opacity: 0;
    transition: opacity 0.3s ease, background 0.3s ease;
    color: white;
    border-radius: 15px;
    text-align: center;
}

.image-container:hover .overlay {
    opacity: 1;
    background: rgba(0, 0, 0, 0.6);  /* Darker background for hover */
}

.like-button {
    background-color: #ff4757;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 12px 25px;
    font-size: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    margin-bottom: 15px;
}

.like-button:hover {
    background-color: #ff6b81;
    transform: scale(1.1);
}

.views {
    display: flex;
    align-items: center;
    font-size: 18px;
    font-weight: 500;
}

.views i {
    margin-right: 8px;
    font-size: 20px;
}

.view-count {
    font-weight: bold;
    font-size: 18px;
}

/* Profile Picture Styling */
.profile-pic {
    display: none;
    position: absolute;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
}

.profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-container:hover .profile-pic {
    display: block; /* Show profile picture on hover */
}

/* Card Hover Effect */
.image-container:hover .overlay .like-button {
    transform: scale(1.1);
}

	
</style>
</head>

<body>
	<?php require("header.php"); ?>  
<div class="hero-section">
      <div class="container">
          <h1 class="display-10 slide-in"style=" font-family: Dancing Script, cursive;">Discover the world's top Photos</h1>
              <div class="container mt-5">
              <div class="search-container">
					<form method = "GET">
					  <input class="search-input" name = "query" type="search" placeholder="What are you looking for?" aria-label="Search">
					</form>
				<div class="search-dropdown">
					<button class="search-dropdown-btn">
						Shots
						<span class="arrow"></span>
					</button>
					<div class="search-dropdown-content">
						<a href="#">Shots</a>
						<a href="#">Photographers</a>
					</div>
				</div>
                  <button class="search-button" type="submit">
					  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
					  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
					  </svg>
				  </button>
              </div>
              </div>
      </div>
  </div>

<!-- *************************** -->
    <div class="container-fluid p-5">
    <div class="image-gallery">
        <?php
		if (isset($_GET['query'])) {
			$search_query = $_GET['query'] ?? '';

			// Flask server URL (adjust based on where your Flask app is running)
			$flask_url = 'http://127.0.0.1:5000/search?query=' . urlencode($search_query);

			// Initialize cURL session
			$ch = curl_init();

			// Set cURL options
			curl_setopt($ch, CURLOPT_URL, $flask_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // To return the response as a string
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow any redirects if needed

			// Execute cURL request and store the response
			$response = curl_exec($ch);

			// Check for errors
			if($response === false) {
				echo "cURL Error: " . curl_error($ch);
				exit();
			}

			// Decode the JSON response
			$response_data = json_decode($response, true);

			// Close cURL session
			curl_close($ch);

			// Display results
			if (isset($response_data['similar_categories'])) {
				$sql = "SELECT url,views,likes,picture,(likes * 100.0 / NULLIF(views, 0)) AS like_percentage FROM 
				images,accounts WHERE label LIKE ? and images.user_id = accounts.id ORDER BY like_percentage DESC, views DESC;";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['%' .$response_data['similar_categories'][0]. '%']);
			 if ($stmt->rowCount() > 0) {

				// Loop through the results and display the image URLs
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					?>
					<div class="image-container">
						<img src="<?=$row['url']?>" class="card-img-top mb-3 rounded" alt="...">
						<div class="overlay">
							<button class="like-button"><i class="fas fa-heart"></i></button>
							<div class="views">
								<i class="fas fa-eye"></i> <span class="view-count"><?=$row['views']?></span>
							</div>
							<div class="profile-pic">
								<img src="<?=$row['picture']?>" alt="Profile" class="profile-img">
							</div>
						</div>
					</div>
					<?php
				}	
			} else {
				echo "Error: " . $response_data['error'];
			}
		}
		}
		?>
    </div>
</div>

<div class=" container-fluid text-center p-2 fw-bold">
<hr>
                <p>© 2025 Moomento. Terms | Privacy | Cookies</p>
</footer>


<script>
	document.addEventListener("DOMContentLoaded", function () {
		const dropdown = document.querySelector(".search-dropdown");
		const btn = document.querySelector(".search-dropdown-btn");

		btn.addEventListener("click", function (event) {
			event.stopPropagation();
			dropdown.classList.toggle("active");
		});

		document.addEventListener("click", function (event) {
			if (!dropdown.contains(event.target)) {
				dropdown.classList.remove("active");
			}
		});
	});

const likeButtons = document.querySelectorAll('.like-button');

likeButtons.forEach(button => {
    button.addEventListener('click', function() {
        this.classList.toggle('liked');
        if (this.classList.contains('liked')) {
            this.innerHTML = '<i class="fas fa-heart"></i> Liked';
        } else {
            this.innerHTML = '<i class="fas fa-heart"></i>';
        }
    });
});

</script>


</body>

</html>