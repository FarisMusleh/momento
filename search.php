<?php
require('pdo.php');
// The search query from the user
if (isset($_GET['query'])) {
	$search_query = $_GET['query'] ?? '';

	// Flask server URL (adjust based on where your Flask app is running)
	$flask_url = 'http://127.0.0.1:5000/search?query=' . urlencode($search_query);

	// Initialize cURL session
	$ch = curl_init();

	// Set cURL options
	curl_setopt($ch, CURLOPT_URL, $flask_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // To return the response as a string
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow any redirects if needed

	// Execute cURL request and store the response
	$response = curl_exec($ch);

	// Check for errors
	if($response === false) {
		echo "cURL Error: " . curl_error($ch);
		exit();
	}

	// Decode the JSON response
	$response_data = json_decode($response, true);

	// Close cURL session
	curl_close($ch);

	// Display results
	if (isset($response_data['similar_categories'])) {
		$sql = "SELECT * FROM images WHERE label LIKE :search";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':search', '%' .$response_data['similar_categories'][0]. '%', PDO::PARAM_STR);
		$stmt->execute();
	 if ($stmt->rowCount() > 0) {

        // Loop through the results and display the image URLs
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<img src='" . $row['image_url'] . "' alt='Image' style='width:200px;'><br>";
        }	
	} else {
		echo "Error: " . $response_data['error'];
	}
}
}
?>