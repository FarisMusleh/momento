 <!DOCTYPE html>
 <html lang="en">
 <head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <link href="Pacifico/Pacifico-Regular">
	<style>
  .login{
        
          background:rgb(31, 29, 29);
          border-radius: 20px;
          cursor: pointer; 
          margin-top:5px;
          transition: background 0.3s ease; 
          width:100px
        
      }
    .login:hover{
        background:rgb(80, 78, 78);
      }
/* Custom styles for the navbar underline */
        .navbar-nav .nav-item .nav-link {
            position: relative;
            padding-bottom: 5px; /* Space for the underline */
        }
        .navbar-nav .nav-item .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background-color:rgb(0, 0, 0); /* Color of the underline */
            transition: width 0.3s ease; /* Smooth transition for the underline */
        }
        .navbar-nav .nav-item .nav-link:hover::after {
            width: 100%; /* Full width on hover */
        }
        .navbar-nav .nav-item .nav-link.active::after {
            width: 100%; /* Full width for active link */
        }
	</style>
 </head>
 <body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand display-10" href="index.php" style=" font-family: Dancing Script, cursive;">Moomento</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-4">
                    <li class="nav-item">
                        <a class="nav-link" href="explore.php">Explore</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Hire a Designer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Find Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sign up</a>
                    </li>
                    <li class="login">
                        <a class="nav-link text-white text-center" href="#">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

 </body>
 </html>



			