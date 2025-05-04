<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../pdo.php';
if (isset($_SESSION['data']) && isset($_GET['photographer_id'])) {
    $data = $_SESSION['data'];
	$photographer_id = $_GET['photographer_id'];
}else{
	header('location: ../index.php');
}
$sql_photographer = $pdo->prepare('select * from business_profiles where id = ?');
$sql_photographer->execute([$photographer_id]);
$photographer = $sql_photographer->fetch();
if(!$photographer){
	header('location: ../index.php');
}
$sql_services = $pdo->prepare('select * from appointment_services where photographer_id = ?');
$sql_services->execute([$photographer_id]);
$services = $sql_services->fetchAll();

$sql_date = $pdo->prepare('select date,duration_minutes from appointments where photographer_id = ?');
$sql_date->execute([$photographer_id]);
$date = $sql_date->fetchAll();

$sql_reviews = $pdo->prepare('select comment,photographer_id,user_id,name,user_profiles.id from reviews, user_profiles where photographer_id = ? and user_profiles.id = user_id and rate >= 4');
$sql_reviews->execute([$photographer_id]);
$reviews = $sql_reviews->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Photography Session</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        
        body {
            background-color: #f2f2f2;
            color: #1a1a1a;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: #000;
            color: #fff;
            padding: 20px 0;
        }
        
        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .hero {
            height: 350px;
            background-color: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/api/placeholder/1200/600') center/cover no-repeat;
            z-index: 1;
        }
        
        .hero-content {
            text-align: center;
            z-index: 2;
            padding: 0 20px;
        }
        
        .hero h1 {
            font-size: 36px;
            margin-bottom: 15px;
            font-weight: 300;
            letter-spacing: 2px;
        }
        
        .hero p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .main {
            padding: 60px 0;
            background-color: white;
        }
        
        .appointment-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }
        
        .appointment-info {
            flex: 1;
            min-width: 300px;
        }
        
        .appointment-info h2 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 400;
        }
        
        .service-type {
            margin-bottom: 30px;
        }
        
        .service-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .service-item:hover {
            background-color: #f9f9f9;
            padding-left: 10px;
        }
        
        .service-item h3 {
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .service-item p {
            font-size: 14px;
            color: #666;
        }
        
        .service-item .price {
            font-weight: 600;
            color: #000;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        
        .session-details {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .detail-item {
            background-color: #f2f2f2;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            color: #333;
        }
        
        .testimonials {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
        
        .testimonials h3 {
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 400;
        }
        
        .testimonial-item {
            padding: 20px;
            background-color: #f9f9f9;
            border-left: 3px solid #000;
            margin-bottom: 15px;
        }
        
        .testimonial-item p {
            font-style: italic;
        }
        
        .client-name {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
            text-align: right;
        }
        
        .appointment-form {
            flex: 1;
            min-width: 300px;
            background-color: #f8f8f8;
            padding: 30px;
            border-radius: 4px;
        }
        
        .appointment-form h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 400;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            background-color: white;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        button {
            background-color: #000;
            color: white;
            border: none;
            padding: 14px 30px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        
        button:hover {
            background-color: #333;
        }
        
        .features {
            padding: 40px 0;
            background-color: #f2f2f2;
        }
        
        .features-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            text-align: center;
        }
        
        .feature {
            flex: 1;
            min-width: 250px;
            padding: 30px 20px;
            transition: transform 0.3s ease;
        }
        
        .feature:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 24px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #000;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .feature h3 {
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .feature p {
            color: #666;
            font-size: 14px;
        }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .form-checkbox input {
            width: auto;
        }
        
        .form-notes {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
        }
        
        footer {
            background-color: #000;
            color: white;
            padding: 30px 0;
            font-size: 14px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .footer-section {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-section h3 {
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 400;
        }
        
        .footer-section p {
            margin-bottom: 10px;
            color: #ccc;
        }
        
        .social-links a {
            color: white;
            margin-right: 15px;
            font-size: 18px;
            text-decoration: none;
        }
        
        .copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #333;
            color: #999;
        }
        
        .faq {
            padding: 60px 0;
            background-color: white;
            border-top: 1px solid #eee;
        }
        
        .faq h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 300;
            font-size: 28px;
        }
        
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .faq-item {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        
        .faq-question {
            font-weight: 500;
            font-size: 18px;
            margin-bottom: 10px;
            cursor: pointer;
            position: relative;
            padding-right: 20px;
        }
        
        .faq-question:after {
            content: attr(data-icon, "+");
            position: absolute;
            right: 0;
            top: 0;
        }
        
        .faq-answer {
            color: #666;
            line-height: 1.6;
        }
        
        .newsletter-signup {
            margin-top: 30px;
            padding: 20px;
            background-color: #111;
            border-radius: 4px;
            text-align: center;
        }
        
        .newsletter-signup h3 {
            margin-bottom: 15px;
            font-weight: 400;
            font-size: 18px;
        }
        
        .newsletter-form {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px 0 0 4px;
        }
        
        .newsletter-form button {
            border-radius: 0 4px 4px 0;
            padding: 0 20px;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 28px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .appointment-info, 
            .appointment-form {
                flex: 100%;
            }
        }
    </style>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include Bootstrap Icons if not already -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="/momento/css/header.css">
	<!-- FLATPICKER -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<!-- Toastify -->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>
    <?php require('../header.php')?>
    
    <section class="hero">
        <div class="hero-content">
            <h1>BOOK YOUR SESSION<br>With <?=$photographer['business_name']?></h1>
            <p>Capture your special moments with professional photography tailored to your vision.</p>
        </div>
    </section>
    
    <section class="features">
        <div class="container">
            <div class="features-container">
                <div class="feature">
                    <div class="feature-icon">✓</div>
                    <h3>Professional Equipment</h3>
                    <p>High-end cameras and lighting for exceptional quality</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">✓</div>
                    <h3>Fast Turnaround</h3>
                    <p>Delivery within 7-14 days of your session</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">✓</div>
                    <h3>Free Consultation</h3>
                    <p>Discuss your vision before your session</p>
                </div>
            </div>
        </div>
    </section>
	
    <section class="main">
        <div class="container">
            <div class="appointment-container">
                <div class="appointment-info">
                    <h2>Our Photography Services</h2>
                    <div class="service-type">
						<?php foreach($services as $service){?>
							<div class="service-item">
								<h3><?=$service['category']??""?></h3>
								<p><?=$service['description']??""?></p>
								<p class="price"><?=$service['price']??""?>$</p>
								<div class="session-details">
									<span class="detail-item"><?=$service['detail_1']??""?></span>
									<span class="detail-item"><?=$service['detail_2']??""?></span>
									<span class="detail-item"><?=$service['detail_3']??""?></span>
								</div>
							</div>
						<?php }?>
                    </div>
                    
                    <div class="testimonials">
                        <h3>Clients Reviews</h3>
						<?php foreach($reviews as $row){?>
							<div class="testimonial-item">
								<p><?=$row['comment']?></p>
								<p class="client-name">— <?=$row['name']?></p>
							</div>
						<?php }?>
                    </div>
                </div>
                <div class="appointment-form">
                    <h2>Schedule Your Session</h2>
                    <form action = "submit_appointment.php" method = "post">
                        <!-- Hidden input for user_id (will be populated after login) -->
                        <input type="hidden" id="user_id" name="user_id" value="<?=$_SESSION['data']['id']?>">
                        
                        <!-- Hidden input for status (default to "pending") -->
                        <input type="hidden" id="status" name="status" value="pending">
                        
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="full_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone_number" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <option value="">Select a category</option>
								<?php foreach($service as $row){?>
                                <option value="<?=$row['category']??""?>"><?=$row['category']??""?></option>
								<?php }?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location</label>
                            <select id="location" name="location" required>
                                <option value="">Select a location</option>
                                <option value="studio">Studio</option>
                                <option value="outdoor">Outdoor</option>
                                <option value="client_location">Client's Location</option>
                                <option value="venue">Event Venue</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="duration_minutes">Session Duration</label>
                            <select id="duration_minutes" name="duration_minutes" required>
                                <option value="">Select duration</option>
                                <option value="30">30 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                                <option value="180">3 hours</option>
                                <option value="240">4 hours</option>
                                <option value="480">Full day (8 hours)</option>
                            </select>
                        </div>
                        
                        
                        <div class="form-group">
							<label for="appointmentDateTime">Date & Time</label>
							<input type="text" id="appointmentDateTime" name="date" class="date-picker-input" placeholder="Select date & time" readonly>
						</div>
                        
                        
                        <div class="form-group">
                            <label for="notes">Additional Notes</label>
                            <textarea id="notes" name="notes" placeholder="Tell us about your vision for this photoshoot..."></textarea>
                        </div>
                        
                        <div class="form-notes">
                            <p>A 50% deposit is required to secure your booking date. Our team will contact you within 24 hours to confirm your appointment and process the deposit.</p>
                        </div>
                        <input type = "hidden" value = "<?=$photographer_id?>" name = "photographer_id">
                        <button type="submit">Book Appointment</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </section>
    <section class="faq">
        <div class="container">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">What should I wear to my photoshoot?</div>
                    <div class="faq-answer">We recommend solid colors and avoiding busy patterns. For group photos, coordinate colors but avoid matching exactly. We'll provide a detailed preparation guide after booking.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">How long until I receive my photos?</div>
                    <div class="faq-answer">For portrait sessions, you'll receive your gallery within 7-14 days. Wedding collections take 4-6 weeks due to the larger volume of images.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">What happens if it rains on the day of an outdoor shoot?</div>
                    <div class="faq-answer">We monitor weather conditions closely and will contact you 24-48 hours before your session if rescheduling is necessary. There's no fee for weather-related reschedules.</div>
                </div>
            </div>
        </div>
    </section>
    
    <?php require('../footer.php');?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category selection affects duration options
            const categorySelect = document.getElementById('category');
            const durationSelect = document.getElementById('duration_minutes');
            
            categorySelect.addEventListener('change', function() {
                const category = this.value;
                
                // Reset duration options
                durationSelect.innerHTML = '<option value="">Select duration</option>';
                
                // Add appropriate duration options based on category
                if (category === 'portrait' || category === 'family') {
                    addOption(durationSelect, '30', '30 minutes');
                    addOption(durationSelect, '60', '1 hour');
                    addOption(durationSelect, '90', '1.5 hours');
                } else if (category === 'engagement' || category === 'commercial') {
                    addOption(durationSelect, '60', '1 hour');
                    addOption(durationSelect, '90', '1.5 hours');
                    addOption(durationSelect, '120', '2 hours');
                    addOption(durationSelect, '180', '3 hours');
                } else if (category === 'wedding' || category === 'event') {
                    addOption(durationSelect, '120', '2 hours');
                    addOption(durationSelect, '240', '4 hours');
                    addOption(durationSelect, '360', '6 hours');
                    addOption(durationSelect, '480', 'Full day (8 hours)');
                } else {
                    // Default options
                    addOption(durationSelect, '30', '30 minutes');
                    addOption(durationSelect, '60', '1 hour');
                    addOption(durationSelect, '90', '1.5 hours');
                    addOption(durationSelect, '120', '2 hours');
                    addOption(durationSelect, '180', '3 hours');
                    addOption(durationSelect, '240', '4 hours');
                    addOption(durationSelect, '480', 'Full day (8 hours)');
                }
            });
            
            // Helper function to add options to select element
            function addOption(selectElement, value, text) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = text;
                selectElement.appendChild(option);
            }
            
            // Handle FAQ toggle
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const isVisible = answer.style.display === 'block';
                    
                    // Toggle answer visibility
                    answer.style.display = isVisible ? 'none' : 'block';
                    
                    // Toggle plus/minus icon
                    this.classList.toggle('active');
                    this.style.fontWeight = isVisible ? '500' : '600';
                    this.textContent = this.textContent.trim();
                    this.setAttribute('data-icon', isVisible ? '+' : '−');
                });
                
                // Initialize FAQ answers as hidden
                question.nextElementSibling.style.display = 'none';
            });
        });
    </script>
