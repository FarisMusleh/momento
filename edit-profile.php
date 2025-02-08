<?php 
	session_start(); 
	require('pdo.php');
	if(isset($_SESSION['data'])){
		$data = $_SESSION['data'];
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <title>Profile</title>
    <style>
     
    </style>

</head>
<body>
    <?php require('header.php'); ?>
    <div class="container-fluid pt-3 bg-primary ">
        
<div class="container-md py-5 px-5" style="min-height: fit-content;border: 2px solid azure; border-radius:3%;" >
<div class="text-center">
        <img src="<?=$data['picture']?>" alt="Profile" width="200px" height="200px" class="rounded-circle">
     
    </div>
    <h1 class="text-center pt-5 text-warning">Edit Account Info </h1>
    
    <div class="row mt-3">
    <div class="col-md-6">
        <label class="text-warning fs-3"> Set new email</label>
        <input type="email" name="email" id="email" class="form-control"  required>
    </div>
    <div class="col-md-6">
        <label class="text-warning fs-3">Password</label>
        <input type="password" name="pass" id="pass" class="form-control" required>
    </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <label class="text-warning fs-3">New password</label>
        <input type="password" name="pass" id="pass" class="form-control"  required>
    </div>
    <div class="col-md-6">
        <label class="text-warning fs-3">Description</label>
        <input type="text" name="Description" id="Description" class="form-control" placeholder="Description" required>
    </div>

    </div>
    <div class="row">
        <div class="col-md-12 ">
            <div class="text-center">
        <label class="text-warning pt-3 fs-4">Change Profile Picture</label>
        <input type="file" class="form-control mx-auto  text-danger text-center"></div>
    </div>
    </div>
    <hr>
    <h1 class="text-center text-warning">Personal Information </h1>
    <div class="row">
    <div class="col-md-6">
        <label class="text-warning fs-3"> Email Address</label>
        <input type="email" name="Email" id="Email" class="form-control"  required>
    </div>
    <div class="col-md-6">
        <label class="text-warning fs-3">Twitter</label>
        <input type="text" name="Twitter" id="Twitter" class="form-control" >
    </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <label class="text-warning fs-3">Buisness number</label>
        <input type="text" name="BuisnessNumber" id="BuisnessNumber" class="form-control"  required>
    </div>
    <div class="col-md-6">
        <label class="text-warning fs-3">Country</label>
        <input type="text" name="Country" id="Country" class="form-control" placeholder="Jordan" required>
    </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center pt-5">
        <button type="submit" value="save" class="btn btn-warning">Save changes</button>
        <button type="reset" class="btn btn-danger">Reset</button>
    </div>
    
        </div>
    </div>
</div>
  


  





</div><!-- end of col-md-12-->
</div>
</div>
</body>
</html>