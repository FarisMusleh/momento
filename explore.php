<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/explore.css">
    <title>Momento</title>


    </head>
    <body>
	<?php require("header.php"); ?>    

    <div class="container-fluid ">
        <div class=" row d-flex flex-row">
<div class="col-md-2 m-0 p-0 bg-warning shadow-lg  px-2 ">
 <div class="row d-flex flex-md-column text-start fw-bolder gy-md-2 " >
      <a href="#" class=" nav-link bg-primary p-3"   style="border: 2px solid rgb(0, 0, 0);"><h4 class="text-warning">Categories 👇</h4></a>
      <a href="#" class=" nav-link text-primary bg-light p-3"   style="border: 2px solid black;"><h4>Wars</h4></a>
      <a href="#" class=" nav-link text-primary bg-light p-3"   style="border: 2px solid black;"><h4 >Archeticture</h4></a>
      <a href="#" class=" nav-link text-primary bg-light p-3" style="border: 2px solid black;"><h4>wedding</h4></a>
      <a href="#" class=" nav-link text-primary bg-light p-3" style="border: 2px solid black;"><h4>Cars</h4></a>
      <a href="#" class=" nav-link text-primary bg-light p-3" style="border: 2px solid black;"><h4>Nature</h4></a>
      <a href="#" class=" nav-link text-primary bg-light p-3" style="border: 2px solid black;"><h4>Graduation</h4></a>

    </div>

 </div>
  


<div class="col-md-10 bg-primary py-2 px-2 " style="border: 2px solid black;">



</div>
</div>
</div>
 



<!--pagination-->
      <div class="container-fluid bg-warning">
        <div class="row">
            <ul class="pagination justify-content-center pt-3">
              <li class="page-item disabled">
                <a class="page-link" href="#">&laquo;</a>
              </li>
              <li class="page-item active">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">2</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">3</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">4</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">5</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">&raquo;</a>
              </li>
            </ul>
          </div>
    </div>

    </body>
    </html>
