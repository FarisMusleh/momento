<?php
require('../pdo.php');

session_start();

if (!isset($_SESSION['data']['id'])) {
    header('Location: ../index.php');
    exit;
}
$user_id = $_SESSION['data']['id'];

$updateSeen = "UPDATE appointments SET seen = 1 WHERE photographer_id = :photographer_id AND seen = 0";
$stmt = $pdo->prepare($updateSeen);
$stmt->bindParam(':photographer_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$sql_account_type = $pdo->prepare('select account_type from accounts where id = ?');
$sql_account_type->execute([$user_id]);
$type = $sql_account_type->fetch();
if($type['account_type']!='business'){
    header('Location: ../index.php');
}

$viewing_details = false;
$appointment_detail = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $viewing_details = true;
    $appointment_id = (int)$_GET['view'];
    
    // Fetch the specific appointment
    try {
        $query = "SELECT * FROM appointments WHERE id = $appointment_id AND photographer_id = $user_id";
        $result = $pdo->query($query);
        $appointment_detail = $result->fetch(PDO::FETCH_ASSOC);
        if (!$appointment_detail) {
            $viewing_details = false;
        }
    } catch (PDOException $e) {
        $viewing_details = false;
    }
    
    // Process status update if form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_status = $_POST['status'];
        $valid_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        
        if (in_array($new_status, $valid_statuses)) {
            try {
                $query = "UPDATE appointments SET status = '$new_status' WHERE id = $appointment_id AND photographer_id = $user_id";
                $pdo->query($query);
                
                // Refresh appointment details
                $query = "SELECT * FROM appointments WHERE id = $appointment_id AND photographer_id = $user_id";
                $result = $pdo->query($query);
                $appointment_detail = $result->fetch(PDO::FETCH_ASSOC);
                
                $status_message = "Status updated successfully.";
            } catch (PDOException $e) {
                $status_message = "Failed to update status.";
            }
        }
    }
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$status_options = ['pending', 'cancelled', 'confirmed', 'completed'];

$query = "SELECT * FROM appointments WHERE photographer_id = $user_id";
if (in_array($status_filter, $status_options)) {
    $query .= " AND status = '$status_filter'";
}
$query .= " ORDER BY date DESC";

