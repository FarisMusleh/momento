<?php
require('../pdo.php');

// Start session for user authentication
session_start();


if (!isset($_SESSION['data']['id'])) {
    header('Location: ../index.php');
    exit;
}
$user_id = $_SESSION['data']['id'];

// Get appointment ID if viewing details
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

// Determine if we're filtering by status
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$status_options = ['pending', 'cancelled', 'confirmed', 'completed'];

// Build the query based on filter
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

// Count appointments by status
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
	<link rel="stylesheet" href="appointment.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Photography Studio</div>
            </div>
        </div>
    </header>
    
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
                        
                        <div class="status-form">
                            <form method="get">
                                <div class="form-group">
                                    <label for="status" class="form-label">Update Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="pending" <?php echo $appointment_detail['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $appointment_detail['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="cancelled" <?php echo $appointment_detail['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        <option value="completed" <?php echo $appointment_detail['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="form-button">Update Status</button>
                                
                                <?php if (isset($status_message)): ?>
                                <div class="status-message"><?php echo $status_message; ?></div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Appointment List View -->
                <h1 style="font-weight: 300; margin-bottom: 30px;">Appointment Bookings</h1>
                
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
</body>
</html>