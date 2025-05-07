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
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2c3e50;
            --light: #ecf0f1;
            --danger: #e74c3c;
            --success: #2ecc71;
            --gray: #95a5a6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        h1 {
            color: var(--secondary);
            font-weight: 600;
        }
        
        .btn {
            padding: 10px 15px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-danger {
            background-color: var(--danger);
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary);
        }
        
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .service-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .service-header {
            background-color: var(--primary);
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .price-tag {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 16px;
        }
        
        .service-body {
            padding: 20px;
        }
        
        .service-description {
            margin-bottom: 15px;
            color: #555;
        }
        
        .service-details {
            list-style-type: none;
        }
        
        .service-details li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .service-details li:last-child {
            border-bottom: none;
        }
        
        .service-details i {
            color: var(--primary);
            margin-right: 10px;
        }
        
        .service-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 0;
            color: var(--gray);
        }
        
        .empty-state i {
            font-size: 50px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container">
	
        <header>
            <h1>Manage Photography Services</h1>
            <button id="addServiceBtn" class="btn">
                <i class="fas fa-plus"></i> Add New Service
            </button>
        </header>
        <div class="main-nav">
			<a href="appointments_inbox.php" class="nav-tab <?php echo basename($_SERVER['PHP_SELF']) == 'appointments_inbox.php' ? 'active' : ''; ?>">Appointments</a>
			<a href="services.php" class="nav-tab <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">Services</a>
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
        });
    </script>
</body>
</html>