<script>
  // PHP will output JS array of busy slots with durations
  const busySlots = [
    <?php
    foreach ($date as $row) {
        echo "{ date: '" . $row['date'] . "', duration: " . $row['duration_minutes'] . " },";
    }
    ?>
  ];

  // Convert to objects with start and end Date
  const busyRanges = busySlots.map(slot => {
    const start = new Date(slot.date);
    const end = new Date(start.getTime() + slot.duration * 60000);
    return { start, end };
  });

  // Extract busy dates for day highlighting
  const busyDates = [...new Set(busySlots.map(s => s.date.split(" ")[0]))];

  flatpickr("#appointmentDateTime", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    time_24hr: true,
    inline: false,

    onChange: function (selectedDates, dateStr, instance) {
      const selected = selectedDates[0];

      const isOverlapping = busyRanges.some(range =>
        selected >= range.start && selected < range.end
      );

      if (isOverlapping) {
        Toastify({
          text: "This time is within a busy period. Please choose another.",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#ff4d4d",
          stopOnFocus: true
        }).showToast();
        instance.clear();
      }
    },

    onDayCreate: function (dObj, dStr, fp, dayElem) {
      const date = dayElem.dateObj.toISOString().split('T')[0];

      const slotsForDay = busySlots.filter(slot => slot.date.startsWith(date));
      if (slotsForDay.length > 0) {
        dayElem.classList.add("busy-day");

        const times = slotsForDay.map(slot => {
          const start = new Date(slot.date);
          const end = new Date(start.getTime() + slot.duration * 60000);
          const format = d => d.toTimeString().substring(0, 5);
          return `${format(start)} - ${format(end)}`;
        });

        const tooltip = document.createElement("div");
        tooltip.className = "custom-tooltip";
        tooltip.innerText = "Busy: " + times.join(", ");
        dayElem.appendChild(tooltip);
      }
    }
  });