try {
    $result = $pdo->query($query);
    $appointments = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

$status_counts = [];
try {
    $count_query = "SELECT status, COUNT(*) as count FROM appointments WHERE photographer_id = $user_id GROUP BY status";
    $count_result = $pdo->query($count_query);
    $status_counts_results = $count_result->fetchAll(PDO::FETCH_ASSOC);
    
    // Initialize all status counts to 0
    foreach ($status_options as $option) {
        $status_counts[$option] = 0;
    }
    
    // Update with actual counts
    foreach ($status_counts_results as $count) {
        $status_counts[$count['status']] = $count['count'];
    }
} catch (PDOException $e) {
    die("Count query failed: " . $e->getMessage());
}

// Helper function to format date
function formatDateTime($date) {
    return date('F j, Y \a\t g:i A', strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments & Services | Photography Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/header.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
     <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
    /* Base Styles */
    :root {
        --primary: #4a6fa5;
        --primary-dark: #3a5a8a;
        --secondary: #333333;
        --light: #f8f9fa;
        --danger: #dc3545;
        --success: #28a745;
        --warning: #ffc107;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --border-radius: 0.375rem;
        --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        --transition: all 0.3s ease;
    }
    
    /* Professional Theme 1: Corporate Blue */
    .theme-corporate {
        --primary: #2c3e50;
        --primary-dark: #1a252f;
        --secondary: #34495e;
        --light: #ecf0f1;
        --danger: #e74c3c;
        --success: #27ae60;
        --warning: #f39c12;
        --gray: #7f8c8d;
        --light-gray: #bdc3c7;
    }
    
    /* Professional Theme 2: Modern Teal */
    .theme-modern {
        --primary: #009688;
        --primary-dark: #00796b;
        --secondary: #455a64;
        --light: #f5f5f5;
        --danger: #f44336;
        --success: #4caf50;
        --warning: #ff9800;
        --gray: #607d8b;
        --light-gray: #cfd8dc;
    }
    
    /* Professional Theme 3: Elegant Purple */
    .theme-elegant {
        --primary: #673ab7;
        --primary-dark: #5e35b1;
        --secondary: #4527a0;
        --light: #f3e5f5;
        --danger: #e91e63;
        --success: #00bcd4;
        --warning: #ff5722;
        --gray: #9e9e9e;
        --light-gray: #e1bee7;
    }
    
    /* Professional Theme 4: Minimal Gray */
    .theme-minimal {
        --primary: #607d8b;
        --primary-dark: #455a64;
        --secondary: #37474f;
        --light: #eceff1;
        --danger: #d32f2f;
        --success: #388e3c;
        --warning: #ffa000;
        --gray: #90a4ae;
        --light-gray: #cfd8dc;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    body {
        background-color: var(--light);
        color: var(--secondary);
        line-height: 1.6;
        font-size: 1rem;
        transition: var(--transition);
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--light-gray);
    }
    
    h1, h2, h3 {
        color: var(--secondary);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    h1 {
        font-size: 2rem;
        font-weight: 300;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .btn {
        padding: 0.625rem 1.25rem;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        text-decoration: none;
        font-size: 0.875rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .btn-danger {
        background-color: var(--danger);
    }
    
    .btn-danger:hover {
        filter: brightness(90%);
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }
    
    .card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: var(--transition);
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--secondary);
        font-size: 0.875rem;
    }
    
    input, textarea, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        background-color: white;
        color: var(--secondary);
    }
    
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(74, 111, 165, 0.25);
    }
    
    .form-row {
        display: flex;
        gap: 1.25rem;
    }
    
    .form-row .form-group {
        flex: 1;
    }
    
    /* Main Navigation */
    .main-nav {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--light-gray);
        padding-bottom: 1rem;
    }
    
    .nav-tab {
        padding: 0.5rem 1rem;
        text-decoration: none;
        color: var(--gray);
        font-weight: 500;
        border-radius: var(--border-radius);
        transition: var(--transition);
    }
    
    .nav-tab:hover, .nav-tab.active {
        color: var(--primary);
        background-color: rgba(74, 111, 165, 0.1);
    }
    
    /* Filter Navigation */
    .filter-nav {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .filter-button {
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        background-color: white;
        color: var(--gray);
        text-decoration: none;
        font-size: 0.875rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid var(--light-gray);
    }
    
    .filter-button:hover, .filter-button.active {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .filter-button .count {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.15rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
    }
    
    .filter-button:hover .count, .filter-button.active .count {
        background-color: rgba(255, 255, 255, 0.3);
    }
    
    /* Appointment List */
    .appointment-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .appointment-card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        border: 1px solid rgba(0, 0, 0, 0.05);
        cursor: pointer;
    }
    
    .appointment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    
    .appointment-header {
        padding: 1rem;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .appointment-name {
        font-weight: 600;
        color: var(--secondary);
    }
    
    .appointment-status {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
    }
    
    .status-pending {
        background-color: rgba(var(--warning), 0.1);
        color: var(--warning);
    }
    
    .status-confirmed {
        background-color: rgba(var(--success), 0.1);
        color: var(--success);
    }
    
    .status-cancelled {
        background-color: rgba(var(--danger), 0.1);
        color: var(--danger);
    }
    
    .status-completed {
        background-color: rgba(var(--primary), 0.1);
        color: var(--primary);
    }
    
    .appointment-body {
        padding: 1rem;
    }
    
    .appointment-detail {
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .appointment-footer {
        padding: 0.75rem 1rem;
        background-color: var(--light);
        color: var(--gray);
        font-size: 0.75rem;
        text-align: center;
    }
    
    /* Detail View */
    .detail-view {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
    }
    
    .detail-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .detail-title {
        margin: 0;
    }
    
    .detail-back {
        color: var(--primary);
        text-decoration: none;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .detail-content {
        padding: 1.5rem;
    }
    
    .detail-info {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .detail-item {
        margin-bottom: 1rem;
    }
    
    .detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--gray);
        margin-bottom: 0.25rem;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        font-size: 1rem;
        color: var(--secondary);
    }
    
    .notes-section {
        grid-column: 1 / -1;
        margin-top: 1rem;
    }
    
    .notes-content {
        background-color: var(--light);
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-top: 0.5rem;
    }
    
    /* Status Form */
    .status-form {
        background-color: var(--light);
        padding: 1.5rem;
        border-radius: var(--border-radius);
        margin-top: 2rem;
    }
    
    .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-button {
        padding: 0.75rem 1.5rem;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-size: 1rem;
        transition: var(--transition);
    }
    
    .form-button:hover {
        background-color: var(--primary-dark);
    }
    
    .status-message {
        margin-top: 1rem;
        padding: 0.75rem;
        border-radius: var(--border-radius);
        background-color: rgba(var(--success), 0.1);
        color: var(--success);
        font-size: 0.875rem;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--gray);
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        color: var(--primary);
    }
    
    .empty-state h3 {
        margin-bottom: 0.5rem;
        color: var(--secondary);
    }
    
    .empty-state p {
        margin-bottom: 1.5rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Theme Selector */
    .theme-selector {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        z-index: 100;
    }
    
    .theme-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid white;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .theme-btn.corporate { background-color: #2c3e50; }
    .theme-btn.modern { background-color: #009688; }
    .theme-btn.elegant { background-color: #673ab7; }
    .theme-btn.minimal { background-color: #607d8b; }
    
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .filter-nav {
            flex-direction: column;
        }
        
        .appointment-list {
            grid-template-columns: 1fr;
        }
        
        .detail-info {
            grid-template-columns: 1fr;
        }
        
        .theme-selector {
            flex-direction: row;
            bottom: 10px;
            right: 10px;
        }
    }
    </style>
</head>
<body class="theme-corporate">
    <?php require('../header.php'); ?>
    
    <div class="container">
        <!-- Main Navigation -->
        <div class="main-nav">
            <a href="appointments_inbox.php" class="nav-tab <?php echo basename($_SERVER['PHP_SELF']) == 'appointments_inbox.php' ? 'active' : ''; ?>">Appointments</a>
            <a href="services.php" class="nav-tab <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">Services</a>
        </div>
        
        <!-- Appointments Section -->
        <div class="section appointments-section active" id="appointments-section">
            <?php if ($viewing_details && $appointment_detail): ?>
                <!-- Detail View -->
                <div class="detail-view">
                    <div class="detail-header">
                        <h2 class="detail-title">Appointment Details</h2>
                        <a href="appointments_inbox.php<?php echo $status_filter ? '?status=' . $status_filter : ''; ?>" class="detail-back">
                            <i class="fas fa-arrow-left"></i> Back to Appointments
                        </a>
                    </div>
                    
                    <div class="detail-content">
                        <div class="detail-info">
                            <div class="detail-item">
                                <div class="detail-label">Client Name</div>
                                <div class="detail-value"><?php echo htmlspecialchars($appointment_detail['full_name']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Status</div>
                                <div class="detail-value status-<?php echo $appointment_detail['status']; ?>">
                                    <?php echo ucfirst($appointment_detail['status']); ?>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Email</div>
                                <div class="detail-value"><?php echo htmlspecialchars($appointment_detail['email']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value"><?php echo htmlspecialchars($appointment_detail['phone_number']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Date & Time</div>
                                <div class="detail-value"><?php echo formatDateTime($appointment_detail['date']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Duration</div>
                                <div class="detail-value"><?php echo $appointment_detail['duration_minutes']; ?> minutes</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Category</div>
                                <div class="detail-value"><?php echo htmlspecialchars($appointment_detail['category']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Location</div>
                                <div class="detail-value"><?php echo htmlspecialchars($appointment_detail['location']); ?></div>
                            </div>
                            
                            <?php if (!empty($appointment_detail['notes'])): ?>
                            <div class="notes-section">
                                <div class="detail-label">Notes</div>
                                <div class="notes-content"><?php echo nl2br(htmlspecialchars($appointment_detail['notes'])); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($appointment_detail['status'] === 'pending'): ?>
                            <div class="status-form">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="status" class="detail-label">Update Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="pending" selected>Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_status" class="form-button">Update Status</button>

                                    <?php if (isset($status_message)): ?>
                                    <div class="status-message"><?php echo $status_message; ?></div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Appointment List View -->
                <h1>Appointment Bookings</h1>
                
                <!-- Filter Navigation -->
                <div class="filter-nav">
                    <a href="appointments_inbox.php" class="filter-button <?php echo $status_filter === '' ? 'active' : ''; ?>">
                        All <span class="count"><?php echo array_sum($status_counts); ?></span>
                    </a>
                    <a href="?status=pending" class="filter-button <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">
                        Pending <span class="count"><?php echo $status_counts['pending']; ?></span>
                    </a>
                    <a href="?status=confirmed" class="filter-button <?php echo $status_filter === 'confirmed' ? 'active' : ''; ?>">
                        Confirmed <span class="count"><?php echo $status_counts['confirmed']; ?></span>
                    </a>
                    <a href="?status=cancelled" class="filter-button <?php echo $status_filter === 'cancelled' ? 'active' : ''; ?>">
                        Cancelled <span class="count"><?php echo $status_counts['cancelled']; ?></span>
                    </a>
                    <a href="?status=completed" class="filter-button <?php echo $status_filter === 'completed' ? 'active' : ''; ?>">
                        Completed <span class="count"><?php echo $status_counts['completed']; ?></span>
                    </a>
                </div>
                
                <!-- Appointments List -->
                <?php if (count($appointments) > 0): ?>
                    <div class="appointment-list">
                        <?php foreach ($appointments as $appointment): ?>
                            <div class="appointment-card" onclick="window.location='appointments_inbox.php?view=<?php echo $appointment['id']; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?>'">
                                <div class="appointment-header">
                                    <div class="appointment-name"><?php echo htmlspecialchars($appointment['full_name']); ?></div>
                                    <div class="appointment-status status-<?php echo $appointment['status']; ?>">
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </div>
                                </div>
                                <div class="appointment-body">
                                    <div class="appointment-detail">
                                        <strong>Date:</strong> <?php echo date('M j, Y', strtotime($appointment['date'])); ?>
                                    </div>
                                    <div class="appointment-detail">
                                        <strong>Time:</strong> <?php echo date('g:i A', strtotime($appointment['date'])); ?>
                                    </div>
                                    <div class="appointment-detail">
                                        <strong>Category:</strong> <?php echo htmlspecialchars($appointment['category']); ?>
                                    </div>
                                </div>
                                <div class="appointment-footer">
                                    Click for details
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="far fa-calendar"></i>
                        <h3>No appointments found</h3>
                        <p>There are no appointments<?php echo $status_filter ? ' with status: ' . ucfirst($status_filter) : ''; ?>.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Theme Selector -->
    <div class="theme-selector">
        <div class="theme-btn corporate" data-theme="corporate" title="Corporate Blue"></div>
        <div class="theme-btn modern" data-theme="modern" title="Modern Teal"></div>
        <div class="theme-btn elegant" data-theme="elegant" title="Elegant Purple"></div>
        <div class="theme-btn minimal" data-theme="minimal" title="Minimal Gray"></div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme switcher
            const themeButtons = document.querySelectorAll('.theme-btn');
            themeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const theme = this.getAttribute('data-theme');
                    document.body.className = `theme-${theme}`;
                    
                    // Save theme preference to localStorage
                    localStorage.setItem('selectedTheme', theme);
                });
            });
            
            // Load saved theme
            const savedTheme = localStorage.getItem('selectedTheme');
            if (savedTheme) {
                document.body.className = `theme-${savedTheme}`;
            }
        });
    </script>
</body>
</html>