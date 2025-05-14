<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'pdo.php';
if (isset($_SESSION['data'])) {
    $data = $_SESSION['data'];
}else{
	header('location: index.php');
}
$sql_photographer = $pdo->prepare('select * from business_profiles where id = ?');
$sql_photographer->execute([$data['id']]);
$photographer = $sql_photographer->fetch();
if(!$photographer){
	header('location: index.php');
}
$stmtToday = $pdo->prepare("
    SELECT COUNT(*) as count
FROM appointments 
WHERE photographer_id = ? 
  AND date >= CURDATE()
  AND date < CURDATE() + INTERVAL 1 DAY

");
$stmtToday->execute([$data['id']]);
$todayAppointments = $stmtToday->fetch(PDO::FETCH_ASSOC)['count'];

$currentMonth = date('Y-m');
$lastMonth = date('Y-m', strtotime('-1 month'));

$stmtCurrent = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE photographer_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?");
$stmtCurrent->execute([$data['id'], $currentMonth]);
$currentMonthCount = $stmtCurrent->fetchColumn();

$stmtLast = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE photographer_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?");
$stmtLast->execute([$data['id'], $lastMonth]);
$lastMonthCount = $stmtLast->fetchColumn();

$diff = $currentMonthCount - $lastMonthCount;
$trendIcon = $diff >= 0 ? 'up' : 'down';
$trendClass = $diff >= 0 ? 'trend-up' : 'trend-down';
$diffText = $diff != 0 ? abs($diff) . ' from last month' : 'No change';

$goal = 28;
$percent = min(round(($currentMonthCount / $goal) * 100), 100);

$stmtUpcoming = $pdo->prepare("
    SELECT category, date, status 
    FROM appointments 
    WHERE photographer_id = ? AND date >= CURDATE() 
    ORDER BY date ASC 
    LIMIT 5
");
$stmtUpcoming->execute([$data['id']]);
$upcomingAppointments = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

$photographer_views_chart = $pdo->prepare("
    SELECT DATE(view_date) AS view_day, COUNT(*) AS views
    FROM photographer_view_logs
    WHERE photographer_id = :pid AND view_date >= CURDATE() - INTERVAL 14 DAY
    GROUP BY view_day
    ORDER BY view_day
");
$photographer_views_chart->execute(['pid' => $data['id']]);


$rawData = [];
while ($row = $photographer_views_chart->fetch(PDO::FETCH_ASSOC)) {
    $rawData[$row['view_day']] = (int)$row['views'];
}

// Prepare chart data with all 15 days
$labels = [];
$data = [];
for ($i = 14; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('M j', strtotime($date)); // e.g. "May 14"
    $data[] = $rawData[$date] ?? 0;
}

// Convert to JS-friendly format
$labels_js = json_encode($labels);
$data_js = json_encode($data);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Dashboard</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!--CSS-->
    <link rel="stylesheet" href="css/header.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card .icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .stat-card h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card .trend {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .trend-up {
            color: #2ecc71;
        }

        .trend-down {
            color: var(--accent-color);
        }

        .quick-actions .btn {
            margin-bottom: 0.5rem;
            width: 100%;
        }

        .testimonial-card {
            border-left: 4px solid var(--primary-color);
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .popular-photo {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .popular-photo:hover {
            transform: scale(1.05);
        }

        .rating-stars {
            color: #f1c40f;
            font-size: 1.2rem;
        }

        .calendar-day {
            padding: 0.25rem;
            text-align: center;
            cursor: pointer;
        }

        .calendar-day.booked {
            background-color: rgba(52, 152, 219, 0.2);
            border-radius: 50%;
            color: var(--primary-color);
            font-weight: bold;
        }

        .calendar-day.today {
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            font-weight: bold;
        }

        .progress-thin {
            height: 6px;
        }

        .navbar-brand img {
            height: 40px;
        }

        @media (max-width: 768px) {
            .stat-card h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <?php require("header.php"); ?>

    <!-- Navigation Bar -->
    <div class="container py-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-6">
				<?php
					$sql = $pdo->prepare('select business_name,total_likes,total_views,total_rate,total_reviews from business_profiles where id = ?');
					$sql->execute([$data['id']]);
					$result = $sql->fetch();

				?>
                    <h1>Welcome back, <span class="fw-bold"></span><?=$result['business_name']?></h1>
                    <p class="mb-0">Here's what's happening with your photography business today</p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                        <!--  <div class="me-3 text-center">
                            <div class="fs-5 fw-bold">28°C</div>
                            <div class="small">Sunny</div>
                        </div> -->
                        <div class="text-center">
                            <div class="fs-5 fw-bold" id="current-date">June 15, 2023</div>
                            <div class="small" id="current-time">10:30 AM</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="icon"><i class="fas fa-eye"></i></div>
                            <h2 id="total-views"><?=$result['total_views']?></h2>
                            <div class="text-muted">Total Views</div>
                        </div>
                        <div class="trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 12%
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="icon"><i class="fas fa-calendar-check"></i></div>
                            <h2 id="today-appointments"><?=$todayAppointments?></h2>
                            <div class="text-muted">Today's Appointments</div>
                        </div>
                        <div class="trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 2
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="icon"><i class="fas fa-star"></i></div>
                            <h2><?=$result['total_rate']?></h2>
                            <div class="text-muted">Average Rating</div>
                        </div>
                        <div class="trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 0.2
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="icon"><i class="fas fa-heart"></i></div>
                            <h2 id="month-likes"><?=$result['total_likes']?></h2>
                            <div class="text-muted">Likes This Month</div>
                        </div>
                        <div class="trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 24%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Views Analytics -->
                <div class="stat-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><i class="fas fa-chart-line me-2"></i> Views Analytics</h3>
                        <div>
                            <select class="form-select form-select-sm" id="views-timeframe">
                                <option value="week">Last 7 Days</option>
                                <option value="month" selected>This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                    </div>
                    <canvas id="viewsChart" height="250"></canvas>
                </div>

                <!-- Appointments -->
                <div class="row">
                    
					
					
					
					<div class="col-md-6">
    <div class="stat-card mb-4 h-100">
        <h3><i class="fas fa-calendar-alt me-2"></i> Appointments</h3>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-0"><?= $currentMonthCount ?></h2>
                <small class="text-muted">This Month</small>
            </div>
            <div class="trend <?= $trendClass ?>">
                <i class="fas fa-arrow-<?= $trendIcon ?> me-1"></i> <?= $diffText ?>
            </div>
        </div>
        <div class="progress progress-thin mb-3">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percent ?>%"></div>
        </div>
        <p class="small text-muted mb-2"><?= $percent ?>% of your monthly goal (<?= $goal ?> appointments)</p>

        <h5 class="mt-4 mb-3">Upcoming Appointments</h5>
        <div class="list-group">
            <?php if ($upcomingAppointments): ?>
                <?php foreach ($upcomingAppointments as $appointment): ?>
                    <div class="list-group-item border-0 px-0 py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong><?= htmlspecialchars($appointment['category']) ?></strong>
                                <div class="text-muted small"><?= date('l, g:i A', strtotime($appointment['date'])) ?></div>
                            </div>
                            <span class="badge bg-primary"><?= htmlspecialchars($appointment['status']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted small">No upcoming appointments</p>
            <?php endif; ?>
        </div>
    </div>
	</div>
                    <div class="col-md-6">
                        <div class="stat-card mb-4 h-100">
                      
                            <div class="row g-0 text-center" id="mini-calendar" style = "display:none;">
                                <!-- Calendar days will be generated by JS -->
                            </div>

                            <h5 class="mt-4 mb-3">Appointment Types</h5>
                            <canvas id="appointmentsChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
			
            <!-- Right Column -->
            <div class="col-lg-4">
			<?php require('appointment/calender.php');?>
                <!-- Ratings & Reviews -->
				
                <div class="stat-card mb-4">
                    <h3><i class="fas fa-star me-2"></i> Ratings & Reviews</h3>
                    <div class="text-center py-3">
                        <div class="rating-stars mb-2">
						<?php
							$rating = isset($result['total_rate']) ? floatval($result['total_rate']) : 0;
							$fullStars = floor($result['total_rate']);
							$halfStar = ($result['total_rate'] - $fullStars) >= 0.5;
							$emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            for ($i = 0; $i < $fullStars; $i++):
							?>
                            <i class="fas fa-star"></i>
						  <?php endfor; ?>
						  <?php if ($halfStar): ?>
							<i class="fas fa-star-half-alt"></i>
						  <?php endif; ?>
						  <?php for ($i = 0; $i < $emptyStars; $i++): ?>
							<i class="far fa-star"></i>
						  <?php endfor; ?>
                        </div>
                        <h2><?=$result['total_rate']?> <small class="text-muted">/ 5.0</small></h2>
                        <p class="text-muted">Based on <?=$result['total_reviews']?> reviews</p>
                    </div>
<!------------------------------------------------------------------------------------------------------------->
					<?php
					$sql_reviews = $pdo->prepare('
						SELECT 
							reviews.id, reviews.user_id, reviews.photographer_id, reviews.rate, reviews.comment, 
							reviews.created_at, accounts.picture, accounts.username
						FROM reviews
						JOIN accounts ON accounts.id = reviews.user_id
						WHERE reviews.photographer_id = ? AND reviews.rate >= 2.0
						LIMIT 1
					');
					$sql_reviews->execute([$data['id']]);
					$reviews = $sql_reviews->fetchAll();
					?>
                    <?php foreach ($reviews as $review): ?>
						<div class="testimonial-card p-3 mb-3 bg-light">
							<div class="d-flex align-items-center mb-2">
								<img src="<?= htmlspecialchars($review['picture'] ?? 'https://via.placeholder.com/40') ?>" class="profile-img me-3" alt="Client">
								<div>
									<strong><?= htmlspecialchars($review['username']) ?></strong>
									<div class="rating-stars small">
										<?php
										$fullStars = floor($review['rate']);
										$halfStar = ($review['rate'] - $fullStars) >= 0.5 ? 1 : 0;
										$emptyStars = 5 - $fullStars - $halfStar;

										for ($i = 0; $i < $fullStars; $i++) {
											echo '<i class="fas fa-star text-warning"></i>';
										}
										if ($halfStar) {
											echo '<i class="fas fa-star-half-alt text-warning"></i>';
										}
										for ($i = 0; $i < $emptyStars; $i++) {
											echo '<i class="far fa-star text-muted"></i>';
										}
										?>
									</div>
								</div>
							</div>
							<p class="mb-0">"<?= htmlspecialchars($review['comment']) ?>"</p>
						</div>
					<?php endforeach; ?>
                    
					
					<?php
					
						$sql_rating_breakdown = $pdo->prepare("
							SELECT rate, COUNT(*) as count 
							FROM reviews 
							WHERE photographer_id = ? 
							GROUP BY rate
						");
						$sql_rating_breakdown->execute([$data['id']]);
						$ratings_data = $sql_rating_breakdown->fetchAll(PDO::FETCH_ASSOC);
						$rating_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
						$total_reviews = 0;
						foreach ($ratings_data as $row) {
							$rounded_rate = round($row['rate']);
							if (isset($rating_counts[$rounded_rate])) {
								$rating_counts[$rounded_rate] += $row['count'];
								$total_reviews += $row['count'];
							}
						}
						?>

					<?php
					foreach ([5, 4, 3, 2, 1] as $star) {
						$count = $rating_counts[$star];
						$percent = $total_reviews > 0 ? round(($count / $total_reviews) * 100) : 0;
						?>
						<div class="progress mb-1" style="height: 10px;">
							<div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percent ?>%"></div>
						</div>
						<div class="d-flex justify-content-between small text-muted mb-3">
							<span><?= $star ?> star<?= $star > 1 ? 's' : '' ?> (<?= $percent ?>%)</span>
							<span><?= $count ?> review<?= $count != 1 ? 's' : '' ?></span>
						</div>
					<?php } ?>


                    <a href="#" class="btn btn-outline-primary w-100 mt-2">View All Reviews</a>
                </div>
                <!-- Quick Actions -->
                <div class="stat-card">
                    <h3><i class="fas fa-bolt me-2"></i> Quick Actions</h3>
                    <div class="quick-actions">
                        <button class="btn btn-outline-primary mb-2">
                            <i class="fas fa-upload me-2"></i> Upload New Work
                        </button>
                        <button class="btn btn-outline-primary mb-2">
                            <i class="fas fa-envelope me-2"></i> Check Messages <span
                                class="badge bg-danger ms-1">3</span>
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i> View Recent Client
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Update current date and time
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', options);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        }

        // Initialize and update every second
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Generate mini calendar
        function generateMiniCalendar() {
            const calendar = document.getElementById('mini-calendar');
            calendar.innerHTML = '';

            // For demo purposes, we'll just show a static calendar for June 2023
            // In a real app, you would generate this dynamically based on the current month
            const daysInMonth = 30;
            const startingDay = 3; // June 1, 2023 is Thursday (0=Sun, 1=Mon, etc.)

            // Add empty cells for days before the 1st
            for (let i = 0; i < startingDay; i++) {
                calendar.innerHTML += '<div class="col calendar-day"></div>';
            }

            // Add days of the month
            const today = new Date();
            const currentDay = today.getDate();
            const currentMonth = today.getMonth(); // June is month 5

            for (let day = 1; day <= daysInMonth; day++) {
                const isBooked = [5, 8, 12, 15, 18, 22, 25, 28].includes(day); // Demo booked days
                const isToday = day === currentDay && currentMonth === 5; // June is month 5

                let dayClass = 'calendar-day';
                if (isBooked) dayClass += ' booked';
                if (isToday) dayClass += ' today';

                calendar.innerHTML += `<div class="col ${dayClass}">${day}</div>`;
            }
        }

        // Initialize charts
		
		
		
		
        function initCharts() {
            // Views Chart
            const viewsCtx = document.getElementById('viewsChart').getContext('2d');
            const viewsChart = new Chart(viewsCtx, {
                type: 'line',
                data: {
                    labels: ['Jun 1', 'Jun 2', 'Jun 3', 'Jun 4', 'Jun 5', 'Jun 6', 'Jun 7', 'Jun 8', 'Jun 9', 'Jun 10', 'Jun 11', 'Jun 12', 'Jun 13', 'Jun 14', 'Jun 15'],
                    datasets: [{
                        label: 'Daily Views',
                        data: [120, 190, 170, 220, 300, 280, 350, 320, 400, 380, 420, 450, 500, 480, 520],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Appointments Chart
            const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
            const appointmentsChart = new Chart(appointmentsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Portrait', 'Wedding', 'Commercial', 'Event', 'Other'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#3498db',
                            '#e74c3c',
                            '#2ecc71',
                            '#f39c12',
                            '#9b59b6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '70%'
                }
            });

            // Update chart when timeframe changes
            document.getElementById('views-timeframe').addEventListener('change', function () {
                const timeframe = this.value;
                let labels, data;

                if (timeframe === 'week') {
                    labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    data = [320, 400, 380, 420, 450, 500, 480];
                } else if (timeframe === 'month') {
                    labels = ['Jun 1', 'Jun 2', 'Jun 3', 'Jun 4', 'Jun 5', 'Jun 6', 'Jun 7', 'Jun 8', 'Jun 9', 'Jun 10', 'Jun 11', 'Jun 12', 'Jun 13', 'Jun 14', 'Jun 15'];
                    data = [120, 190, 170, 220, 300, 280, 350, 320, 400, 380, 420, 450, 500, 480, 520];
                } else { // year
                    labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    data = [3200, 2900, 3500, 4200, 4800, 5200, 5500, 5800, 5400, 6000, 6500, 7000];
                }

                viewsChart.data.labels = labels;
                viewsChart.data.datasets[0].data = data;
                viewsChart.update();
            });
        }

        // Animate counting numbers
        function animateValue(id, start, end, duration) {
            const obj = document.getElementById(id);
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', function () {
            generateMiniCalendar();
            initCharts();

            // Animate some numbers for demo purposes
            animateValue('total-views', 0, <?=$result['total_views']?>, 2000);
            animateValue('today-appointments', 0, <?=intval($todayAppointments)?>, 1000);
            animateValue('month-likes', 0, <?=$result['total_likes']?>, 1500);
        });
    </script>
</body>

</html>