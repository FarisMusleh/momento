<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../pdo.php';

$data = $_SESSION['data'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['data']['id'])) {
    $userId = $_SESSION['data']['id'];

    $stmt = $pdo->prepare("SELECT api_key FROM api_keys WHERE user_id = ? AND is_active = 1");
    $stmt->execute([$userId]);
    $existing = $stmt->fetch();

    if ($existing) {
        echo "You already have an active API key: <code>{$existing['api_key']}</code>";
    } else {
        $apiKey = bin2hex(random_bytes(32));
        $isActive = 1;

        $check = $pdo->prepare("SELECT id FROM api_keys WHERE user_id = ?");
        $check->execute([$userId]);

        if ($check->fetch()) {
            $update = $pdo->prepare("UPDATE api_keys SET api_key = ?, is_active = ?, created_at = NOW() WHERE user_id = ?");
            $update->execute([$apiKey, $isActive, $userId]);
        } else {
            $insert = $pdo->prepare("INSERT INTO api_keys (api_key, user_id, is_active, created_at) VALUES (?, ?, ?, NOW())");
            $insert->execute([$apiKey, $userId, $isActive]);
        }

        echo "Your new API key: <code>$apiKey</code>";
    }
    exit;
}
?>