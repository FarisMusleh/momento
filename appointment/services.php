<?php
session_start();
require '../pdo.php';

if(isset($_SESSION['data'])){
	$user_id = $_SESSION['data']['id'] ?? 0;
	}else{
		header('Location: ../index.php');
	}

$sql_account_type = $pdo->prepare('select account_type from accounts where id = ?');
$sql_account_type->execute([$user_id]);
$type = $sql_account_type->fetch();
if($type['account_type']!='business'){
	header('Location: ../index.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $detail_1 = $_POST['detail_1'];
    $detail_2 = $_POST['detail_2'];
    $detail_3 = $_POST['detail_3'];

    if ($id > 0) {
        // Update
        $stmt = $pdo->prepare("UPDATE appointment_services SET category=?, description=?, price=?, detail_1=?, detail_2=?, detail_3=? WHERE id=? AND photographer_id=?");
        $stmt->execute([$category, $description, $price, $detail_1, $detail_2, $detail_3, $id, $user_id]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO appointment_services (photographer_id, category, description, price, detail_1, detail_2, detail_3, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $category, $description, $price, $detail_1, $detail_2, $detail_3]);
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Delete
if (isset($_GET['delete_service'])) {
    $sid = intval($_GET['delete_service']);
    $stmt = $pdo->prepare("DELETE FROM appointment_services WHERE id=? AND photographer_id=?");
    $stmt->execute([$sid, $user_id]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Edit
$edit = null;
if (isset($_GET['edit_service'])) {
    $sid = intval($_GET['edit_service']);
    $stmt = $pdo->prepare("SELECT * FROM appointment_services WHERE id=? AND photographer_id=?");
    $stmt->execute([$sid, $user_id]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Photography Services</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/header.css">
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
        --gray: #90a4ae;
        --light-gray: #cfd8dc;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
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
    
    .btn-theme {
        background-color: var(--gray);
        margin-left: 0.5rem;
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
    
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .service-card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    
    .service-header {
        background-color: var(--primary);
        color: white;
        padding: 1rem 1.25rem;
        font-size: 1.125rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .price-tag {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .service-body {
        padding: 1.25rem;
    }
    
    .service-description {
        margin-bottom: 1rem;
        color: var(--gray);
        font-size: 0.9375rem;
        line-height: 1.5;
    }
    
    .service-details {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    
    .service-details li {
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        align-items: center;
        font-size: 0.875rem;
    }
    
    .service-details li:last-child {
        border-bottom: none;
    }
    
    .service-details i {
        color: var(--primary);
        margin-right: 0.75rem;
        font-size: 0.875rem;
    }
    
    .service-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.625rem;
        margin-top: 1rem;
    }
    
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
        
        .form-row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .services-grid {
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
        <div class="main-nav">
            <a href="appointments_inbox.php" class="nav-tab <?php echo basename($_SERVER['PHP_SELF']) == 'appointments_inbox.php' ? 'active' : ''; ?>">Appointments</a>
            <a href="services.php" class="nav-tab <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">Services</a>
             <div class="justify-content-end" style="margin-left: auto;">
                <button id="addServiceBtn" class="btn">
                    <i class="fas fa-plus"></i> Add New Service
                </button>
            </div>
        </div>
        
        <!-- Form Card (Initially Hidden if not editing) -->
        <div class="card" id="serviceForm" style="display: <?php echo $edit ? 'block' : 'none'; ?>">
            <h2><?php echo $edit ? 'Edit' : 'Add New'; ?> Service</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : 0; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Service Category</label>
                        <select name="category" id="category" required>
                            <option value="">Select a category</option>
                            <option value="Portrait" <?php echo ($edit && $edit['category'] == 'Portrait') ? 'selected' : ''; ?>>Portrait</option>
                            <option value="Wedding" <?php echo ($edit && $edit['category'] == 'Wedding') ? 'selected' : ''; ?>>Wedding</option>
                            <option value="Event" <?php echo ($edit && $edit['category'] == 'Event') ? 'selected' : ''; ?>>Event</option>
                            <option value="Commercial" <?php echo ($edit && $edit['category'] == 'Commercial') ? 'selected' : ''; ?>>Commercial</option>
                            <option value="Family" <?php echo ($edit && $edit['category'] == 'Family') ? 'selected' : ''; ?>>Family</option>
                            <option value="Other" <?php echo ($edit && $edit['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Price ($)</label>
                        <input type="number" step="0.01" name="price" id="price" required value="<?php echo $edit ? $edit['price'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="3" required><?php echo $edit ? $edit['description'] : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="detail_1">Service Detail 1</label>
                    <input type="text" name="detail_1" id="detail_1" value="<?php echo $edit ? $edit['detail_1'] : ''; ?>" placeholder="e.g. 2 hour session">
                </div>
                
                <div class="form-group">
                    <label for="detail_2">Service Detail 2</label>
                    <input type="text" name="detail_2" id="detail_2" value="<?php echo $edit ? $edit['detail_2'] : ''; ?>" placeholder="e.g. 20 edited photos">
                </div>
                
                <div class="form-group">
                    <label for="detail_3">Service Detail 3</label>
                    <input type="text" name="detail_3" id="detail_3" value="<?php echo $edit ? $edit['detail_3'] : ''; ?>" placeholder="e.g. Online gallery included">
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-danger" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> <?php echo $edit ? 'Update' : 'Save'; ?> Service
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Services List -->
        <div class="services-container">
            <h2>Your Services</h2>
            
            <?php
            // Fetch services
            $stmt = $pdo->prepare("SELECT * FROM appointment_services WHERE photographer_id = ? ORDER BY category, created_at DESC");
            $stmt->execute([$user_id]);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($services) > 0): 
            ?>
                <div class="services-grid">
                    <?php foreach ($services as $service): ?>
                        <div class="service-card">
                            <div class="service-header">
                                <?php echo htmlspecialchars($service['category']); ?>
                                <span class="price-tag">$<?php echo number_format($service['price'], 2); ?></span>
                            </div>
                            <div class="service-body">
                                <div class="service-description">
                                    <?php echo htmlspecialchars($service['description']); ?>
                                </div>
                                
                                <ul class="service-details">
                                    <?php if (!empty($service['detail_1'])): ?>
                                        <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($service['detail_1']); ?></li>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($service['detail_2'])): ?>
                                        <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($service['detail_2']); ?></li>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($service['detail_3'])): ?>
                                        <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($service['detail_3']); ?></li>
                                    <?php endif; ?>
                                </ul>
                                
                                <div class="service-actions">
                                    <a href="?edit_service=<?php echo $service['id']; ?>" class="btn btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?delete_service=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this service?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-camera"></i>
                    <h3>No services added yet</h3>
                    <p>Start adding your photography services to display them to potential clients.</p>
                    <button class="btn" id="emptyAddBtn" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Add Your First Service
                    </button>
                </div>
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
        // Show/hide form functionality
        document.addEventListener('DOMContentLoaded', function() {
            const addServiceBtn = document.getElementById('addServiceBtn');
            const emptyAddBtn = document.getElementById('emptyAddBtn');
            const serviceForm = document.getElementById('serviceForm');
            const cancelBtn = document.getElementById('cancelBtn');
            
            if (addServiceBtn) {
                addServiceBtn.addEventListener('click', function() {
                    // Reset form if it was used for editing
                    const form = serviceForm.querySelector('form');
                    form.reset();
                    form.querySelector('input[name="id"]').value = '0';
                    
                    // Change title to Add New
                    serviceForm.querySelector('h2').textContent = 'Add New Service';
                    
                    // Show the form
                    serviceForm.style.display = 'block';
                    
                    // Scroll to form
                    serviceForm.scrollIntoView({ behavior: 'smooth' });
                });
            }
            
            if (emptyAddBtn) {
                emptyAddBtn.addEventListener('click', function() {
                    // Reset form
                    const form = serviceForm.querySelector('form');
                    form.reset();
                    form.querySelector('input[name="id"]').value = '0';
                    
                    // Change title to Add New
                    serviceForm.querySelector('h2').textContent = 'Add New Service';
                    
                    // Show the form
                    serviceForm.style.display = 'block';
                    
                    // Scroll to form
                    serviceForm.scrollIntoView({ behavior: 'smooth' });
                });
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    // Hide the form
                    serviceForm.style.display = 'none';
                    
                    // If we were editing, remove the query parameter
                    if (window.location.search.includes('edit_service')) {
                        window.location.href = window.location.pathname;
                    }
                });
            }
            
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