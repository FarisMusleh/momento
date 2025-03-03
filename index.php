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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/home.css">
	<title>Momento</title>
</head>
<body> 

<?php require("header.php"); ?>    

<div class="container-fluid"  >

    <div class="row">
<!--start of right Section 😊--> 
    <div class="col-md-12 "   style=" background-color: rgb(156, 154, 154);margin:0;padding:0;overflow-x: hidden;  overflow-y: scroll; scrollbar-width: none;
  -ms-overflow-style: none;"> 
   <div class="card bg-primary p-1 w-100 h-85"  >
     <!-- Video with Overlay -->
     <div class="video-overlay-container">
      <div class="ratio ratio-16x9">
        <p>terst<p>
        <video autoplay muted loop   id="Top" >
          <source src="img/video.mp4" type="video/mp4">
          
        </video>    
      </div>
      <!-- Overlay Text -->
      <div class="video-overlay-text ">
        <img src="img/moomento-high-resolution-logo-transparent.png" alt="Mommento" width="600px" height="140px">
    
      <h4 class=" text-center text-warning p-4" style="  background-color: rgba(0, 0, 0, 0.2);">
         Welcome<br> to the first-ever platform connecting photographers and users to inspire and collaborate!</h4>
      </div>
    </div>
   
  
        </div>
<div class="row mt-2">
    <h1 class="text-center text-light bg-dark p-4" id="Categories">Categories</h1>
</div>

        <div class="row ">
        <div class="card-group">
            <div class="card">
              <img class="card-img-top overflow-hidden p-1" src="img/war.jpg" alt="Card image cap" height="300" width="300" >
              <div class="card-body">
                <h5 class="card-title ">Wars</h5>
                
              </div>
            </div>
            <div class="card">
              <img class="card-img-top p-1" src="img/Arch.jpg" alt="Card image cap" height="300" width="300">
              <div class="card-body">
                <h5 class="card-title">Archeticture</h5>
              
              </div>
            </div>
            <div class="card" >
              <img class="card-img-top p-1" src="img/wedding5.jpg" alt="Card image cap" height="300" width="300">
              <div class="card-body">
                <h5 class="card-title">wedding</h5>
              
              </div>
            </div>
        </div></div>
            <div class="row ">
                <div class="card-group">
            <div class="card">
                <img class="card-img-top p-1" src="img/BMW.png" alt="Card image cap" height="300" width="300" >
                <div class="card-body">
                  <h5 class="card-title">Cars</h5>
           
                </div>
              </div>
              <div class="card">
                <img class="card-img-top p-1 " src="img/Nature.webp" alt="Card image cap" height="300" width="300"  >
                <div class="card-body">
                  <h5 class="card-title">Nature</h5>
                 
                </div>
              </div>
              <div class="card">
                <img class="card-img-top p-1" src="img/Graduate.jpg" alt="Card image cap"height="300" width="300"  >
                <div class="card-body">
                  <h5 class="card-title">Graduation</h5>
                
                </div>
              </div>
            
          </div>
        </div>

<!--PHOTOGRAPHERS-->
        <div class="row m-0 p-0">
			<h1 class="bg-dark text-center text-light p-4 m-2" id="Photographers">Photographers </h1>
        </div>
        <div class="container-fluid bg-primary py-4">
        <div class="row d-flex flex-wrap my-5 text-center">
          <div class="col-md-4 mx-auto">
              <div class="card1 ms-5">
              <div class="background">
                  <img src="img/adam_rouhana.jpg" height="300px" width="300px">
              </div>
           
            </div>
            <div class="text-light text-center pt-5 fs-3 text-break"><h1> <a href="https://adamrouhana.com/"  class="text-warning">Adam Rouhana</a></h1>
              <p class="text-light">The Photographer Searching for Freedom in Palestine</p>

            </div>
          </div>
          <div class="col-md-4">
            <div class="card1 ms-5">
              <div class="background">
                  <img src="img/Steve_McCurry.jpg" height="300px" width="300px">
              </div>
         
            </div>
            <div class="text-light text-center pt-5 fs-3 text-break"><h1 class="text-warning"><a href="https://www.stevemccurry.com/"  class="text-warning">Steve McCurry</a></h1>
            <p class="text-light">American photographer.Renowned for his vibrant color photography and profound human connection</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card1 ms-5">
              <div class="background ">
                  <img src="img/ph1.jpg" height="300px" width="300px">
              </div>
           
            </div>
     
            <div class="text-light text-center pt-5 fs-3 text-break">
              <h1 class="text-warning"><a href="https://www.andreasgursky.com/en"  class="text-warning">Andreas Gursky</a></h1>
              <p class="text-light">German photographer. He is known for his large format architecture and landscape
                 colour photographs</p>

            </div>
          </div>
          </div>
     
          </div>
     


