<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
	if(!isset($_SESSION['data']['admin']) && $_SESSION['data']['admin']==True){
		header("location: ../index.php");
	}
// sidebar.php
$sections = [
    'Admin' => ['accounts', 'user_profiles', 'paypal_payments'],
    'Photographers' => ['images', 'comments', 'likes', 'reviews', 'image_view_logs'],
    'Businesses' => ['business_profiles', 'appointments', 'appointment_services'],
    'Messaging' => ['messages', 'conversations'],
    'Social & Logs' => ['followers', 'comment_likes', 'image_downloads', 'photographer_view_logs'],
];
?>
<style>
    :root {
        --sidebar-bg: #1a1a2e;
        --sidebar-accent: #16213e;
        --sidebar-text: #e6e6e6;
        --sidebar-text-muted: #b8b8b8;
        --sidebar-hover: #0f3460;
        --sidebar-active: #4a4a8a;
        --sidebar-border: #2d2d42;
        --sidebar-highlight: #4cc9f0;
        --transition-speed: 0.3s;
    }

    .sidebar-container {
        background: var(--sidebar-bg);
        color: var(--sidebar-text);
        width: 280px;
        min-height: 100vh;
        padding: 1.5rem 1rem;
        border-right: 1px solid var(--sidebar-border);
        transition: all var(--transition-speed) ease;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header {
        padding: 0 0.5rem 1rem;
        border-bottom: 1px solid var(--sidebar-border);
        margin-bottom: 1rem;
    }

    .sidebar-header a {
        color: var(--sidebar-text);
        font-size: 1.25rem;
        font-weight: 600;
        transition: color var(--transition-speed);
    }

    .sidebar-header a:hover {
        color: var(--sidebar-highlight);
        text-decoration: none;
    }

    .sidebar-divider {
        border-color: var(--sidebar-border);
        opacity: 0.5;
        margin: 1rem 0;
    }

    .sidebar-section-title {
        color: var(--sidebar-text-muted);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        margin-top: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .sidebar-nav {
        padding-left: 0;
        margin-bottom: 1.5rem;
    }

    .sidebar-nav .nav-item {
        margin: 0.15rem 0;
    }

    .sidebar-nav .nav-link {
        color: var(--sidebar-text);
        border-radius: 4px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        transition: all var(--transition-speed);
        display: flex;
        align-items: center;
    }

    .sidebar-nav .nav-link:hover {
        background-color: var(--sidebar-hover);
        color: white;
        transform: translateX(3px);
    }

    .sidebar-nav .nav-link.active {
        background-color: var(--sidebar-active);
        color: white;
        font-weight: 500;
    }

    .sidebar-nav .nav-link:before {
        content: "•";
        color: var(--sidebar-highlight);
        margin-right: 8px;
        font-size: 1.2rem;
        line-height: 0;
    }

    @media (max-width: 768px) {
        .sidebar-container {
            width: 240px;
            padding: 1rem 0.75rem;
        }
        
        .sidebar-header a {
            font-size: 1.1rem;
        }
        
        .sidebar-nav .nav-link {
            padding: 0.4rem 0.6rem;
        }
    }
</style>

<div class="sidebar-container">
    <div class="sidebar-header">
        <a href="index.php" class="d-flex align-items-center text-decoration-none">
            <span class="fs-4">Momento|Admin</span>
        </a>
    </div>
    <!-- <hr class="sidebar-divider"> -->
    <?php foreach ($sections as $title => $tables): ?>
        <h6 class="sidebar-section-title"><?= $title ?></h6>
        <ul class="nav nav-pills flex-column sidebar-nav">
            <?php foreach ($tables as $table): ?>
                <li class="nav-item">
                    <a href="manage.php?table=<?= $table ?>" class="nav-link text-white">
                        <?= ucfirst(str_replace('_', ' ', $table)) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>