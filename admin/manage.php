<?php
include 'db_connect.php';

$table = $_GET['table'] ?? '';
if (!$table) die("No table selected.");

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Search setup
$search = $_GET['search'] ?? '';

// Get columns
$columnRes = $conn->query("SHOW COLUMNS FROM `$table`");
$columns = [];
while ($col = $columnRes->fetch_assoc()) {
    $columns[] = $col['Field'];
}

// Base query
$where = "";
if ($search) {
    $searchConditions = [];
    foreach ($columns as $col) {
        $searchConditions[] = "`$col` LIKE '%" . $conn->real_escape_string($search) . "%'";
    }
    $where = "WHERE " . implode(" OR ", $searchConditions);
}

// Get total rows
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM `$table` $where");
$totalRows = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch data
$query = "SELECT * FROM `$table` $where LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
$columnInfo = $result ? $result->fetch_fields() : [];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage <?= htmlspecialchars($table) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
            --success-color: #28a745;
            --warning-color: #ffc107;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 100%;
            margin-top: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        
        h2 {
            color: var(--secondary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        
        .table {
            margin-bottom: 0;
            min-width: 100%;
            white-space: nowrap;
        }
        
        .table thead th {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 500;
            border: none;
            padding: 12px 15px;
            position: sticky;
            top: 0;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        /* Rest of your existing styles remain the same */
        .btn {
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            box-shadow: none !important;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-danger {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .form-control {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .page-link {
            color: var(--secondary-color);
            border: 1px solid #dee2e6;
            margin: 0 2px;
            border-radius: 4px !important;
        }
        
        .page-link:hover {
            color: var(--primary-color);
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
        }
        
        .action-buttons .btn:last-child {
            margin-right: 0;
        }
        
        /* Modern card style for header */
        .header-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header-card h2 {
            color: white;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .table thead {
                display: none;
            }
            
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
                white-space: normal;
            }
            
            .table tr {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 4px;
            }
            
            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #dee2e6;
            }
            
            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: calc(50% - 15px);
                padding-right: 15px;
                text-align: left;
                font-weight: bold;
                color: var(--secondary-color);
            }
            
            .action-buttons {
                text-align: center !important;
            }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Managing Table: <?= htmlspecialchars($table) ?></h2>
            <div>
                <a href="index.php" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="edit.php?table=<?= $table ?>" class="btn btn-light">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>
        </div>
    </div>

    <!-- Search -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="hidden" name="table" value="<?= $table ?>">
            <input type="text" name="search" class="form-control" placeholder="Search across all columns..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if ($search): ?>
                <a href="?table=<?= $table ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Table with horizontal scroll -->
    <div class="table-container">
        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <?php foreach ($columnInfo as $col): ?>
                    <th><?= $col->name ?></th>
                <?php endforeach; ?>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($columnInfo as $col): ?>
                        <td data-label="<?= $col->name ?>"><?= htmlspecialchars($row[$col->name]) ?></td>
                    <?php endforeach; ?>
                    <td class="action-buttons text-center">
                        <a href="edit.php?table=<?= $table ?>&id=<?= $row[$columnInfo[0]->name] ?>" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="delete.php?table=<?= $table ?>&id=<?= $row[$columnInfo[0]->name] ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this record?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Table navigation">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?table=<?= $table ?>&search=<?= urlencode($search) ?>&page=<?= $page-1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?table=<?= $table ?>&search=<?= urlencode($search) ?>&page=<?= $p ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?table=<?= $table ?>&search=<?= urlencode($search) ?>&page=<?= $page+1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <!-- Summary -->
    <div class="text-muted text-center mt-3">
        Showing <?= min($limit, $totalRows - $offset) ?> of <?= $totalRows ?> records
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>