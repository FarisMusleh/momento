<?php
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	require 'pdo.php';
	$data = $_SESSION['data'] ?? null;
	if(!isset($_GET['id'])){
		header('location: gallery.php');
	}
	$sql_image_info = $pdo->prepare('select label,title,description,views,likes,url,img.created_at,username,business_name
	from images as img, accounts as acc,business_profiles as bs where img.id = ? and img.user_id = bs.id and acc.id = bs.id');
	$sql_image_info->execute([intval($_GET['id'])]);
	$img = $sql_image_info->fetch();
	if(!$img){
		header('location: gallery.php');
	}
	$sql_comments = $pdo->prepare(
    'SELECT c.id, c.user_id, c.image_id, c.comment, c.likes, c.created_at, a.picture, a.username
     FROM comments AS c
     JOIN accounts AS a ON a.id = c.user_id
     WHERE c.image_id = ?'
	);

	$sql_comments->execute([intval($_GET['id'])]);
	$comments = $sql_comments->fetchAll();
	
	function timeAgo($datetime) {
    $now = new DateTime();
    $commentTime = new DateTime($datetime);
    $diff = $now->diff($commentTime);

    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'just now';
	}
	
	require('queries/view_counter.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Image Review - Moomento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/header.css">
  <style>
    :root {
      --primary: #2563eb;
      --primary-light: #dbeafe;
      --primary-dark: #1e40af;
      --text-dark: #1e293b;
      --text-muted: #64748b;
      --bg-light: #f1f5f9;
      --border-light: #e2e8f0;
      --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --transition: all 0.3s ease;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--text-dark);
      background-color: #f8fafc;
      line-height: 1.7;
      margin: 0;
      padding: 0;
    }

    .page-container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .content-area {
      display: grid;
      grid-template-columns: 1fr;
      gap: 40px;
      margin: 40px 0;
    }
    
    @media (min-width: 992px) {
      .content-area {
        grid-template-columns: 2fr 1fr;
      }
    }
    
    /* Image Card */
    .image-card {
      border-radius: 16px;
      overflow: hidden;
      background-color: white;
      box-shadow: var(--card-shadow);
      transition: var(--transition);
      position: relative;
    }
    
    .image-card:hover {
      box-shadow: var(--hover-shadow);
    }
    
    .image-preview {
      width: 100%;
      height: auto;
      display: block;
      max-height: 80vh;
      object-fit: contain;
      background-color: #f8fafc;
      cursor: zoom-in;
    }
    
    /* Info Card */
    .info-card {
      position: sticky;
      top: 24px;
      padding: 28px;
      border-radius: 16px;
      background-color: white;
      box-shadow: var(--card-shadow);
      transition: var(--transition);
    }
    
    .info-card:hover {
      box-shadow: var(--hover-shadow);
    }
    
    .image-title {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: var(--text-dark);
      letter-spacing: -0.5px;
    }
    
    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 14px;
      color: var(--text-muted);
      font-size: 0.95rem;
    }
    
    .info-item i {
      margin-right: 12px;
      width: 20px;
      color: var(--primary);
    }
    
    .user-link {
      color: var(--primary-dark);
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
    }
    
    .user-link:hover {
      color: var(--primary);
      text-decoration: underline;
    }
    
    /* Action Buttons */
    .action-bar {
      display: flex;
      gap: 16px;
      margin-top: 28px;
      flex-wrap: wrap;
    }
    
    .action-btn {
      display: flex;
      align-items: center;
      padding: 10px 18px;
      border-radius: 50px;
      font-weight: 600;
      border: none;
      background: var(--bg-light);
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      cursor: pointer;
      transition: var(--transition);
      color: var(--text-dark);
    }
    
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .action-btn i {
      margin-right: 10px;
      font-size: 1.1rem;
    }
    
    .action-btn.like-btn {
      color: var(--text-dark);
    }
    
    .action-btn.like-btn.liked {
      color: #e11d48;
      background-color: #ffe4e6;
    }
    
    .action-btn.primary-btn {
      background-color: var(--primary);
      color: white;
    }
    
    .action-btn.primary-btn:hover {
      background-color: var(--primary-dark);
    }
    
    .action-counter {
      font-size: 0.875rem;
      margin-left: 6px;
      font-weight: 600;
    }
    
    /* Tags */
    .tag-list {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin: 24px 0;
    }
    
    .tag {
      padding: 6px 14px;
      background-color: var(--primary-light);
      border-radius: 50px;
      font-size: 0.85rem;
      color: var(--primary-dark);
      font-weight: 500;
      transition: var(--transition);
    }
    
    .tag:hover {
      background-color: var(--primary);
      color: white;
      transform: translateY(-2px);
    }
    
    /* Comments Section */
    .comments-section {
      margin-top: 40px;
      padding: 28px;
      border-radius: 16px;
      background-color: white;
      box-shadow: var(--card-shadow);
    }
    
    .section-title {
      font-size: 1.35rem;
      font-weight: 700;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      color: var(--text-dark);
      letter-spacing: -0.3px;
    }
    
    .section-title i {
      margin-right: 12px;
      color: var(--primary);
    }
    
    .comment-form {
      margin-bottom: 32px;
    }
    
    .comment-textarea {
      width: calc(100% - 32px);
      padding: 18px;
      border-radius: 12px;
      border: 1px solid var(--border-light);
      background-color: var(--bg-light);
      resize: none;
      margin-bottom: 16px;
      transition: var(--transition);
      font-family: inherit;
      font-size: 0.95rem;
    }
    
    .comment-textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-light);
    }
    
    .comment {
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 12px;
      border-bottom: none;
      background-color: var(--bg-light);
      transition: var(--transition);
    }
    
    .comment:hover {
      background-color: #e8f1ff;
    }
    
    .comment-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      align-items: center;
    }
    
    .comment-user {
      font-weight: 700;
      color: var(--text-dark);
      font-size: 1.05rem;
    }
    
    .comment-time {
      font-size: 0.85rem;
      color: var(--text-muted);
      font-weight: 500;
    }
    
    .comment-text {
      margin-bottom: 12px;
      line-height: 1.6;
    }
    
    .comment-actions {
      display: flex;
      gap: 20px;
    }
    
    .comment-action {
      font-size: 0.875rem;
      color: var(--text-muted);
      background: none;
      border: none;
      padding: 6px 12px;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      border-radius: 20px;
      font-weight: 500;
    }
    
    .comment-action:hover {
      color: var(--primary);
      background-color: rgba(37, 99, 235, 0.1);
    }
    
    .comment-action i {
      margin-right: 6px;
      font-size: 0.9rem;
    }
    
    /* Similar Images */
    .similar-images {
      margin-top: 40px;
    }
    
    .similar-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    
    .similar-item {
      overflow: hidden;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      transition: var(--transition);
      position: relative;
    }
    
    .similar-item:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: var(--hover-shadow);
    }
    
    .similar-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      transition: var(--transition);
    }
    
    .similar-item:hover .similar-img {
      transform: scale(1.05);
    }
    
    /* Lightbox */
    .lightbox {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s;
    }
    
    .lightbox.active {
      opacity: 1;
      pointer-events: all;
    }
    
    .lightbox-img {
      max-width: 90%;
      max-height: 90%;
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .lightbox-close {
      position: absolute;
      top: 30px;
      right: 30px;
      color: white;
      font-size: 2.5rem;
      cursor: pointer;
      background: rgba(0, 0, 0, 0.3);
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
    }
    
    .lightbox-close:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: rotate(90deg);
    }
    
    /* Like button states */
    .like-comment-btn.liked {
      color: #e11d48;
    }
    
    .like-comment-btn.liked i {
      color: #e11d48;
    }
    
    .like-comment-btn:hover {
      transform: scale(1.05);
    }
    
    /* Description area */
    .description-section {
      margin-top: 24px;
      padding: 20px;
      background-color: var(--bg-light);
      border-radius: 12px;
      border-left: 4px solid var(--primary);
    }
    
    .description-section h4 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 12px;
      color: var(--primary-dark);
    }
    
    /* Responsive Improvements */
    @media (max-width: 768px) {
      .page-container {
        padding: 0 16px;
      }
      
      .content-area {
        margin: 20px 0;
        gap: 24px;
      }
      
      .info-card {
        position: relative;
        top: 0;
      }
      
      .image-title {
        font-size: 1.5rem;
      }
      
      .comments-section, .info-card {
        padding: 20px;
      }
      
      .action-bar {
        justify-content: center;
      }
    }
    
    /* Tooltip improvement */
    .share-tooltip {
      position: relative;
      display: inline-block;
    }
    
    .tooltip-text {
      position: absolute;
      bottom: 125%;
      left: 50%;
      transform: translateX(-50%);
      background-color: var(--primary-dark);
      color: white;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      white-space: nowrap;
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.3s, transform 0.3s;
    }
    
    .tooltip-text.visible {
      visibility: visible;
      opacity: 1;
      transform: translateX(-50%) translateY(-5px);
    }
    
    .tooltip-text::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 50%;
      margin-left: -5px;
      border-width: 5px;
      border-style: solid;
      border-color: var(--primary-dark) transparent transparent transparent;
    }
  </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
