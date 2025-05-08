<?php

declare(strict_types=1);
session_start();

require '../../database.php'; // This must return a Medoo instance as $database
require 'functions/validate_hu.php';
require '../../vendor/autoload.php';

use Ramsey\Uuid\Uuid;
use Medoo\Medoo;

header('Content-Type: application/json');

// Session and request headers
$uuid        = $_SESSION['uuid'] ?? null;
$fileUuid    = Uuid::uuid4()->toString();
$scheme      = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host        = parse_url($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);
$hu          = $_SERVER['HTTP_HU'] ?? null;
$personName  = sanitizePersonName($_SERVER['HTTP_X_PERSON_NAME'] ?? '');
$orderId     = sanitizeFileName($_SERVER['HTTP_X_ORDER_ID'] ?? '');
$travelerId  = $_SERVER['HTTP_X_TRAVELER_ID'] ?? '';

// Validation
if (!isValidRequest($uuid, $hu, $host)) {
    respondWithJson(["error" => "Invalid request"], 400);
}
if (!isHuValid($uuid, $hu)) {
    respondWithJson(["error" => "Invalid HU"], 401);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    respondWithJson(["error" => "Invalid request or missing file"], 400);
}

// Process upload
handleFileUpload($uuid, $orderId, $travelerId, $personName, $fileUuid, $database);

/**
 * Handles the uploaded passport file.
 */
function handleFileUpload(
    string $uuid,
    string $orderId,
    string $travelerId,
    string $personName,
    string $fileUuid,
    Medoo $database
): void {
    $uploadBase = realpath(__DIR__ . '/../../user_uploads');
    $uploadDir  = $uploadBase . "/$orderId/$personName/passport";

    if (hasExistingFiles($uploadDir)) {
        respondWithJson(["error" => "Passport has already been uploaded."], 400);
    }

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            respondWithJson(["error" => "Failed to create upload directory"], 500);
        }
    }

    $uploadedFile = $_FILES['file'];

    // ✅ File size restriction (10 MB)
    $maxSize = 10 * 1024 * 1024; // 10 MB
    if ($uploadedFile['size'] > $maxSize) {
        respondWithJson(["error" => "File size exceeds 10MB limit."], 400);
    }

    $extension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $allowedExtensions, true)) {
        respondWithJson(["error" => "Invalid file type. Only JPG, PNG, or WEBP allowed."], 400);
    }

    $photoFilename = sanitizeFileName($fileUuid . '_passport.' . $extension);
    $filePath = $uploadDir . DIRECTORY_SEPARATOR . $photoFilename;

    if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
        respondWithJson(["error" => "Failed to move uploaded file"], 500);
    }

    // Insert record
    $database->insert('passports', [
        'uploaded_by_user_id' => $uuid,
        'order_id'            => $orderId,
        'traveler_id'         => $travelerId,
        'passport_filename'   => basename($filePath),
        'upload_date'         => date('Y-m-d H:i:s'),
        'is_finished'         => 1
    ]);

    if (!$database->id()) {
        @unlink($filePath);
        if (is_dir($uploadDir) && count(scandir($uploadDir)) <= 2) {
            @rmdir($uploadDir);
        }
        respondWithJson(['error' => 'Database insertion failed'], 500);
    }

    $relativePath = "/visa/user_uploads/$orderId/$personName/passport/$photoFilename";
    $photoUrl = buildBaseUrl() . $relativePath;

    respondWithJson([
        'success'   => true,
        'message'   => 'Passport uploaded successfully',
        'photo_url' => $photoUrl
    ], 200);
}

/**
 * Checks if a directory contains any image files.
 */
function hasExistingFiles(string $uploadDir): bool
{
    if (!is_dir($uploadDir)) {
        return false;
    }
    return (bool) glob("$uploadDir/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
}

/**
 * Sanitizes filenames or folder names for safe use in filesystem paths.
 */
function sanitizeFileName(string $name): string
{
    return preg_replace('/[^a-zA-Z0-9_\-\.]/', '-', trim($name));
}

/**
 * Sanitizes person names for safe use in filesystem paths.
 */
function sanitizePersonName(string $name): string
{
    // Normalize unicode characters and remove special ones (smart quotes, emojis)
    $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);

    // Trim whitespace
    $name = trim($name);

    // Remove everything except letters, spaces, and hyphens
    $name = preg_replace('/[^a-zA-Z\s-]/', '', $name);

    // Convert multiple spaces to single hyphen
    $name = preg_replace('/[\s\-]+/', '-', $name);

    // Lowercase then capitalise each word
    $name = strtolower($name);
    return implode('-', array_map('ucfirst', explode('-', $name)));
}

/**
 * Builds the base URL of the current request.
 */
function buildBaseUrl(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return "$scheme://$host/";
}
