<?php
// Start session, include DB
session_start();
require '../pdo.php'; // Your PDO connection file

$user_id = $_SESSION['data']['id'] ?? 0;


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
    <title>Appointments & Services | Photography Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="appointment.css">
</head>
<!-- Services Section -->
<body>
<div class="container">
        <div class="section services-section" id="services-section">
            <h1 style="font-weight: 300; margin-bottom: 30px;">Photography Services</h1>
            
            <!-- Service Form -->
            <form method="post" class="service-form">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">
                
                <div class="form-row">
                    <div class="form-col">
                        <input type="text" name="category" class="form-control" placeholder="Category" required value="<?= htmlspecialchars($edit['category'] ?? '') ?>">
                    </div>
                    <div class="form-col">
                        <input type="text" name="description" class="form-control" placeholder="Description" required value="<?= htmlspecialchars($edit['description'] ?? '') ?>">
                    </div>
                    <div class="form-col">
                        <input type="number" name="price" class="form-control" placeholder="Price" required value="<?= htmlspecialchars($edit['price'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col form-col-4">
                        <input type="text" name="detail_1" class="form-control" placeholder="Detail 1" value="<?= htmlspecialchars($edit['detail_1'] ?? '') ?>">
                        <input type="text" name="detail_2" class="form-control" placeholder="Detail 2" value="<?= htmlspecialchars($edit['detail_2'] ?? '') ?>">
                        <input type="text" name="detail_3" class="form-control" placeholder="Detail 3" value="<?= htmlspecialchars($edit['detail_3'] ?? '') ?>">
                    </div>
                </div>
                
                <button type="submit" class="form-button"><?= $edit ? 'Update' : 'Add' ?> Service</button>
                <?php if ($edit): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="form-button secondary">Cancel</a>
                <?php endif; ?>
            </form>
            
            <!-- Services Table -->
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Details</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM appointment_services WHERE photographer_id = ? ORDER BY created_at DESC");
                    $stmt->execute([$user_id]);
                    while($s = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($s['category']) ?></td>
                        <td><?= htmlspecialchars($s['description']) ?></td>
                        <td>$<?= number_format($s['price'], 2) ?></td>
                        <td class="service-details">
                            <?php if (!empty($s['detail_1'])): ?><?= htmlspecialchars($s['detail_1']) ?><br><?php endif; ?>
                            <?php if (!empty($s['detail_2'])): ?><?= htmlspecialchars($s['detail_2']) ?><br><?php endif; ?>
                            <?php if (!empty($s['detail_3'])): ?><?= htmlspecialchars($s['detail_3']) ?><?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit_service=<?= $s['id'] ?>" class="action-button edit">Edit</a>
                            <a href="?delete_service=<?= $s['id'] ?>" onclick="return confirm('Delete this service?')" class="action-button delete">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($stmt->rowCount() === 0): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-camera"></i>
                                <h3>No services found</h3>
                                <p>Add your first photography service using the form above.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
</div>
</body>
</html>