</head>
<body>
	<!-- Navigation -->
    <?php require "header.php";?>
  <div class="page-container">
    
    
    <!-- Main Content -->
    <div class="content-area">
      <!-- Image Section -->
      <div>
        <div class="image-card">
          <img src="<?=$img['url']?>" alt="Elegant Black Car" class="image-preview" id="mainImage">
        </div>
        
        <!-- Comments Section -->
        <div class="comments-section">
          <h3 class="section-title">
            <i class="bi bi-chat-left-text"></i>
            Comments <span class="action-counter" id="commentCount"><?php echo count($comments);?></span>
          </h3>
          
          <!-- Add Comment -->
          <form class="comment-form" id="commentForm">
            <textarea class="comment-textarea" id="commentInput" rows="3" placeholder="Share your thoughts..."></textarea>
            <button type="submit" class="action-btn primary-btn">
              <i class="bi bi-send"></i> Post Comment
            </button>
          </form>
          
          <!-- Comment List -->
          <div id="commentsList">
		    <?php foreach($comments as $comment){?>
            <div class="comment">
              <div class="comment-header">
                <span class="comment-user"><?=$comment['username']?></span>
                <span class="comment-time"><?php echo timeAgo($comment['created_at']);?></span>
              </div>
              <p class="comment-text"><?=$comment['comment']?></p>
			  <?php
					if($data){
					$check = $pdo->prepare("SELECT 1 FROM comment_likes WHERE user_id = ? AND comment_id = ?");
					$check->execute([$_SESSION['data']['id'], $comment['id']]);
					$liked = $check->fetch();
					}
			  ?>
              <div class="comment-actions">
				<button class="comment-action like-comment-btn <?= $liked ? 'liked' : '' ?>" data-comment-id="<?= $comment['id']??"" ?>">
				  <i class="bi <?= $liked ? 'bi-heart-fill' : 'bi-heart' ?>"></i> Like&nbsp;
				  <span class="comment-like-counter"><?= $comment['likes']??"" ?></span>
				</button>
              </div>
            </div>
			<?php }?>
          </div>
        </div>
        
        <?php
			// 1. Get current image data
			$stmt = $pdo->prepare("SELECT id, label, url, description FROM images WHERE id = ?");
			$stmt->execute([$_GET['id']]);
			$current = $stmt->fetch(PDO::FETCH_ASSOC);

			$imagePath = __DIR__ . '/' . $current['url'];
			$caption = $current['description'];

			// 2. Send to Flask to get best keyword
			$curl = curl_init();
			$postFields = [
				'image' => new CURLFile($imagePath),
				'caption' => $caption,
				'keywords' => json_encode(json_decode($current['label'], true))  // Ensure valid JSON string
			];


			curl_setopt_array($curl, [
				CURLOPT_URL => "http://127.0.0.1:5000/get_best_keyword",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $postFields
			]);

			$response = curl_exec($curl);
			if (curl_errno($curl)) {
				die("Keyword extraction failed: " . curl_error($curl));
			}
			curl_close($curl);

			$result = json_decode($response, true);
			$bestKeyword = $result['best_keyword'] ?? null;

			if (!$bestKeyword) {
				die("No keyword received from AI service.");
			}

			// 3. Find similar images based on that keyword
			$stmt_sim = $pdo->prepare("SELECT id, label, url FROM images WHERE id != ?");
			$stmt_sim->execute([$_GET['id']]);
			$res = $stmt_sim->fetchAll();

			$similar = [];
			foreach ($res as $row) {
				$labels = json_decode($row['label'], true);
				if (in_array($bestKeyword, $labels)) {
					$similar[] = [
						'id' => $row['id'],
						'image_url' => $row['url']
					];
				}
			}

			// 4. Limit to 4
			$similar_image_results = array_slice($similar, 0, 4);
		?>
		<div class="similar-images">
		  <h3 class="section-title">
			<i class="bi bi-grid"></i>
			Similar Images (Keyword: <?= htmlspecialchars($bestKeyword) ?>)
		  </h3>
		  <div class="similar-grid">
			<?php foreach ($similar_image_results as $image): ?>
			  <a href="view_image.php?id=<?= $image['id'] ?>" class="similar-item">
				<img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Similar image" class="similar-img">
			  </a>
			<?php endforeach; ?>
		  </div>
		</div>

      </div>
      
      <!-- Info Section -->
      <div class="info-card">
        <h1 class="image-title"><?=$img['title']?></h1>
        <div class="info-item">
          <i class="bi bi-person"></i>
          By <a href="<?php echo "/momento/profile.php?username=".$img['username']?>" class="user-link">&nbsp;<?=$img['business_name']?></a>
        </div>
        
        <div class="info-item">
          <i class="bi bi-calendar3"></i>
          <?php
			$dateOnly = date("Y-m-d",strtotime($img['created_at']));
			echo $dateOnly;
		  ?>
        </div>
        
        <div class="info-item">
          <i class="bi bi-eye"></i>
          <?=$img['views']?> views
        </div>
        
        <div class="tag-list">
		  <?php 
		  if(isset($img['label'])){
			foreach(json_decode($img['label']) as $row){
				echo '<span class="tag" style = "text-transform: capitalize;">'.$row.'</span>';
			};
		  }
		  ?>
        </div>
        <?php if(isset($_SESSION['data'])){
				$stmt = $pdo->prepare("SELECT 1 FROM likes WHERE user_id = ? AND image_id = ?");
				$stmt->execute([$_SESSION['data']['id'], $_GET['id']]);
				$liked = $stmt->fetch();
				$btnClass = $liked ? 'liked-button' : '';
			}
			?>
        <div class="action-bar">
			<button class="action-btn like-btn <?= $liked ? 'liked' : '' ?>" data-image-id="<?= $_GET['id'] ?>">
			  <i class="bi bi-heart"></i> Like
			  <span class="action-counter"><?= $img['likes'] ?></span>
			</button>

          
          <button class="action-btn" id="downloadButton">
            <i class="bi bi-download"></i> Download
          </button>
          
          <div class="share-tooltip">
            <button class="action-btn" id="shareButton">
              <i class="bi bi-share"></i> Share
            </button>
            <span class="tooltip-text" id="shareTooltip">Link copied!</span>
          </div>
        </div>
        
        <div class="description-section mt-4">
          <h4>Description</h4>
          <p><?=$img['description']?></p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <?php require('footer.php')?>
  
  <!-- Lightbox -->
  <div class="lightbox" id="imageLightbox">
    <img src="" alt="Full-size image" class="lightbox-img" id="lightboxImg">
    <button class="lightbox-close" id="lightboxClose">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
  

  <!-- Scripts -->
  <script>
