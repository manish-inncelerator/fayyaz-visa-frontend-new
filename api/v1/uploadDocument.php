<?php

session_start();

require '../../database.php';
require 'functions/validate_hu.php';
require '../../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

header('Content-Type: application/json');

// Fetch user session data
$uuid = $_SESSION['uuid'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

// Fetch headers (Nginx compatible)
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$hu = $headers['hu'] ?? ($_SERVER['HTTP_HU'] ?? null);
$fileUuid = Uuid::uuid4()->toString();

// Validate request
if (!isValidRequest($uuid, $hu, $_SERVER['HTTP_HOST']) || !isHuValid($uuid, $hu)) {
    respondWithJson(["status" => "error", "status_code" => 401, "message" => "Invalid request"], 401);
}

// Ensure POST request and file upload
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file']) || !isset($_FILES['file']['tmp_name']) || $_FILES['file']['tmp_name'] === '') {
    respondWithJson(["status" => "error", "status_code" => 400, "message" => "Invalid request or missing file. Please upload again."], 400);
}

// ✅ File size check (10 MB)
$maxSize = 10 * 1024 * 1024; // 10 MB
if ($_FILES['file']['size'] > $maxSize) {
    respondWithJson([
        "status" => "error",
        "status_code" => 400,
        "message" => "File is too large. Max size allowed is 10MB."
    ], 400);
}

// Allowed file types
$allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

// Get file extension safely
$fileInfo = pathinfo($_FILES['file']['name']);
$extension = strtolower($fileInfo['extension'] ?? '');

// Ensure file exists before checking MIME type
if (!file_exists($_FILES['file']['tmp_name'])) {
    respondWithJson(["status" => "error", "status_code" => 400, "message" => "File upload error or missing file. Please upload again."], 400);
}

$mimeType = mime_content_type($_FILES['file']['tmp_name']);

// Validate file type
if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
    respondWithJson([
        "status" => "error",
        "status_code" => 400,
        "message" => "Invalid file type. Allowed: JPG, PNG, PDF, WEBP."
    ], 400);
}

// Extract headers (Nginx-compatible)
$order_id = $_SERVER['HTTP_X_ORDER_ID'] ?? 'unknown_order';
$person_name = $_SERVER['HTTP_X_PERSON_NAME'] ?? 'unknown_person';

// Sanitize person name using the same logic as uploadPhoto.php
$person_name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $person_name);
$person_name = trim($person_name);
$person_name = preg_replace('/[^a-zA-Z\s-]/', '', $person_name);
$person_name = preg_replace('/[\s\-]+/', '-', $person_name);
$person_name = strtolower($person_name);
$person_name_dir = implode('-', array_map('ucfirst', explode('-', $person_name)));

$doc_id = $_SERVER['HTTP_X_DOCUMENT_ID'] ?? 'unknown_doc';
$travelerId = $_SERVER['HTTP_X_TRAVELER_ID'] ?? '';

// Get document name from database
$requiredDoc = $database->get('required_documents', 'required_document_name', ['id' => $doc_id]);
if (!$requiredDoc) {
    respondWithJson(["status" => "error", "status_code" => 400, "message" => "Invalid document type."], 400);
}

$requiredDocName = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $requiredDoc));
$cleanRequiredDocName = str_replace('_', ' ', trim($requiredDocName, '_'));

// Check if the document already exists for this traveler
$existingDocument = $database->get('documents', 'document_filename', [
    'traveler_id' => $travelerId,
    'order_id' => $order_id,
    'document_filename[~]' => "{$cleanRequiredDocName}."
]);

if ($existingDocument) {
    respondWithJson([
        "status" => "error",
        "status_code" => 409,
        "message" => ucfirst(str_replace('_', ' ', $requiredDocName)) . " already uploaded for this traveler."
    ], 409);
}

// Define upload directories
$baseUploadDir = realpath("../../user_uploads") . "/{$order_id}/{$person_name_dir}/";
$uploadDir = "{$baseUploadDir}documents/";

// Ensure directories exist securely
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
    respondWithJson(["status" => "error", "status_code" => 500, "message" => "Failed to create upload directories."], 500);
}

// Generate secure filename
$fileName = "{$fileUuid}_{$requiredDocName}.{$extension}";
$filePath = "{$uploadDir}{$fileName}";

// Move uploaded file securely
if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
    respondWithJson(["status" => "error", "status_code" => 500, "message" => "Upload failed."], 500);
}

// Save file info to database
$dbResult = $database->insert('documents', [
    'uploaded_by_user_id' => $userId,
    'order_id'            => $order_id,
    'traveler_id'         => $travelerId,
    'document_type'       => $cleanRequiredDocName,
    'document_filename'   => $fileName,
    'upload_date'         => date('Y-m-d H:i:s'),
    'is_finished'         => 1
]);

if (!$database->id()) {
    respondWithJson(["status" => "error", "status_code" => 500, "message" => "Database insertion failed."], 500);
}

// Success response
respondWithJson([
    "status" => "success",
    "status_code" => 200,
    "document_name" => $fileName,
    "message" => ucfirst(str_replace('_', ' ', $requiredDocName)) . " uploaded successfully",
    "document_url" => str_replace(realpath("../../"), "", $filePath) // Relative path
]);
