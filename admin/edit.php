<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
	if(!isset($_SESSION['data']['admin']) && $_SESSION['data']['admin']==True){
		header("location: ../index.php");
	}

include 'db_connect.php';

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? null;

if (!$table) die("No table selected.");

$isEdit = $id !== null;

// Get columns
$columnsRes = $conn->query("SHOW COLUMNS FROM `$table`");
$columns = [];
while ($col = $columnsRes->fetch_assoc()) {
    $columns[] = $col;
}

// Fetch row for editing
$data = [];
if ($isEdit) {
    $res = $conn->query("SELECT * FROM `$table` WHERE `{$columns[0]['Field']}` = '$id'");
    $data = $res->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [];
    foreach ($columns as $col) {
        if ($col['Extra'] === 'auto_increment') continue;
        $field = $col['Field'];
        $value = $conn->real_escape_string($_POST[$field]);
        $fields[$field] = "'$value'";
    }

    if ($isEdit) {
        $updates = [];
        foreach ($fields as $field => $value) {
            $updates[] = "`$field` = $value";
        }
        $conn->query("UPDATE `$table` SET " . implode(',', $updates) . " WHERE `{$columns[0]['Field']}` = '$id'");
    } else {
        $conn->query("INSERT INTO `$table` (" . implode(',', array_keys($fields)) . ") VALUES (" . implode(',', $fields) . ")");
    }

    header("Location: manage.php?table=$table");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $isEdit ? 'Edit' : 'Add' ?> Record in <?= htmlspecialchars($table) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #f8f9fa;
            --accent-color: #2c3e50;
            --border-radius: 6px;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 800px;
            margin-top: 40px;
            margin-bottom: 40px;
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        h2 {
            color: var(--accent-color);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }
        
        .form-control {
            padding: 10px 15px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }
        
        .mb-3 {
            margin-bottom: 1.5rem !important;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2><?= $isEdit ? 'Edit' : 'Add' ?> Record: <?= htmlspecialchars($table) ?></h2>
    <form method="post">
        <?php foreach ($columns as $col):
            if ($col['Extra'] === 'auto_increment') continue;
            $field = $col['Field'];
        ?>
            <div class="mb-3">
                <label class="form-label"><?= $field ?></label>
                <input type="text" name="<?= $field ?>" class="form-control" value="<?= htmlspecialchars($data[$field] ?? '') ?>">
            </div>
        <?php endforeach; ?>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Add' ?> Record</button>
            <a href="manage.php?table=<?= $table ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>