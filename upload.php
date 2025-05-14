<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['data'])) {
    header('location:index.php');
    exit();
}
$data = $_SESSION['data'];
require('pdo.php');

$mainCategory = [
    'wars' => ['tank', 'soldier', 'battle', 'explosion', 'ruins', 'military', 'gun', 'destroyed', 'army','gaza','gore','bloody','dead'],
    'graduation' => ['graduation', 'cap', 'diploma', 'certificate', 'graduate', 'ceremony'],
    'wedding' => ['wedding', 'bride', 'groom', 'ring', 'ceremony', 'dress', 'cake'],
    'nature' => ['tree', 'mountain', 'river', 'lake', 'sunset', 'forest', 'nature'],
    'tourism' => ['tourism', 'travel', 'landmark', 'beach', 'resort', 'hotel'],
    'architecture' => ['building', 'architecture', 'bridge', 'tower', 'skyscraper', 'monument']
];

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (isset($_POST['upload']) && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    if (!in_array($fileExt, $allowedExtensions)) {
        $_SESSION['message'] = "Image not allowed: Invalid file type.";
        exit;
    }

    $filePath = $_FILES['file']['tmp_name'];
    $imageInfo = getimagesize($filePath);
    $cFile = curl_file_create($filePath, mime_content_type($filePath), $fileName);

    if ($imageInfo) {
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        if ($width < 1 || $height < 1) {
            $_SESSION['message'] = "Image not allowed : Image size too small.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    $chNsfw = curl_init("http://127.0.0.1:5000/classify/nsfw");
    curl_setopt_array($chNsfw, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['file' => $cFile],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $responseNsfw = curl_exec($chNsfw);
    curl_close($chNsfw);
    $resultNsfw = json_decode($responseNsfw, true);
    $classification = $resultNsfw['label'] ?? 'Unknown';

    if (strtolower($classification) === 'nsfw') {
        $_SESSION['message'] = "Image not allowed: NSFW content detected.";
        exit;
    }

    $chClip = curl_init("http://127.0.0.1:5000/classify/clip");
    curl_setopt_array($chClip, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['file' => $cFile],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $responseClip = curl_exec($chClip);
    curl_close($chClip);

    $resultClip = json_decode($responseClip, true);
    $topLabels = !empty($resultClip['clip_labels']) ? $resultClip['clip_labels'] : $resultClip['labels'];
    $isViolent = $resultClip['is_gory'] ?? false;
    $caption = $resultClip['caption'] ?? '';

    $uniqueId = uniqid('img_', true);
    $targetDir = "uploads/Images/";
    $newFileName = $uniqueId . '.' . $fileExt;
    $targetFilePath = $targetDir . $newFileName;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($filePath, $targetFilePath)) {
        $finalDescription = trim($_POST['description']) !== '' ? $_POST['description'] : $caption;

        try {
            $sql = $pdo->prepare('INSERT INTO images (user_id, url, title, file_name, description, label, is_sensitive) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $sql->execute([
                $data['id'],
                $targetFilePath,
                $_POST['title'],
                $newFileName,
                $finalDescription,
                json_encode($topLabels),
				$isViolent
            ]);
			
            $categoryCounts = [];

			foreach ($mainCategory as $category => $keywords) {
				foreach ($topLabels as $label) {
					if (in_array(strtolower($label), $keywords)) {
						$categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;
					}
				}
			}

			foreach ($categoryCounts as $category => $count) {
				$stmt = $pdo->prepare("INSERT INTO photographer_category_scores (photographer_id, category, score)
					VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score = score + VALUES(score)");
				$stmt->execute([$data['id'], $category, $count]);
			}
            $_SESSION['message'] = "Image Uploaded Successfully";
            $_SESSION['color'] = "green";
        } catch (Exception $e) {
            $_SESSION['message'] = "File upload failed.";
            exit;
        }
        header('Location: profile.php');
        exit;
    } else {
        $_SESSION['message'] = "File upload failed.";
    }
}
?>