</script>





<style>

    #appointmentDateTime {
		position: relative; 
    width: 220px;
    padding: 8px 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  #appointmentDateTime:focus {
    border-color: #5a9df9;
    box-shadow: 0 0 0 2px rgba(90, 157, 249, 0.2);
  }

.flatpickr-calendar {
  position: absolute !important;
  z-index: 0 !important;
  overflow: visible !important;
  top: 100%;
  left: 0;
  margin-top: 5px;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  background-color: #fff;
}

/* Year navigation arrows */
.flatpickr-prev-month,
.flatpickr-next-month {
  color: #444;
  transition: color 0.2s;
  font-size: 16px;
  cursor: pointer;
}

.flatpickr-prev-month:hover,
.flatpickr-next-month:hover {
  color: #5a9df9;
}

/* Timeup and Time down arrows */
.flatpickr-time input {
  font-size: 13px;
  height: 30px;
  padding-right: 30px;
}

.flatpickr-time .flatpickr-hour {
  position: relative;
}

/* Up/down arrows on time input (hours and minutes) */
.flatpickr-time .flatpickr-hour::after,
.flatpickr-time .flatpickr-minute::after {
  content: '';
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  width: 0;
  height: 0;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 6px solid #5a9df9;
  cursor: pointer;
}

.flatpickr-time .flatpickr-minute::after {
  border-top: 6px solid #5a9df9;
  top: calc(50% + 10px);
}