<!--  *************************  statistics ***************** -->
 <div class="row mt-2  ">
  <h1 class="text-center text-light bg-dark p-4" id="Statistics">Statistics</h1>
</div>
<div class="container-fluid bg-primary" id = "counter-section">
  <div class=" d-flex flex-wrap gap-5 justify-content-center py-5">
    
      <div class="outer mt-2">
          <div class="dot"></div>
          <div class="card2">
            <div class="ray"></div>
            <div class="text" id = "photographers-num">0</div>
            <div>Photographer</div>
            <div class="line topl"></div>
            <div class="line leftl"></div>
            <div class="line bottoml"></div>
            <div class="line rightl"></div>
          </div>
        </div>
  
      <div class="outer mt-2">
          <div class="dot"></div>
          <div class="card2">
            <div class="ray"></div>
            <div class="text" id = "users-num">0</div>
            <div>users</div>
            <div class="line topl"></div>
            <div class="line leftl"></div>
            <div class="line bottoml"></div>
            <div class="line rightl"></div>
          </div>
        </div>
  
      <div class="outer mt-2">
          <div class="dot"></div>
          <div class="card2">
            <div class="ray"></div>
            <div class="text" id = "photos-num">0</div>
            <div>Picture</div>
            <div class="line topl"></div>
            <div class="line leftl"></div>
            <div class="line bottoml"></div>
            <div class="line rightl"></div>
          </div>
        </div>
      </div>
  </div>
<!-- *********************   Tips start -->
<div class="row mt-2">
  
  <h1 class="text-center text-light bg-dark p-4" id="Tips">Tips</h1>
</div>

<div class="container-fluid bg-primary">
  <div class="row"><p style="text-shadow:3px 3px 5px #ffe4e4; font-family: Arial, Helvetica, sans-serif;"   class="text-light text-center pt-5 fs-2 fw-bolder">Discover helpful tips and tricks here to make the most of your experience!</p></div>
  <div class="row" >
   <div class="col-md-4">
    <div class="item-hints">
      <div class="hint" data-position="4">
        <span class="hint-radius"></span>
        <span class="hint-dot">Tips</span>
        <div class="hint-content do--split-children">
          <p>Use Navbar to navigate the website quickly and easily.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="item-hints">
      <div class="hint" data-position="4">
        <span class="hint-radius"></span>
        <span class="hint-dot">Tip</span>
        <div class="hint-content do--split-children">
          <p> Don’t forget to rate us!<br> Your feedback helps us improve.</p>
        </div>
      </div>
    </div>
  </div> 
  <div class="col-md-4">
    <div class="item-hints">
      <div class="hint" data-position="4">
        <span class="hint-radius"></span>
        <span class="hint-dot">Tip</span>
        <div class="hint-content do--split-children">
          <p> 

            Need help?<br> contact us anytime!</p>
        </div>
      </div>
    </div>
  </div>
  <div class="text-end">
<a href=""><abbr class="text-light">Top<img src="img/arrow.png" width="50px" height="50px"/> </abbr></a>
</div>

    </div>
    </div>
<!-- tips end-->
   <!-- contacts & footer-->