const isLogged = <?php if($data){echo "true";}else{echo "false";}?> 
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.like-comment-btn').forEach(button => {
    button.addEventListener('click', function () {
      if (!isLogged) {
        window.location.href = 'account/login.php';
        return;
      }

      const commentId = this.dataset.commentId;
      const action = this.classList.contains('liked') ? 'unlike' : 'like';
      const counter = this.querySelector('.comment-like-counter');
      const btn = this;

      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'like_comment.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function () {
        if (xhr.status === 200) {
			let count = parseInt(counter.textContent);
			const icon = btn.querySelector('i');
			if (action === 'like') {
			  btn.classList.add('liked');
			  icon.classList.remove('bi-heart');
			  icon.classList.add('bi-heart-fill');
			  counter.textContent = count + 1;
			} else {
			  btn.classList.remove('liked');
			  icon.classList.remove('bi-heart-fill');
			  icon.classList.add('bi-heart');
			  counter.textContent = Math.max(0, count - 1);
			}
        }
      };

      xhr.send(`comment_id=${commentId}&action=${action}`);
    });
  });
});


  
  
  
  document.addEventListener('DOMContentLoaded', () => {
  const likeButton = document.querySelector('.action-btn.like-btn');
  if (!likeButton) return;

  likeButton.addEventListener('click', () => {
    if (!isLogged) {
      window.location.href = 'account/login.php';
      return;
    }

    const imageId = likeButton.dataset.imageId;
    const action = likeButton.classList.contains('liked') ? 'unlike' : 'like';
    const likeCountSpan = likeButton.querySelector('.action-counter');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'like_unlike.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
      if (xhr.status === 200) {
        let currentCount = parseInt(likeCountSpan.textContent);
        if (action === 'like') {
          likeButton.classList.add('liked');
          likeCountSpan.textContent = currentCount + 1;
        } else {
          likeButton.classList.remove('liked');
          likeCountSpan.textContent = Math.max(0, currentCount - 1);
        }
      }
    };

    xhr.send(`image_id=${imageId}&action=${action}`);
  });
});
  
