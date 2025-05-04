<?php
require "../pdo.php";
// Fetch the current image's labels
$image_id = $_GET['image_id']; // Assume the image ID is passed in the URL
$query = "SELECT label FROM images WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$image_id]);
$image = $stmt->fetch(PDO::FETCH_ASSOC);

// Decode the JSON labels
$current_labels = json_decode($image['label'], true);

// SQL to get similar images
$query = "
    SELECT id, label FROM images WHERE id != ?"; // Avoid selecting the same image
$stmt = $pdo->prepare($query);
$stmt->execute([$image_id]);
$similar_images = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $other_labels = json_decode($row['label'], true);
    
    // Find the intersection of labels
    $common_labels = array_intersect($current_labels, $other_labels);
    
    if (count($common_labels) > 0) {
        $similar_images[] = [
            'id' => $row['id'],
            'common_labels' => $common_labels,
            'label_count' => count($common_labels) // Optionally rank by common label count
        ];
    }
}

// Sort images by common label count (optional)
usort($similar_images, function($a, $b) {
    return $b['label_count'] - $a['label_count']; // Sort descending
});

// Now you can fetch details for the similar images
$similar_image_ids = array_slice(array_column($similar_images, 'id'), 0, 4); // Limit to 4
$placeholders = str_repeat('?,', count($similar_image_ids) - 1) . '?';
$query = "SELECT id, url, label FROM images WHERE id IN ($placeholders)";
$stmt = $pdo->prepare($query);
$stmt->execute($similar_image_ids);
$similar_image_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="similar-images">
    <h3>Similar Images</h3>
    <div class="row">
        <?php foreach ($similar_image_results as $image): ?>
            <div class="col-md-3">
                <div class="similar-image">
                    <img src="../<?= htmlspecialchars($image['url']) ?>" alt="Similar Image">
                    <p>Labels: <?= implode(', ', json_decode($image['label'], true)) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
