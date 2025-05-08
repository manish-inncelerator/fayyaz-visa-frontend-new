<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: google.php');
    exit();
}

$user = $_SESSION['user'];

// Path to save the image
$imagePath = 'assets/images/dp/' . basename($user['picture']);

// Check if the image is already saved to avoid re-downloading
if (!file_exists($imagePath)) {
    // Fetch the image from the URL
    $imageData = file_get_contents($user['picture']);

    // Save the image to the local server
    file_put_contents($imagePath, $imageData);
}

// Set the path to the saved image
$savedImagePath = $imagePath;
?>


<h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
<p><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']) ?></p>
<p><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
<p><strong>Verified Email:</strong> <?= htmlspecialchars($user['verified_email']) ?></p>
<p><strong>Locale:</strong> <?= htmlspecialchars($user['locale']) ?></p>
<p><strong>Hosted Domain:</strong> <?= htmlspecialchars($user['hosted_domain'] ?? 'N/A') ?></p>
<img src="<?= htmlspecialchars($savedImagePath) ?>" alt="Profile Picture">
<a href="logout.php">Logout</a>