const imageId = <?= json_encode($_GET['id']) ?>; 
  // Comment functionality
const commentForm = document.getElementById('commentForm');
const commentInput = document.getElementById('commentInput');
const commentsList = document.getElementById('commentsList');
const commentCount = document.getElementById('commentCount');

commentForm.addEventListener('submit', (e) => {
	if(!isLogged){
			window.location.href = '/momento/account/login.php';
	}
  e.preventDefault();
  const text = commentInput.value.trim();
  if (!text) return;

  fetch('add_comment.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      comment: text,
      image_id: imageId
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const newComment = document.createElement('div');
      newComment.className = 'comment';
      newComment.innerHTML = `
        <div class="comment-header">
          <span class="comment-user">${data.comment.user}</span>
          <span class="comment-time">${data.comment.time}</span>
        </div>
        <p class="comment-text">${data.comment.text}</p>
        <div class="comment-actions">
          <button class="comment-action like-comment-btn"><i class="bi bi-heart"></i> Like <span class="comment-like-counter">0</span></button>
        </div>
      `;
      commentsList.prepend(newComment);
      commentInput.value = '';
      const count = commentsList.querySelectorAll('.comment').length;
      commentCount.textContent = `${count}`;
    } else {
      
    }
  })
  .catch(err => {
    console.error('Error:', err);
    
  });
});

    
    // Share functionality
    const shareButton = document.getElementById('shareButton');
    const shareTooltip = document.getElementById('shareTooltip');
    
    shareButton.addEventListener('click', () => {
      navigator.clipboard.writeText(window.location.href);
      shareTooltip.classList.add('visible');
      
      setTimeout(() => {
        shareTooltip.classList.remove('visible');
      }, 2000);
    });
    
    // Download functionality
    const downloadButton = document.getElementById('downloadButton');
    
	if(isLogged){
    downloadButton.addEventListener('click', () => {
      window.location.href = '/momento/download_handler.php?imageId=<?= $_GET['id'] ?>';
    });
    }else{
		downloadButton.addEventListener('click', () => {
      window.location.href = '/momento/account/login.php';
    });
	}
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    
    if (darkModeToggle) {
      darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const icon = darkModeToggle.querySelector('i');
        
        if (body.classList.contains('dark-mode')) {
          icon.classList.remove('bi-moon');
          icon.classList.add('bi-sun');
        } else {
          icon.classList.remove('bi-sun');
          icon.classList.add('bi-moon');
        }
      });
    }
    
    // Lightbox functionality
    const mainImage = document.getElementById('mainImage');
    const lightbox = document.getElementById('imageLightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxClose = document.getElementById('lightboxClose');
    
    mainImage.addEventListener('click', () => {
      lightboxImg.src = mainImage.src;
      lightbox.classList.add('active');
    });
    
    lightboxClose.addEventListener('click', () => {
      lightbox.classList.remove('active');
    });
    
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox) {
        lightbox.classList.remove('active');
      }
    });
  </script>
</body>
</html>
