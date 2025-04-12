
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="css/navbar.css">
    <style>
body{
    background-color: #044040;
	overflow-x:hidden;
}
 /* Custom checkbox container */
 .custom-checkbox {
      position: relative;
      display: inline-block;
      width: 150px;
      height: 50px;
      padding: 5px;
    }

    /* Hide the default checkbox */
    .custom-checkbox input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }

    /* Custom checkbox styling */
    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #000000;
      border: 2px solid #ddd;
      border-radius: 12px;
      text-align: center;
      line-height: 40px; /* Adjust line height to center text vertically */
      font-size: 18px;
      color: azure;
      transition: background-color 0.3s, color 0.3s;
    }

    /* On mouse-over */
    .custom-checkbox input:hover ~ .checkmark {
      background-color: #52160b;
    }

    /* When checkbox is checked */
    .custom-checkbox input:checked ~ .checkmark {
      background-color:#044040;  /* Bootstrap's primary color */
      color: azure;
    }

    </style>
</head>
<body>
	<div class="container mt-0 my-5 shadow-lg" style="background-color: #591C21;">
		<div class="row">
			<div class="col-md-12  mt-5 text-center">
				<img src="img/vertical-logo.webp" height="200px" width=" 200px" alt="MOMENTO" class="text-center">
			</div>
		</div>
	<div class="row ">
		<div class="h1 text-center  pb-2" style="color: whitesmoke">
			<strong>Browse by interests</strong>
		</div>
	</div>
<div class="row">
    <p class="h5 text-center" style=" color:#065757;"><b>Select some interests to help us <br> personalize your experiance on Momento</b></p>
</div>

    
  
     <div class="row">
        <div class="col-md-12 text-center ">
            <form>
<div class="row justify-content-md-center justify-content-sm-center justify-content-xs-center   mb-2 gap-md-1">
    

    <label class="custom-checkbox ">
        <input type="checkbox" name="Technology" id="Technology" >
        <span class="checkmark">Technology</span> 
      </label>
   
    <label class="custom-checkbox ">
        <input type="checkbox" name="Creativity" id="Creativity">
        <span class="checkmark">Creativity</span> 
      </label>
   
    

    <label class="custom-checkbox">
        <input type="checkbox" name="Social " id="Social">
        <span class="checkmark">Social</span> 
      </label>
  


    <label class="custom-checkbox">
        <input type="checkbox" name="Nature" id="Nature">
        <span class="checkmark">Nature</span> 
      </label>
    
</div>
<div class="row justify-content-md-center justify-content-sm-center justify-content-xs-center  mb-2 gap-md-1">
     
        <label class="custom-checkbox ">
            <input type="checkbox" name="Bloggs" id="Bloggs">
            <span class="checkmark">Bloggs</span> 
          </label>
       
  
        <label class="custom-checkbox ">
            <input type="checkbox" name="Art" id="Art">
            <span class="checkmark">Art</span> 
          </label>
       
    
    
        <label class="custom-checkbox">
            <input type="checkbox" name="Architecture" id="Architecture">
            <span class="checkmark">Architecture</span> 
          </label>
      
      
    
        <label class="custom-checkbox">
            <input type="checkbox" name="Painting" id="Painting">
            <span class="checkmark">Painting</span> 
          </label>
        </div>
<div class="row justify-content-md-center justify-content-sm-center justify-content-xs-center    mb-2 gap-md-1">

          
      
            <label class="custom-checkbox ">
                <input type="checkbox">
                <span class="checkmark">Cars</span> 
              </label>
           
            <label class="custom-checkbox ">
                <input type="checkbox">
                <span class="checkmark">Designs</span> 
              </label>
           
        
        
            <label class="custom-checkbox">
                <input type="checkbox">
                <span class="checkmark">Technology</span> 
              </label>
          
        
        
            <label class="custom-checkbox">
                <input type="checkbox">
                <span class="checkmark">Technology</span> 
              </label>

              </div>
<div class="row mb-3">
<div class="col-md-12 text-center">
   
    <button type="clear" class=" btn btn-lg btn-danger" value="clear"> clear</button>
    <button type="submit" class=" btn btn-lg  btn-primary " value="submit"> submit</button>
    
</div>
</div>
</form>
             





</div>
</div>



</div>






</body>
</html>


 






    <script src="js/bootstrap.js"></script>
</body>
</html>