.flatpickr-time .flatpickr-hour::after {
  border-bottom: 6px solid #5a9df9;
  top: calc(50% - 10px);
}

.flatpickr-time .flatpickr-hour:hover::after,
.flatpickr-time .flatpickr-minute:hover::after {
  border-color: #007bff;
}

/* Year and month arrows */
.flatpickr-months .flatpickr-prev-month,
.flatpickr-months .flatpickr-next-month {
  cursor: pointer;
}

.flatpickr-prev-month {
  margin-right: 10px;
}

/* Hover effect for arrows */
.flatpickr-prev-month:hover,
.flatpickr-next-month:hover {
  color: #5a9df9;
}

  .flatpickr-day {
    position: relative;
	overflow: visible !important;
  }
.flatpickr-day.busy-day {
  position: relative;
  box-shadow: 0 0 0 2px red inset;
  border-radius: 50%;
  color: #000 !important;
  overflow: visible !important;
}

.custom-tooltip {
  display: none;
  position: absolute;
  bottom: 120%;
  left: 50%;
  transform: translateX(-50%);
  background-color: #333;
  color: #fff;
  padding: 2px 6px;
  font-size: 10px;
  white-space: nowrap;
  border-radius: 4px;
  z-index: 9999;
  pointer-events: none;
  overflow: visible !important;
}
.flatpickr-innerContainer,
.flatpickr-days,
.flatpickr-day {
  overflow: visible !important;
}
.flatpickr-day.busy-day:hover .custom-tooltip {
  display: block;
   overflow: visible !important;
}

.flatpickr-months .flatpickr-prev-month,
.flatpickr-months .flatpickr-next-month {
  background: #f0f0f0;
  border: 1px solid #ccc;
  width: 28px;
  height: 28px;
  border-radius: 4px; /* slight rounding for a square look */
  display: flex;
  align-items: center;
  justify-content: center;
  color: #333;
  font-size: 16px;
  transition: background 0.2s, border-color 0.2s;
}

.flatpickr-months .flatpickr-prev-month:hover,
.flatpickr-months .flatpickr-next-month:hover {
  background: #e0e0e0;
  border-color: #999;
  cursor: pointer;
}

/* Square-style up/down time arrows */
.flatpickr-time .arrowUp,
.flatpickr-time .arrowDown {
  background: #f0f0f0;
  border: 1px solid #ccc;
  width: 22px;
  height: 22px;
  border-radius: 4px;
  margin: 2px auto;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #333;
  font-size: 12px;
  transition: background 0.2s, border-color 0.2s;
}

.flatpickr-time .arrowUp:hover,
.flatpickr-time .arrowDown:hover {
  background: #e0e0e0;
  border-color: #999;
  cursor: pointer;
}
</style>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>