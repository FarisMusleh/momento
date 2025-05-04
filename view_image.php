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
  
  <style>
    :root {
      --primary: #0d6efd;
      --text-dark: #212529;
      --text-muted: #6c757d;
      --bg-light: #f8f9fa;
      --border-light: #dee2e6;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--text-dark);
      background-color: white;
      line-height: 1.6;
    }
	.page-container{
		max-width: 1200px;
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
    
    .image-card {
      border-radius: 12px;
      overflow: hidden;
      background-color: var(--bg-light);
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .image-preview {
      width: 100%;
      height: auto;
      display: block;
      max-height: 80vh;
      object-fit: contain;
    }
    
    .info-card {
      position: sticky;
      top: 24px;
      padding: 24px;
      border-radius: 12px;
      background-color: var(--bg-light);
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .image-title {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 16px;
    }
    
    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
      color: var(--text-muted);
    }
    
    .info-item i {
      margin-right: 10px;
      width: 20px;
    }
    
    .user-link {
      color: var(--text-dark);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }
    
    .user-link:hover {
      color: var(--primary);
    }
    
    .action-bar {
      display: flex;
      gap: 16px;
      margin-top: 24px;
      flex-wrap: wrap;
    }
    
    .action-btn {
      display: flex;
      align-items: center;
      padding: 8px 16px;
      border-radius: 50px;
      font-weight: 500;
      border: none;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: all 0.2s;
    }
    
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .action-btn i {
      margin-right: 8px;
    }
    
    .action-btn.like-btn {
      color: var(--text-dark);
    }
    
    .action-btn.like-btn.liked {
      color: #e74c3c;
      background-color: #fde8e7;
    }
    
    .action-btn.primary-btn {
      background-color: var(--primary);
      color: white;
    }
    
    .action-counter {
      font-size: 0.875rem;
      margin-left: 6px;
    }
    
    .tag-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin: 24px 0;
    }
    
    .tag {
      padding: 4px 12px;
      background-color: white;
      border-radius: 50px;
      font-size: 0.875rem;
      color: var(--text-muted);
    }
    
    .comments-section {
      margin-top: 40px;
      padding: 24px;
      border-radius: 12px;
      background-color: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .section-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
    }
    
    .section-title i {
      margin-right: 10px;
    }
    
    .comment-form {
      margin-bottom: 32px;
    }
    
    .comment-textarea {
      width: 100%;
      padding: 16px;
      border-radius: 12px;
      border: 1px solid var(--border-light);
      background-color: white;
      resize: none;
      margin-bottom: 16px;
      transition: border-color 0.2s;
    }
    
    .comment-textarea:focus {
      outline: none;
      border-color: var(--primary);
    }
    
    .comment {
      padding-bottom: 20px;
      margin-bottom: 20px;
      border-bottom: 1px solid var(--border-light);
    }
    
    .comment:last-child {
      border-bottom: none;
    }
    
    .comment-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
    }
    
    .comment-user {
      font-weight: 600;
      color: var(--text-dark);
    }
    
    .comment-time {
      font-size: 0.875rem;
      color: var(--text-muted);
    }
    
    .comment-text {
      margin-bottom: 8px;
    }
    
    .comment-actions {
      display: flex;
      gap: 16px;
    }
    
    .comment-action {
      font-size: 0.875rem;
      color: var(--text-muted);
      background: none;
      border: none;
      padding: 0;
      cursor: pointer;
      transition: color 0.2s;
      display: flex;
      align-items: center;
    }
    
    .comment-action:hover {
      color: var(--text-dark);
    }
    
    .comment-action i {
      margin-right: 4px;
      font-size: 0.75rem;
    }
    
    .similar-images {
      margin-top: 40px;
    }
    
    .similar-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 16px;
      margin-top: 16px;
    }
    
    .similar-item {
      overflow: hidden;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    
    .similar-item:hover {
      transform: translateY(-4px);
    }
    
    .similar-img {
      width: 100%;
      max-height: 200px;
      object-fit: cover;
    }
    
    .footer {
      text-align: center;
      padding: 32px 0;
      color: var(--text-muted);
      border-top: 1px solid var(--border-light);
      margin-top: 40px;
    }
    
    .share-tooltip {
      position: relative;
      display: inline-block;
    }
    
    .tooltip-text {
      position: absolute;
      bottom: 125%;
      left: 50%;
      transform: translateX(-50%);
      background-color: #333;
      color: white;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 0.75rem;
      white-space: nowrap;
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.3s;
    }
    
    .tooltip-text.visible {
      visibility: visible;
      opacity: 1;
    }
    
    /* Dark mode toggle */
    .dark-mode-toggle {
      margin-left: auto;
      background: none;
      border: none;
      cursor: pointer;
      font-size: 1.25rem;
      color: var(--text-dark);
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
    }
    
    .lightbox-close {
      position: absolute;
      top: 20px;
      right: 20px;
      color: white;
      font-size: 2rem;
      cursor: pointer;
      background: none;
      border: none;
    }
	.like-comment-btn.liked {
  color: #e74c3c;
}
	.like-comment-btn.liked i {
  color: #e74c3c;
}
.like-comment-btn:hover {
  transform: scale(1.05);
}
  </style>
   <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include Bootstrap Icons if not already -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
	<!-- Bootstrap + Icons -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="css/header.css">
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
        
        <!-- Similar Images -->
		<?php
			$stmt = $pdo->prepare("SELECT label FROM images WHERE id = ?");
			$stmt->execute([$_GET['id']]);
			$current = $stmt->fetch(PDO::FETCH_ASSOC);
			$current_labels = json_decode($current['label'], true);

			// Step 2: Find other images with similar labels
			$stmt = $pdo->prepare("SELECT id, label, url FROM images WHERE id != ?");
			$stmt->execute([$_GET['id']]);

			$similar = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$labels = json_decode($row['label'], true);
				$common = array_intersect($current_labels, $labels);
				if (count($common) > 0) {
					$similar[] = [
						'id' => $row['id'],
						'image_url' => $row['url'],
						'common_count' => count($common)
					];
				}
			}

			// Step 3: Sort by number of matching labels, then limit to 4
			usort($similar, fn($a, $b) => $b['common_count'] - $a['common_count']);
			$similar_image_results = array_slice($similar, 0, 4);
		?>
		<div class="similar-images">
		  <h3 class="section-title">
			<i class="bi bi-grid"></i>
			Similar Images
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
			foreach(json_decode($img['label']) as $row){
				echo '<span class="tag" style = "text-transform: capitalize;">'.$row.'</span>';
			};
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
        
        <div class="mt-4">
          <h4 class="mb-2">Description</h4>
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
          <button class="comment-action"><i class="bi bi-heart"></i> Like</button>
        </div>
      `;
      commentsList.prepend(newComment);
      commentInput.value = '';
      const count = commentsList.querySelectorAll('.comment').length;
      commentCount.textContent = `${count}`;
    } else {
      alert('Error posting comment. Please try again.');
    }
  })
  .catch(err => {
    console.error('Error:', err);
    alert('There was an error submitting your comment.');
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
      window.location.href = '/momento/';
    });
	}
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    
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