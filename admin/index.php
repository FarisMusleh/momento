<?php 
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
	if(!isset($_SESSION['admin']) && $_SESSION['admin']==True){
		header("location: ../index.php");
	}
	include 'db_connect.php';
	
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --dark-color: #2b2d42;
            --light-color: #f8f9fa;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #f44336;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            overflow-x: hidden;
        }
        
        .container-fluid {
            padding: 0;
        }
        
        /* Sidebar styling */
        .bg-dark {
            background-color: var(--dark-color) !important;
            min-height: 100vh;
            padding: 0;
        }
        
        /* Main content area */
        .col-md-9 {
            padding: 2rem;
            background-color: var(--light-color);
        }
        
        h2 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* Card styling */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
        }
        
        .card-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        /* Button styling */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 0.375rem 1rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        /* Grid spacing */
        .row.g-4 {
            margin-bottom: 1.5rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-md-9 {
                padding: 1rem;
            }
            
            .card-text {
                font-size: 1.5rem;
            }
        }
        
        /* Animation for cards */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .col-md-4 {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .col-md-4:nth-child(1) { animation-delay: 0.1s; }
        .col-md-4:nth-child(2) { animation-delay: 0.2s; }
        .col-md-4:nth-child(3) { animation-delay: 0.3s; }
        .col-md-4:nth-child(4) { animation-delay: 0.4s; }
        .col-md-4:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
<div class="container-fluid">
 
    <div class="row">
        <div class="col-md-2">
           <?php include 'sidebar.php'; ?>
        </div>
        <div class="col-md-9 p-4">
            <h2>Dashboard Overview</h2>
            <div class="row g-4 mt-4">
                <?php
                $tables = ['accounts', 'images', 'appointments', 'comments', 'reviews'];
                foreach ($tables as $table) {
                    $res = $conn->query("SELECT COUNT(*) AS count FROM `$table`");
                    $count = $res->fetch_assoc()['count'];
                    echo "<div class='col-md-4'>
                            <div class='card shadow-sm'>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . ucfirst(str_replace('_', ' ', $table)) . "</h5>
                                    <p class='card-text'>Total: $count</p>
                                    <a href='manage.php?table=$table' class='btn btn-sm btn-primary'>Manage</a>
                                </div>
                            </div>
                        </div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>