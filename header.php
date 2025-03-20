 <?php
	require('pdo.php');
 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	
	<style>
	.navSticky{
		position: sticky;
		text-align: center;
		top:0;
		z-index:1020;
		padding-top:10px; 
		padding-bottom:10px;
	}
	.dropdown-button{
		position:relative;
	}
  .dropdown-content {
		border-radius: 5px;
		opacity: 0;
		visibility: hidden;
		position: absolute;
		top: 67px; 
		right: 30px;
		background-color: #fef;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
		border: 1px solid #ddd;
		min-width:150px;
		width:350px;
		height:auto;
		padding: 10px;
		padding-bottom:30px;
		z-index: 123123;
		transform: translateY(20px);
		transition: opacity 0.3s ease, visibility 0s 0.3s, transform 0.3s ease;
  }
  .dropdown-button:hover + .dropdown-content, .dropdown-content:hover {
    opacity: 1;
    visibility: visible;
	transform: translateY(0); /* Move the element to its original position */
    transition: opacity 0.3s ease, visibility 0s 0s, transform 0.3s ease;
  }
  .DropDownFlex{
		display: flex;
		flex-direction: column; 
		justify-content: center;
		align-items: center;
  }
  .login{
	  background:rgb(31, 29, 29);
	  border-radius: 20px;
	  cursor: pointer; 
	  margin-top:5px;
	  transition: background 0.3s ease; 
	  width:100px;
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
		.text-hover-a:hover{
			opacity:0.5;
		}
	</style>
 </head>
	<nav class="navbar navbar-expand-lg navbar-light bg-light navSticky">
        <div class="container-fluid">
            <a class="navbar-brand fs-3" href="index.php" style="font-family: Dancing Script, cursive;font-size:20px;">Momento</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarNav">
                <ul class="navbar-nav ms-auto gap-4 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="explorephotos.php">EXPLORE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">HIRE A DESIGNER</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">FIND JOBS</a>
                    </li>
					<?php
					if(!isset($_SESSION['data'])){
						echo '	<li class="nav-item">
									<a class="nav-link" href="account/register.php">Sign up</a>
								</li>
								<li class="login">
									<a class="nav-link text-white text-center" href="account/login.php">Login</a>
								</li>';
					}
					else{ 
					?>
					<ul>
					<div class = "dropdown-button"><img style = "border-radius:50%;margin-right:20px;object-fit: cover;border-radius:100%;height:55px;width:55px;" src = "<?=$data['picture']?>" width = '55' height = '55'></div>
					<div class = "dropdown-content">
						<div class = "DropDownFlex">
							<img style = "border-radius:50%;object-fit: cover;border-radius:100%;height:55px;width:55px;" src = "<?=$data['picture']?>" width = '55' height = '55'>
							<div style = "font-weight: 900;margin-top:5px;text-transform: capitalize;"><?php
							
							$stmt = $pdo->prepare('select account_type from accounts where id = ?');
							$stmt->execute(array($_SESSION['data']['id']));
							$account_type = $stmt->fetch(PDO::FETCH_ASSOC);
							if($account_type['account_type']=='user'){
								echo $_SESSION['data']['name'];
							}elseif($account_type['account_type']=='business'){
								echo $_SESSION['data']['business_name'];
							}
							
							?></div>
						</div>
						<div style = "padding:0 20px;margin-top:20px;">
							<?php
								if($account_type['account_type']==='business'){
							?>
									<div style ="justify-content:left;text-align:left;">
										<a href = "profile.php" class = "text-dark text-decoration-none text-hover-a">Profile</a>
									</div>
									<hr style = "opacity:0.2; color:gray;">
							<?php
								}
							?>
							
							<div style ="justify-content:left;text-align:left;">
								<a href = "edit-profile.php" class = "text-dark text-decoration-none text-hover-a">Settings</a>
							</div>
							<hr style = "opacity:0.2; color:gray;">
							<div style ="text-align:left;">
								<a href = "logout.php" class = "text-dark text-decoration-none text-hover-a">Sign Out</a>
							</div>
						</div>
					</div>
					</ul>
					<?php
					}
					?>
                </ul>
            </div>
        </div>
    </nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



			