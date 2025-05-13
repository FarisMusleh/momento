<?php
	session_start();
	if(isset($_SESSION['data'])){
		header('location: ../index.php');
		exit();
	}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<link href = "../css/bootstrap/bootstrap.css" rel = "stylesheet">
	<style>
	body{
		background:url(img/road.jpg) no-repeat;
		background-size: cover;
		backdrop-filter: blur(3px);
	}
	h1 {
		position: relative;
		text-align:right;
	}
	h1::before {
		background-color: #f0ad4e;
		content: "";
		position: absolute;
		width: 250px;
		height: 30px;
		right: -2px;
		bottom: 0;
		z-index: -1;
		transform: rotate(-3deg);
	}
	@media (max-width: 829px) {
		h1 {
			display: none;
		}
	}
	.unselect{
		user-select: none;
	}
	.form-check-input {
    width: 1em;
    height: 1em;
    border: 1.5px solid #6c757d; /* Visible gray border */
    border-radius: 0.25rem;
  }
  
  #showTermsBtn {
            
            text-decoration:underline;
            color: black;
            border: none;
            border-radius: 2px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            transition: background 0.3s;
        }

        #showTermsBtn:hover {
            text-shadow:1px 1px 10px #55555555;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content {
            background-color: #ffffff;
            margin: 5% auto;
            padding: 30px;
            width: 90%;
            max-width: 700px;
            height: 80%;
            overflow-y: auto;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.4s ease-out;
        }

        .close {
            float: right;
            font-size: 22px;
            color: #888;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111827;
        }

        h3 {
            font-size: 18px;
            margin-top: 20px;
            color: #1f2937;
        }

        p,
        li {
            margin: 10px 0;
            color: #374151;
            line-height: 1.6;
        }

        ul {
            padding-left: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
  
  
	</style>
	<link href="../css/toaster.css" rel="stylesheet"/>
	<script src="../js/toaster.js"/>
</head>
<body class = 'bg-dark'>
<div class="modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>toastr.warning('My name is Inigo Montoya. You killed my father, prepare to die!');</script>
	<div class="d-flex align-items-center vh-100 vw-100 flex-row justify-content-around">
		<div class = 'p-5 py-4 h-auto min-w-custom bg-light' style = 'width:500px;min-width:400px;'>
			<h2 class = 'text-center unselect'>Create a user account</h2>
			<form method = "post" action = "class-register.php">
				<!--ACCOUNT TYPE-->
				<div class = "d-flex justify-content-around my-3">
					<input type="radio" class="btn-check" name="account" id="success-outlined" autocomplete="off" value = "user" checked>
					<label class="btn btn-outline-success rounded-0" for="success-outlined">User account</label>
					<input type="radio" class="btn-check" name="account" id="danger-outlined" autocomplete="off" value = "business">
					<label class="btn btn-outline-danger rounded-0" for="danger-outlined">Business account</label>
				</div>
				<!--USERNAME-->
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="username" placeholder="Username" name = "username" autocomplete="username" value = "" required>
					<label for="username">Username</label>
					<div class="" id = "username-status"></div>
				</div>
				<!--EMAIL-->
				<div class="form-floating mb-3">
					<input type="email" class="form-control" id="email" placeholder="name@example.com" name = "email" value = "" autocomplete="email" required>
					<label for="email">Email address</label>
				</div>
				<!--PASSWORD-->
				<div class="form-floating">
					<input type="password" class="form-control" id="password" placeholder="Password" autocomplete="off" name = "password" required>
					<label for="password" class = "form-label">Password</label>
				</div>
				<!--GENDER-->
				<div class = "d-flex gap-3 mt-3 unselect">
					<div class="form-check">
						<input class="form-check-input" type="radio" name="gender" id="male" value="male" checked="" value = "male">
						<label class="form-check-label" for="male">Male</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="gender" id="female" value="female">
						<label class="form-check-label" for="female">Female</label>
					</div>
				</div>
				<div class="mt-3">
					<input type = "checkbox" class="form-check-input" id="agree">
					<lable><a id="showTermsBtn">Show Terms</a></label>
				</div>
				<!--REGISTER-->
				<div class = "d-flex align-items-center justify-content-center">
					<button id = "submit" type="submit" class="btn btn-primary rounded-0 mt-4 w-100" name = "enter" disabled>REGISTER</button>
				</div>		
			</form>
			<small>Already have momento account?<a href = "login.php">login</a></small>
		</div>
		<h1 class = "unselect">JOIN OUR<br> COMMUNITY</h1>
	</div>
	
	
	
	
	
	
	
	
	
	<div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Terms & Conditions for Momento</h2>

            <p>
                Welcome to Momento. By accessing or using this website, you agree to be bound by the following terms and
                conditions. These terms are designed in accordance with the applicable laws of the Hashemite Kingdom of
                Jordan, including but not limited to the Cybercrime Law, the Copyright Law, and the Telecommunications
                Law.
            </p>

            <h3>1. Responsibility for Uploaded Images and Content</h3>
            <ul>
                <li>Users are solely and fully responsible for any images or content they upload to the platform.</li>
                <li>Momento bears no legal or civil liability for any content uploaded by users.</li>
                <li>Any image or content that violates Jordanian law (as of the year 2025) is the sole responsibility of
                    the user who uploaded it. This includes, but is not limited to:</li>
                <ul>
                    <li>Violation of individuals’ privacy.</li>
                    <li>Breach of intellectual property or copyright.</li>
                    <li>Incitement to hatred, violence, racism, or discrimination.</li>
                    <li>Obscene or unethical content that contradicts public morals.</li>
                    <li>Use of personal photos without explicit consent.</li>
                </ul>
            </ul>

            <h3>2. Ownership and Usage Rights</h3>
            <ul>
                <li>Any image uploaded without clear prior agreement or license shall be considered proprietary content
                    of Momento, and the platform reserves the right to use, modify, or republish it at its discretion.
                </li>
                <li>By uploading content to Momento, users grant the platform a non-exclusive license to use such
                    content within the platform’s services and promotional materials.</li>
            </ul>

            <h3>3. Compliance with Jordanian Law</h3>
            <ul>
                <li>Users agree not to use the platform for any unlawful or unauthorized purposes under the laws of
                    Jordan.</li>
                <li>Uploading or sharing any content that violates Jordanian law, including the Cybercrime Law No. 27 of
                    2015, is strictly prohibited.</li>
                <li>The platform reserves the right to cooperate with law enforcement authorities and judicial entities
                    in the event of any legal violation involving a user.</li>
            </ul>

            <h3>4. Account and Content Management</h3>
            <ul>
                <li>Momento reserves the right to suspend or delete any user account that violates these terms or
                    uploads content deemed illegal or inappropriate.</li>
                <li>The platform may remove any uploaded content without prior notice if it violates internal policies
                    or national regulations.</li>
            </ul>

            <h3>5. Privacy and Data Protection</h3>
            <ul>
                <li>Momento is committed to maintaining the confidentiality of user data and will not share it with any
                    third party without explicit consent, unless required by law or judicial authorities.</li>
                <li>Users have the right to request permanent deletion of their personal data from the platform’s
                    database.</li>
            </ul>

            <h3>6. Child Protection</h3>
            <ul>
                <li>Users are strictly prohibited from uploading any images or content involving minors (under 18 years
                    of age) without verified parental or legal guardian consent.</li>
                <li>Any violation of this policy may result in account suspension and legal action in accordance with
                    child protection laws in Jordan.</li>
            </ul>

            <h3>7. Changes to Terms</h3>
            <ul>
                <li>These terms and conditions may be updated at any time without prior notice. Continued use of the
                    platform after changes are posted will be considered acceptance of those changes.</li>
            </ul>
        </div>
    </div>

    <script>
        const modal = document.getElementById("termsModal");
        const btn = document.getElementById("showTermsBtn");
        const close = document.getElementById("closeModal");

        btn.onclick = () => modal.style.display = "block";
        close.onclick = () => modal.style.display = "none";
        window.onclick = (event) => {
            if (event.target == modal) modal.style.display = "none";
        }
    </script>

	
	
	
	
	
	
	
	
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  const checkbox = document.getElementById('agree');
  const submitBtn = document.getElementById('submit');

  checkbox.addEventListener('change', function () {
    submitBtn.disabled = !this.checked;
  });
	$(document).ready(function(){
		$('#username').on('input', function(){
			let temp = $(this).val();
			if(temp.length > 0){
				setTimeout(function() {
					$.ajax({
						url: 'check-username.php',
						type: 'post',
						data: {key:temp},
						success: function(response){
							if(response==true){
								if ($('#username').hasClass('is-invalid')) {
									$('#username').removeClass('is-invalid');								
								}
								$("#username").addClass("is-valid");
								$('#username-status').text("Username is available.");									
							}else{
								if ($('#username').hasClass('is-valid')) {
									$('#username').removeClass('is-valid');
								}
								$("#username").addClass("is-invalid");
								$('#username-status').text("A user with that username already exist.");								
							}
						}
					});
				});
			}else{
				$('#username-status').text('');
				if($("#username").hasClass("is-valid")){
					$("#username").removeClass("is-valid");
				}else if($("#username").hasClass("is-invalid")){
					$("#username").removeClass("is-invalid");
				}
			}
		});
	});
</script>
