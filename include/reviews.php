<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require('../pdo.php');
$photographerId = $_SESSION['data']['id'];
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
$stmt = $pdo->prepare("
    SELECT r.*, u.name AS user_name, p.business_name AS photographer_name
    FROM reviews r
    JOIN user_profiles u ON r.user_id = u.id
    JOIN business_profiles p ON r.photographer_id = p.id
    WHERE r.photographer_id = :photographer_id
    ORDER BY r.created_at DESC
");
$stmt->execute(['photographer_id' => $photographerId]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$totalRating = 0;
$reviewCount = count($reviews);
foreach ($reviews as $review) {
    $totalRating += $review['rate'];
}
$averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | <?=$_SESSION['data']['username']?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f9f9f9;
            --dark-bg: #f0f0f0;
            --text-color: #333;
            --light-text: #666;
            --border-color: #ddd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background-color: var(--secondary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background-color: var(--dark-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .stat-card {
            text-align: center;
        }
        
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
        }
        
        .stat-card .label {
            font-size: 14px;
            color: var(--light-text);
        }
        
        .reviews-container {
            padding: 20px;
        }
        
        .review {
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
            transition: transform 0.2s ease;
        }
        
        .review:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .reviewer {
            font-weight: bold;
            color: var(--secondary-color);
        }
        
        .date {
            font-size: 13px;
            color: var(--light-text);
        }
        
        .rating {
            margin-bottom: 10px;
        }
        
        .star {
            color: #f1c40f;
            font-size: 18px;
        }
        
        .empty-star {
            color: #ddd;
            font-size: 18px;
        }
        
        .comment {
            background-color: var(--light-bg);
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 10px;
        }
        
        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: var(--dark-bg);
            border-radius: 4px;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
        }
        
        .filter-label {
            margin-right: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        
        select {
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: white;
        }
        
        .no-reviews {
            text-align: center;
            padding: 40px 0;
            color: var(--light-text);
        }
        
        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
                gap: 15px;
            }
            
            .review-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .date {
                margin-top: 5px;
            }
            
            .filters {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/header.css">
</head>
<body>
<?php require('../header.php');?>
    <div class="container">
        <div class="header">
            <h1>Reviews for <?=$_SESSION['data']['username']?></h1>
            <p>See what clients are saying about your photography services</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="value"><?=$reviewCount?></div>
                <div class="label">Total Reviews</div>
            </div>
            <div class="stat-card">
                <div class="value"><?=number_format($averageRating, 1)?></div>
                <div class="label">Average Rating</div>
            </div>
        </div>
        
        <div class="filters">
            <div class="filter-group">
                <span class="filter-label">Sort by:</span>
                <select id="sort-select">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="highest">Highest Rating</option>
                    <option value="lowest">Lowest Rating</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Filter:</span>
                <select id="filter-select">
                    <option value="all">All Reviews</option>
                    <option value="5">5 Star Only</option>
                    <option value="4">4 Star Only</option>
                    <option value="3">3 Star Only</option>
                    <option value="2">2 Star Only</option>
                    <option value="1">1 Star Only</option>
                </select>
            </div>
        </div>
        
        <div class="reviews-container">
            <?php if ($reviewCount > 0): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review" data-rating="<?=$review['rate']?>">
                        <div class="review-header">
                            <div class="reviewer"><?= htmlspecialchars($review['user_name']) ?></div>
                            <div class="date"><?= date('F j, Y', strtotime($review['created_at'])) ?></div>
                        </div>
                        <div class="rating">
                            <?php 
                            // Output stars based on rating
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $review['rate']) {
                                    echo '<i class="fas fa-star star"></i>';
                                } else {
                                    echo '<i class="far fa-star empty-star"></i>';
                                }
                            }
                            ?>
                            <span>(<?= number_format($review['rate'], 1) ?>)</span>
                        </div>
                        <div class="comment"><?= nl2br(htmlspecialchars($review['comment'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-reviews">
                    <i class="fas fa-comment-slash" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <p>No reviews yet. Reviews will appear here once clients leave feedback.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple filtering and sorting functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sortSelect = document.getElementById('sort-select');
            const filterSelect = document.getElementById('filter-select');
            const reviewsContainer = document.querySelector('.reviews-container');
            const reviews = Array.from(document.querySelectorAll('.review'));
            
            function applyFiltersAndSort() {
                // Get current reviews to work with
                let filteredReviews = Array.from(reviews);
                
                // Apply filter
                const filterValue = filterSelect.value;
                if (filterValue !== 'all') {
                    filteredReviews = filteredReviews.filter(review => {
                        return Math.round(parseFloat(review.dataset.rating)) === parseInt(filterValue);
                    });
                }
                
                // Apply sort
                const sortValue = sortSelect.value;
                filteredReviews.sort((a, b) => {
                    const dateA = new Date(a.querySelector('.date').textContent);
                    const dateB = new Date(b.querySelector('.date').textContent);
                    const ratingA = parseFloat(a.dataset.rating);
                    const ratingB = parseFloat(b.dataset.rating);
                    
                    switch (sortValue) {
                        case 'newest':
                            return dateB - dateA;
                        case 'oldest':
                            return dateA - dateB;
                        case 'highest':
                            return ratingB - ratingA;
                        case 'lowest':
                            return ratingA - ratingB;
                        default:
                            return 0;
                    }
                });
                
                // Clear container and append sorted/filtered reviews
                reviews.forEach(review => review.remove());
                filteredReviews.forEach(review => reviewsContainer.appendChild(review));
                
                // Remove any existing "no reviews" message first
                const existingNoReviews = reviewsContainer.querySelector('.no-reviews');
                if (existingNoReviews) {
                    existingNoReviews.remove();
                }
                
                // Show "no reviews" message if needed
                if (filteredReviews.length === 0) {
                    const noReviews = document.createElement('div');
                    noReviews.className = 'no-reviews';
                    noReviews.innerHTML = `
                        <i class="fas fa-filter" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>No reviews match your current filter.</p>
                    `;
                    reviewsContainer.appendChild(noReviews);
                }
            }
            
            // Add event listeners
            sortSelect.addEventListener('change', applyFiltersAndSort);
            filterSelect.addEventListener('change', applyFiltersAndSort);
        });
    </script>
</body>
</html>