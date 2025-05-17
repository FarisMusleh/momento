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
    <title> Photography Booking | <?=$photographer['business_name']?></title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/header.css">
    <style>
        :root {
            --primary: #2a2a2a;
            --secondary: #f8f8f8;
            --accent: #d4af37;
            --text: #333;
            --light-text: #777;
            --white: #ffffff;
            --black: #000000;
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --border-radius: 10px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            line-height: 1.7;
            background-color: var(--secondary);
        }
        
        h1, h2, h3, h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--primary);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
        }
        
        /* Hero Section */
        .hero {
            height: 80vh;
            min-height: 700px;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1493863641943-9b68992a8d07?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            text-align: center;
            position: relative;
            margin-bottom: 80px;
        }
        
        .hero-content {
            max-width: 800px;
            padding: 0 30px;
        }
        
        .hero h1 {
            font-size: 3.8rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.4);
        color: var(--white);
        }
        
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            letter-spacing: 0.5px;
        }
        
        /* Features Section */
        .features {
            padding: 100px 0;
            background-color: var(--white);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 2.8rem;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
        }
        
        .features-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        
        .feature {
            background: var(--white);
            padding: 50px 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        .feature-icon {
            width: 90px;
            height: 90px;
            background: var(--accent);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.2rem;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }
        
        .feature h3 {
            font-size: 1.6rem;
            margin-bottom: 1.2rem;
        }
        
        .feature p {
            color: var(--light-text);
            font-size: 1.05rem;
            line-height: 1.8;
        }
        
        /* Main Content */
        .main {
            padding: 100px 0;
            background-color: var(--white);
        }
        
        .appointment-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        @media (max-width: 992px) {
            .appointment-container {
                grid-template-columns: 1fr;
            }
        }
        
        .appointment-info {
            background: var(--white);
            padding: 50px 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .appointment-info h2 {
            font-size: 2.4rem;
            margin-bottom: 2.5rem;
            position: relative;
            padding-bottom: 1.2rem;
        }
        
        .appointment-info h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--accent);
        }
        
        .service-item {
            padding: 2rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: var(--transition);
        }
        
        .service-item:hover {
            transform: translateX(8px);
        }
        
        .service-item h3 {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            color: var(--primary);
        }
        
        .service-item p {
            color: var(--light-text);
            line-height: 1.8;
            margin-bottom: 0.8rem;
        }
        
        .service-item .price {
            font-weight: 700;
            color: var(--accent);
            font-size: 1.3rem;
            margin: 15px 0;
        }
        
        .session-details {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-top: 1.2rem;
        }
        
        .detail-item {
            background-color: var(--secondary);
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.9rem;
            color: var(--light-text);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        /* Testimonials */
        .testimonials {
            margin-top: 4rem;
            padding-top: 3rem;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .testimonials h3 {
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }
        
        .testimonial-item {
            padding: 2rem;
            background-color: var(--secondary);
            border-left: 4px solid var(--accent);
            margin-bottom: 1.5rem;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            position: relative;
        }
        
        .testimonial-item::before {
            content: '"';
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 5rem;
            color: rgba(212, 175, 55, 0.1);
            font-family: 'Playfair Display', serif;
            line-height: 1;
        }
        
        .testimonial-item p {
            font-style: italic;
            color: var(--text);
            position: relative;
            z-index: 1;
            padding-left: 25px;
            line-height: 1.8;
        }
        
        .client-name {
            font-weight: 600;
            color: var(--primary);
            margin-top: 1.5rem;
            text-align: right;
            font-size: 1.05rem;
        }
        
        /* Appointment Form */
        .appointment-form {
            background: var(--white);
            padding: 50px 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            position: sticky;
            top: 30px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .appointment-form h2 {
            font-size: 2.4rem;
            margin-bottom: 2.5rem;
            position: relative;
            padding-bottom: 1.2rem;
        }
        
        .appointment-form h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--accent);
        }
        
        .form-group {
            margin-bottom: 1.8rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 500;
            color: var(--primary);
            font-size: 1rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: var(--border-radius);
            font-size: 1.05rem;
            transition: var(--transition);
            background-color: var(--white);
            font-family: 'Poppins', sans-serif;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
        
        .form-group textarea {
            min-height: 140px;
        }
        
        button[type="submit"] {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 16px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
        }
        
        button[type="submit"]:hover {
            background: var(--accent);
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-notes {
            background-color: var(--secondary);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            color: var(--light-text);
            margin-bottom: 1.8rem;
            border-left: 4px solid var(--accent);
            line-height: 1.7;
        }
        
        /* FAQ Section */
        .faq {
            padding: 100px 0;
            background: var(--white);
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .faq h2 {
            text-align: center;
            font-size: 2.8rem;
            margin-bottom: 4rem;
        }
        
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .faq-item {
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 2rem;
        }
        
        .faq-question {
            font-weight: 600;
            font-size: 1.4rem;
            margin-bottom: 1.2rem;
            cursor: pointer;
            position: relative;
            padding-right: 35px;
            color: var(--primary);
            transition: var(--transition);
        }
        
        .faq-question:hover {
            color: var(--accent);
        }
        
        .faq-question::after {
            content: '+';
            position: absolute;
            right: 0;
            top: 0;
            font-size: 1.8rem;
            transition: var(--transition);
        }
        
        .faq-question.active::after {
            content: '-';
        }
        
        .faq-answer {
            color: var(--light-text);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
            line-height: 1.8;
            font-size: 1.05rem;
        }
        
        .faq-question.active + .faq-answer {
            max-height: 500px;
        }
        
        /* Footer */
        footer {
            background: var(--primary);
            color: var(--white);
            padding: 80px 0 40px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
            margin-bottom: 3rem;
        }
        
        .footer-section h3 {
            color: var(--white);
            margin-bottom: 1.8rem;
            font-size: 1.4rem;
            position: relative;
            padding-bottom: 0.8rem;
        }
        
        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent);
        }
        
        .footer-section p {
            margin-bottom: 1.2rem;
            opacity: 0.8;
            font-size: 1rem;
            line-height: 1.7;
        }
        
        .social-links a {
            color: var(--white);
            margin-right: 1.2rem;
            font-size: 1.3rem;
            transition: var(--transition);
            opacity: 0.8;
        }
        
        .social-links a:hover {
            color: var(--accent);
            opacity: 1;
        }
        
        .copyright {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
            opacity: 0.7;
            font-size: 0.95rem;
        }
        
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
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }
            
            .hero {
                height: 70vh;
                min-height: 600px;
                margin-bottom: 60px;
            }
            
            .hero h1 {
                font-size: 2.8rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .section-title h2,
            .faq h2 {
                font-size: 2.2rem;
            }
            
            .features-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .feature {
                padding: 40px 30px;
            }
            
            .appointment-info, 
            .appointment-form {
                padding: 40px 30px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .service-item, .feature, .testimonial-item, .appointment-form {
            animation: fadeIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        
        .service-item:nth-child(1) { animation-delay: 0.1s; }
        .service-item:nth-child(2) { animation-delay: 0.2s; }
        .service-item:nth-child(3) { animation-delay: 0.3s; }
        .service-item:nth-child(4) { animation-delay: 0.4s; }
    </style>
	
</head>
<body>
	
    <?php require('../header.php')?>
    <section class="hero">
        <div class="hero-content">
            <h1>Premium Photography Experience With <?=$photographer['business_name']?></h1>
            <p>Book your exclusive session with our award-winning photographer</p>
        </div>
    </section>
    
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Us</h2>
            </div>
            <div class="features-container">
                <div class="feature">
                    <div class="feature-icon"><i class="fas fa-camera-retro"></i></div>
                    <h3>Professional Equipment</h3>
                    <p>We use only the highest quality cameras and lighting equipment to ensure your photos are stunning in every detail.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <h3>Fast Turnaround</h3>
                    <p>Receive your professionally edited photos within 7-14 days, with sneak peeks available within 48 hours.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3>Creative Vision</h3>
                    <p>Our photographer will work with you to create a personalized concept that perfectly captures your vision.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="main">
        <div class="container">
            <div class="appointment-container">
                <div class="appointment-info">
					<?php if($services){ ?>
                    <h2>Our Photography Services</h2>
                    <div class="service-type">
                        <?php foreach($services as $service): ?>
                            <div class="service-item">
                                <h3><?=$service['category']??""?></h3>
                                <p><?=$service['description']??""?></p>
                                <p class="price">$<?=$service['price']??""?></p>
                                <div class="session-details">
                                    <?php if(!empty($service['detail_1'])): ?>
                                        <span class="detail-item"><?=$service['detail_1']?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($service['detail_2'])): ?>
                                        <span class="detail-item"><?=$service['detail_2']?></span>
                                    <?php endif; ?>
                                    <?php if(!empty($service['detail_3'])): ?>
                                        <span class="detail-item"><?=$service['detail_3']?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php }?>
					<?php if($reviews){ ?>
                    <div class="testimonials">
                        <h3>Client Testimonials</h3>
                        <?php foreach($reviews as $row): ?>
                            <div class="testimonial-item">
                                <p><?=$row['comment']?></p>
                                <p class="client-name">— <?=$row['name']?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
					<?php }?>
                </div>
                
                <div class="appointment-form">
                    <h2>Book Your Session</h2>
                    <form action="submit_appointment.php" method="post">
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
								<option value="general">General</option>
                                <?php foreach($services as $row): ?>
                                <option value="<?=$row['category']??""?>"><?=$row['category']??""?></option>
                                <?php endforeach; ?>
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
                            <p>A 50% deposit is required to secure your booking date. Our team will contact you within 24 hours to confirm your appointment and process the deposit. Cancellations within 48 hours may incur a fee.</p>
                        </div>
                        <input type="hidden" value="<?=$photographer_id?>" name="photographer_id">
                        <button type="submit">Reserve Your Session</button>
                    </form>
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
                    <div class="faq-answer">We recommend solid colors that complement your skin tone. Avoid busy patterns and logos that can distract. For group photos, choose coordinating colors but avoid being too matchy. We'll provide a detailed style guide after booking to help you prepare.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">How long until I receive my photos?</div>
                    <div class="faq-answer">Portrait sessions are typically delivered within 7-14 days. Wedding and event galleries take 4-6 weeks due to the larger volume of images. You'll receive a sneak peek within 48 hours of your session to share with friends and family.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">What if the weather is bad for my outdoor shoot?</div>
                    <div class="faq-answer">We monitor weather closely and will contact you 24-48 hours in advance if rescheduling is needed. There's no fee for weather-related changes. We can also discuss moving to an indoor location if available.</div>
                </div>
            </div>
        </div>
    </section>
    
    <?php require('../footer.php');?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
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

</body>
</html>