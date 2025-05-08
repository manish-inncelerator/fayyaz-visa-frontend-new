<?php

// Include the ImageProcessor class (make sure the class file is included)
require_once 'imgClass.php';

// Get parameters from the URL and sanitize inputs
$width = isset($_GET['width']) ? $_GET['width'] : 'auto';
$originalImagePath = isset($_GET['image']) ? $_GET['image'] : ''; // Default to empty if not provided
$height = isset($_GET['height']) ? $_GET['height'] : 'auto';
$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'jpg';
$quality = isset($_GET['quality']) ? (int)$_GET['quality'] : 80;

// Validate the parameters
$validFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

// Ensure the format is valid
if (!in_array($format, $validFormats)) {
    $format = 'jpg'; // Default to 'jpg' if the format is invalid
}

// Ensure width and height are either 'auto' or numeric
if ($width !== 'auto' && !is_numeric($width)) {
    die('Invalid width parameter');
}

if ($height !== 'auto' && !is_numeric($height)) {
    die('Invalid height parameter');
}

// Ensure quality is between 0 and 100
$quality = max(0, min(100, $quality));

// Validate the image path
if (empty($originalImagePath)) {
    die('Image path is required');
}

// Sanitize the image path to prevent directory traversal
$originalImagePath = realpath($originalImagePath);

// Check if the file exists and is an image
if (!$originalImagePath || !file_exists($originalImagePath) || !getimagesize($originalImagePath)) {
    die('Original image not found or invalid');
}

try {
    // Create an instance of the ImageProcessor class
    $imageProcessor = new ImageProcessor($originalImagePath);

    // Process the image and get the cached image path
    $processedImage = $imageProcessor->processImage($width, $height, $format, $quality);

    // Output the processed image with the correct Content-Type header based on the format
    switch ($format) {
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'gif':
            header('Content-Type: image/gif');
            break;
        case 'webp':
            header('Content-Type: image/webp');
            break;
        case 'avif':
            header('Content-Type: image/avif');
            break;
    }

    // Output the image file securely
    readfile($processedImage);
} catch (Exception $e) {
    // Log the error for debugging purposes (do not display sensitive info to users)
    error_log($e->getMessage());

    // Return a generic error message to the user
    header('HTTP/1.1 500 Internal Server Error');
    echo 'An error occurred while processing the image. Please try again later.';
}
