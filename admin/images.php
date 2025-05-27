<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
	if(!isset($_SESSION['admin']) && $_SESSION['admin']==True){
		header("location: ../index.php");
	}
 include 'header.php'; ?>
<?php include 'db_connect.php'; ?>
<?php
function generateStars($rating) {
    $stars = '';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;
    
    for ($i = 0; $i < $fullStars; $i++) {
        $stars .= '<i class="bi bi-star-fill text-warning"></i>';
    }
    if ($halfStar) {
        $stars .= '<i class="bi bi-star-half text-warning"></i>';
    }
    for ($i = 0; $i < $emptyStars; $i++) {
        $stars .= '<i class="bi bi-star text-warning"></i>';
    }
    
    return $stars;
}

function getStatusColor($status) {
    switch($status) {
        case 'pending': return 'warning';
        case 'confirmed': return 'primary';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
?>
<h1 class="h3 mb-4 text-gray-800">Images Management</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Images</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="imagesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Photographer</th>
                        <th>Views</th>
                        <th>Likes</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("
                        SELECT i.id, i.title, i.views, i.likes, i.created_at, 
                               b.business_name as photographer, i.url
                        FROM images i
                        JOIN business_profiles b ON i.user_id = b.id
                        ORDER BY i.created_at DESC
                    ");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td><img src='{$row['url']}' style='max-height: 50px; max-width: 80px;'></td>
                            <td>{$row['title']}</td>
                            <td>{$row['photographer']}</td>
                            <td>{$row['views']}</td>
                            <td>{$row['likes']}</td>
                            <td>".date('Y-m-d', strtotime($row['created_at']))."</td>
                            <td>
                                <button class='btn btn-sm btn-info'><i class='bi bi-eye'></i></button>
                                <button class='btn btn-sm btn-danger'><i class='bi bi-trash'></i></button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#imagesTable').DataTable();
});
</script>

<?php include 'footer.php'; ?>