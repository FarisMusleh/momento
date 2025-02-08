<nav class="navbar header navbar-expand-lg bg-primary" data-bs-theme="dark" style="height: 70px;" >
	<div class="container-fluid">
	  <a class="navbar-brand " href="#">Momento<i class="bi bi-star-fill"></i></a>
	  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarColor01">
		<ul class="navbar-nav me-auto">
		  <li class="nav-item">
			<a class="nav-link" href="index.php">Home
			  <span class="visually-hidden">(current)</span>
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="index.php#Categories">categories</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="explore.php">Explore</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="#">About</a>
		  </li></ul>
			<?php
				if(!isset($_SESSION['data']))
				{
					echo '<ul class = "navbar-nav ms-auto"><li class="nav-item">
						<a class="nav-link btn-warning" href="account/login.php">Login</a>
						</li>
						<li class="nav-item">
						<a class="nav-link btn-warning" href="account/register.php">Register</a>
						</li>
						</ul>';
				}else{
					?>	
					<ul class = "navbar-nav ms-auto">
					<li class="nav-item dropdown">
						<span><?=$data['first-name']?> <?=$data['last-name']?></span>
						<a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><img src = "<?=$data['picture']?>" width = '50' height = '50'></a>
						
						<div class="dropdown-menu">
							<div class="dropdown-item"><?=$data['username']?></div>
							<div class="dropdown-item"><?=$data['email']?></div>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="profile.php">Profile</a>
							<a class="dropdown-item" href="edit-profile.php">Edit Profile</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="logout.php">Logout</a>
						</div>
					</li>
					</ul>
					<?php
				}
			
			?>

		
		<form class="d-flex">
			<?php 
			$current_page = basename($_SERVER['REQUEST_URI']); 
			if($current_page==="explore.php"){
				
			?>
		  <input class="form-control me-sm-2" type="search" placeholder="Search"/>
		  <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
		  <?php
			}
		  ?>
		</form>
	  </div>
	</div>
  </nav>
