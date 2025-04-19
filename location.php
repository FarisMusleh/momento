<?php
session_start();
require('pdo.php');

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lat']) && isset($_POST['lon'])) {
    header('Content-Type: application/json');

    $userId = $_SESSION['id'];  // Use 'id' instead of 'user_id'
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];

    // Get account type
    $stmt = $pdo->prepare("SELECT account_type FROM accounts WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['account_type'] !== 'business') {
        echo json_encode(['account_type' => $user['account_type'] ?? 'none']);
        exit;
    }

    // Get all other users' coordinates
    $stmt = $pdo->prepare("SELECT name, lat, lon FROM accounts WHERE id != ? AND lat IS NOT NULL AND lon IS NOT NULL");
    $stmt->execute([$userId]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'account_type' => 'business',
        'users' => $users
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html>
<body>
  <h1>Business User: Nearest People</h1>
  <p>Click the button to find nearby people (if you're a business account).</p>

  <button onclick="getLocation()">Find Nearby</button>

  <p id="demo"></p>

  <script>
    const x = document.getElementById("demo");

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
      } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
      }
    }

    async function success(position) {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;

      const formData = new FormData();
      formData.append('lat', lat);
      formData.append('lon', lon);

      const res = await fetch('', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();

      if (data.account_type !== 'business') {
        x.innerHTML = "Only business accounts can view nearby people.";
        return;
      }

      // Calculate distances
      const distances = data.users.map(user => {
        const dist = haversine(lat, lon, parseFloat(user.lat), parseFloat(user.lon));
        return { ...user, distance: dist };
      });

      distances.sort((a, b) => a.distance - b.distance);

      let output = `<strong>Nearest Users:</strong><br>`;
      distances.slice(0, 5).forEach(user => {
        output += `Name: ${user.name}, Distance: ${user.distance.toFixed(2)} km<br>`;
      });

      x.innerHTML = output;
    }

    function error() {
      alert("Unable to retrieve location.");
    }

    function haversine(lat1, lon1, lat2, lon2) {
      const R = 6371;
      const dLat = toRad(lat2 - lat1);
      const dLon = toRad(lon2 - lon1);
      const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                Math.sin(dLon / 2) ** 2;
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      return R * c;
    }

    function toRad(deg) {
      return deg * Math.PI / 180;
    }
  </script>
</body>
</html>