<div class="container-fluid bg-warning ">
<div class="row mt-0 ">
    <div class="col-md-12 text-end">
  <ul class="wrapper   p-0 text-center " style="height: min-content;">
      <li class="icon facebook">
        <span class="tooltip">Facebook</span>
        <svg
          viewBox="0 0 320 512"
          height="1.2em"
          fill="currentColor"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
          ></path>
        </svg>
      </li>
      <li class="icon twitter">
        <span class="tooltip">Twitter</span>
        <svg
          height="1.8em"
          fill="currentColor"
          viewBox="0 0 48 48"
          xmlns="http://www.w3.org/2000/svg"
          class="twitter"
        >
          <path
            d="M42,12.429c-1.323,0.586-2.746,0.977-4.247,1.162c1.526-0.906,2.7-2.351,3.251-4.058c-1.428,0.837-3.01,1.452-4.693,1.776C34.967,9.884,33.05,9,30.926,9c-4.08,0-7.387,3.278-7.387,7.32c0,0.572,0.067,1.129,0.193,1.67c-6.138-0.308-11.582-3.226-15.224-7.654c-0.64,1.082-1,2.349-1,3.686c0,2.541,1.301,4.778,3.285,6.096c-1.211-0.037-2.351-0.374-3.349-0.914c0,0.022,0,0.055,0,0.086c0,3.551,2.547,6.508,5.923,7.181c-0.617,0.169-1.269,0.263-1.941,0.263c-0.477,0-0.942-0.054-1.392-0.135c0.94,2.902,3.667,5.023,6.898,5.086c-2.528,1.96-5.712,3.134-9.174,3.134c-0.598,0-1.183-0.034-1.761-0.104C9.268,36.786,13.152,38,17.321,38c13.585,0,21.017-11.156,21.017-20.834c0-0.317-0.01-0.633-0.025-0.945C39.763,15.197,41.013,13.905,42,12.429"
          ></path>
        </svg>
      </li>
      <li class="icon instagram">
        <span class="tooltip">Instagram</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="1.2em"
          fill="currentColor"
          class="bi bi-instagram"
          viewBox="0 0 16 16"
        >
          <path
            d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"
          ></path>
        </svg>
      </li>
    </ul>
  </div>
  </div>

  </div>
  <div class=" container-fluid bg-dark  "  >
    <div class="row pt-2 text-center text-light ">
<p class="fs-5">Made with <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-balloon-heart-fill" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M8.49 10.92C19.412 3.382 11.28-2.387 8 .986 4.719-2.387-3.413 3.382 7.51 10.92l-.234.468a.25.25 0 1 0 .448.224l.04-.08c.009.17.024.315.051.45.068.344.208.622.448 1.102l.013.028c.212.422.182.85.05 1.246-.135.402-.366.751-.534 1.003a.25.25 0 0 0 .416.278l.004-.007c.166-.248.431-.646.588-1.115.16-.479.212-1.051-.076-1.629-.258-.515-.365-.732-.419-1.004a2 2 0 0 1-.037-.289l.008.017a.25.25 0 1 0 .448-.224l-.235-.468ZM6.726 1.269c-1.167-.61-2.8-.142-3.454 1.135-.237.463-.36 1.08-.202 1.85.055.27.467.197.527-.071.285-1.256 1.177-2.462 2.989-2.528.234-.008.348-.278.14-.386"/>
</svg> by <span  class="text-warning" style="text-decoration: underline;">Momento  Team </span> </p>
  </div>
</div>
 
  </div>

  </div>

    </div> 

            </div> <!--End of right Section  col-md-10 😊--> 
        
        
      </div> <!--End of bigger row 😊-->  


    </div> <!--End of bigger container 😊--> 
<!--The  end of home page 😎-->



</body>
</html>

	<script>
	function countUp(element, start, end, duration) {
  const range = end - start;
  const increment = range / (duration / 16);
  let current = start;

  function updateCounter() {
    current += increment;
    if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
      current = end;
      element.textContent = Math.round(current);
      return;
    }
    element.textContent = Math.round(current);
    requestAnimationFrame(updateCounter);
  }

  updateCounter();
}

const phgCount = document.getElementById("photographers-num");
const userCount = document.getElementById("users-num");
const phCount = document.getElementById("photos-num");

<?php
	$stmt1 = $pdo->query("SELECT COUNT(*) FROM images");
    $images = $stmt1->fetchColumn();
	$stmt2 = $pdo->query("SELECT COUNT(*) FROM `user-profiles`");
    $userProfiles = $stmt2->fetchColumn();
	$stmt3 = $pdo->query("SELECT COUNT(*) FROM `business-profiles`");
    $businessProfiles = $stmt3->fetchColumn();
?>
// Function to start animation when section is visible
function observeSection() {
  const counterSection = document.querySelector("#counter-section");

  const observer = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
			countUp(phgCount, 0, <?=$businessProfiles?>, 1000);
			countUp(userCount, 0, <?=$userProfiles?>, 1000);
			countUp(phCount, 0, <?=$images?>, 1000);// Start animation
			observer.disconnect(); // Stop observing after animation starts
        }
      });
    },
    { threshold: 0.5 } // Trigger when 50% of the section is visible
  );

  observer.observe(counterSection);
}

// Initialize observation
observeSection();

</script>