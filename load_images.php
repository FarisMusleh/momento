<?php
require '../pdo.php'; // DB connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['data'])) {
    $data = $_SESSION['data'];
}

$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
$limit = 20;
$userLocation = 'USA'; // This can be made dynamic

$sql = "
    SELECT 
        images.id,
        images.label,
        images.description,
        images.url,
        images.views,
        images.likes,
        images.created_at,
        images.is_sensitive,
        accounts.username,
        accounts.location,
        accounts.picture,
        (
            (images.likes * 3 + images.views) / 
            POW(TIMESTAMPDIFF(HOUR, images.created_at, NOW()) + 2, 1.5)
        ) * IF(accounts.location = :userLocation, 1.5, 1) AS score
    FROM images
    JOIN accounts ON images.user_id = accounts.id
    ORDER BY score DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':userLocation', $userLocation);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($images as $image) {
    $btnClass = '';
    if (isset($_SESSION['data'])) {
        $stmtLike = $pdo->prepare("SELECT 1 FROM likes WHERE user_id = ? AND image_id = ?");
        $stmtLike->execute([$data['id'], $image['id']]);
        $liked = $stmtLike->fetch();
        $btnClass = $liked ? 'liked-button' : '';
    }

    echo '<div class="gallery-item position-relative sensitive-content" style="overflow: hidden;">';

    if ($image['is_sensitive'] == 1) {
        echo '<img loading="lazy" id="myImage" src="' . htmlspecialchars($image['url']) . '" class="w-100 sensitive-image" alt="' . htmlspecialchars($image['label']) . '">';
        echo '<div class="content-warning" id="warningOverlay">';
        echo '<div class="warning-icon">⚠️</div>';
        echo '<h3 class="warning-heading">Sensitive Content</h3>';
        echo '<p class="warning-description">This image contains content that may be disturbing to some viewers.</p>';
        echo '<button class="reveal-button" onclick="revealImage(this)">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">';
        echo '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>';
        echo '<path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>';
        echo '</svg> View Content</button>';
        echo '</div>';
    } else {
        echo '<img loading="lazy" src="' . htmlspecialchars($image['url']) . '" class="w-100" alt="' . htmlspecialchars($image['label']) . '">';
    }

    echo '<a href="view_image.php?id=' . $image['id'] . '" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;"></a>';
    echo '<div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-3" style="z-index: 3; pointer-events: none;">';

    echo '<div class="mt-2 d-flex align-items-center" style="pointer-events: auto;">';
    echo '<img src="' . htmlspecialchars($image['picture']) . '" alt="Profile" class="me-2 pfp" style="width:30px;height:30px;">';
    echo '<span class="text-white text-capitalize">';
    echo '<a href="/momento/profile.php?username=' . htmlspecialchars($image['username']) . '" style="color:white;" onclick="event.stopPropagation();">';
    echo htmlspecialchars($image['username']);
    echo '</a></span></div>';

    echo '<div class="d-flex align-items-center justify-content-between text-white" style="pointer-events: auto;">';
    echo '<button class="btn like-btn ' . $btnClass . '" data-image-id="' . $image['id'] . '" onclick="event.stopPropagation();">';
    echo '<i class="bi bi-suit-heart-fill" style="margin:auto;"></i></button>';
    echo '<span><i class="fas fa-eye"></i> ' . $image['views'] . '</span>';
    echo '</div></div></div>';
}
?>
