<?php

declare(strict_types=1);

session_start();

require '../../database.php';
require 'functions/validate_hu.php';
require '../../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

header('Content-Type: application/json');

class FileUploadHandler
{
    private $database;
    private $uuid;
    private $fileUuid;
    private $baseUploadPath;
    private $scheme;
    private $host;

    public function __construct($database)
    {
        $this->database = $database;
        $this->uuid = $_SESSION['uuid'] ?? null;
        $this->fileUuid = Uuid::uuid4()->toString();
        $this->baseUploadPath = __DIR__ . '/../../user_uploads';
        $this->scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $this->host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    }

    public function handleRequest(): void
    {
        try {
            $this->validateRequest();
            $this->processFileUpload();
        } catch (RuntimeException $e) {
            $this->respondWithError($e->getMessage(), $e->getCode());
        }
    }

    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 MB

    private function validateRequest(): void
    {
        $hu = $_SERVER['HTTP_HU'] ?? null;

        if (!isValidRequest($this->uuid, $hu, $this->host)) {
            throw new RuntimeException("Invalid request", 400);
        }

        if (!isHuValid($this->uuid, $hu)) {
            throw new RuntimeException("Invalid HU", 401);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
            throw new RuntimeException("Invalid request or missing file", 400);
        }

        if ($_FILES['file']['size'] > self::MAX_FILE_SIZE) {
            throw new RuntimeException("File size exceeds 10 MB limit", 413); // 413 Payload Too Large
        }
    }

    private function processFileUpload(): void
    {
        $personName = $this->sanitizePersonName($_SERVER['HTTP_X_PERSON_NAME'] ?? '');
        $orderId = $_SERVER['HTTP_X_ORDER_ID'] ?? '';
        $travelerId = $_SERVER['HTTP_X_TRAVELER_ID'] ?? '';

        if (!$orderId || !$personName || !$travelerId) {
            throw new RuntimeException('Missing required headers', 400);
        }

        $uploadDir = $this->prepareUploadDirectory($orderId, $personName);
        $filePath = $this->generateFilePath($uploadDir, $_FILES['file']);

        if (!$this->moveUploadedFile($_FILES['file']['tmp_name'], $filePath)) {
            $this->cleanupFailedUpload($uploadDir);
            throw new RuntimeException('Failed to move uploaded file', 500);
        }

        $photoUrl = $this->saveFileMetadataAndGetUrl(
            $orderId,
            $travelerId,
            basename($filePath)
        );

        $this->respondWithSuccess($photoUrl);
    }

    private function sanitizePersonName(string $name): string
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


    private function prepareUploadDirectory(string $orderId, string $personName): string
    {
        $uploadDir = "{$this->baseUploadPath}/{$orderId}/{$personName}";

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new RuntimeException('Failed to create upload directory', 500);
            }
        } elseif ($this->hasExistingFiles($uploadDir)) {
            throw new RuntimeException('Only one image is allowed per person', 400);
        }

        return $uploadDir;
    }

    private function hasExistingFiles(string $uploadDir): bool
    {
        return (bool) glob("{$uploadDir}/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
    }

    private function generateFilePath(string $uploadDir, array $file): string
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        $mimeType = mime_content_type($file['tmp_name']);

        if (!in_array($extension, $allowedExtensions, true) || !in_array($mimeType, $allowedMimeTypes, true)) {
            throw new RuntimeException('Invalid file type. Allowed: JPG, PNG, GIF, WEBP', 400);
        }

        return "{$uploadDir}/{$this->fileUuid}_photo.{$extension}";
    }

    private function moveUploadedFile(string $tmpPath, string $destinationPath): bool
    {
        return move_uploaded_file($tmpPath, $destinationPath);
    }

    private function cleanupFailedUpload(string $uploadDir): void
    {
        if (is_dir($uploadDir) && count(scandir($uploadDir)) <= 2) {
            @rmdir($uploadDir);
        }
    }

    private function saveFileMetadataAndGetUrl(string $orderId, string $travelerId, string $filename): string
    {
        $this->database->insert('photos', [
            'uploaded_by_user_id' => $this->uuid,
            'order_id' => $orderId,
            'traveler_id' => $travelerId,
            'photo_filename' => $filename,
            'upload_date' => date('Y-m-d H:i:s'),
            'is_finished' => 1
        ]);

        if (!$this->database->id()) {
            throw new RuntimeException('Database insertion failed', 500);
        }

        return "{$this->scheme}://{$this->host}/visa/user_uploads/{$orderId}/" .
            urlencode(basename(dirname($filename))) . "/{$filename}";
    }

    private function respondWithSuccess(string $photoUrl): void
    {
        echo json_encode([
            'success' => true,
            'message' => 'Photo uploaded successfully',
            'photo_url' => $photoUrl
        ]);
    }

    private function respondWithError(string $message, int $code): void
    {
        http_response_code($code);
        echo json_encode(['error' => $message]);
    }
}

// Instantiate and run the handler
try {
    $handler = new FileUploadHandler($database);
    $handler->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred']);
}
