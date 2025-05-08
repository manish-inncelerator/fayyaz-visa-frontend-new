<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../../database.php';
    require 'functions/validate_hu.php';

    header('Content-Type: application/json');

    // Retrieve headers safely across different servers
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        $hu = $headers['HU'] ?? $headers['Hu'] ?? $headers['hu'] ?? null;
        $orderId = $headers['X-Order-ID'] ?? $headers['X-Order-Id'] ?? null;
    } else {
        // Fallback for Nginx or other servers
        $hu = $_SERVER['HTTP_HU'] ?? null;
        $orderId = $_SERVER['HTTP_X_ORDER_ID'] ?? null;
    }

    // Read and decode JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validate input parameters
    $fileName = isset($data['file_name']) ? sanitizeInput($data['file_name']) : null;
    $travelerId = isset($data['traveler_id']) ? (int)$data['traveler_id'] : null; // Ensure it's an integer

    if (!$fileName || !$travelerId || !$orderId) {
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters.']);
        exit;
    }

    // Fetch document details
    $document = $database->get('documents', ['id', 'document_filename', 'traveler_id'], [
        'document_filename' => $fileName,
        'traveler_id'       => $travelerId,
        'order_id'          => $orderId
    ]);

    if (!$document) {
        echo json_encode(['status' => 'error', 'message' => 'Document not found.']);
        exit;
    }

    // Fetch traveler details
    $traveler = $database->get('travelers', ['name', 'order_id'], [
        'id' => $document['traveler_id'],
        'order_id' => $orderId
    ]);

    if (!$traveler) {
        echo json_encode(['status' => 'error', 'message' => 'Traveler not found.']);
        exit;
    }

    // Construct the correct file path safely
    $safeName = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $traveler['name']);
    $safeName = trim($safeName);
    $safeName = preg_replace('/[^a-zA-Z\s-]/', '', $safeName);
    $safeName = preg_replace('/[\s\-]+/', '-', $safeName);
    $safeName = strtolower($safeName);
    $safeName = implode('-', array_map('ucfirst', explode('-', $safeName)));

    // Construct the correct file path
    $baseDir = realpath(__DIR__ . "/../../user_uploads/{$traveler['order_id']}/{$safeName}/documents");

    // Debug the path issue
    // print("Base directory path: " . print_r($baseDir, true));
    // print("Traveler name: " . $traveler['name']);
    // print("Safe name: " . $safeName);
    // print("Order ID: " . $traveler['order_id']);
    // print("Document filename: " . $document['document_filename']);



    if (!$baseDir || strpos(realpath($baseDir), realpath(__DIR__ . "/../../user_uploads")) !== 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file path. Directory not found.']);
        exit;
    }





    // $filePath = "{$baseDir}/{$document['document_filename']}";
    $filePath = trim("{$baseDir}/{$document['document_filename']}");


    // Delete the file from storage if it exists
    if (file_exists($filePath)) {
        if (!unlink($filePath)) {
            error_log("Failed to delete file: $filePath");
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete file.']);
            exit;
        }
    }

    // Remove document entry from the database
    $delete = $database->delete('documents', [
        'traveler_id' => $travelerId,
        'document_filename' => $fileName,
        'order_id' => $orderId
    ]);


    if (!$delete) {
        error_log("Database deletion error: " . json_encode($database->error));
        respondWithJson(["error" => "Failed to delete photo entry from database"], 500);
        exit;
    }

    echo json_encode(['status' => 'success', 'message' => 'Document deleted successfully.']);
}
