<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// connect to database
require_once '../../database.php';
require_once '../../sendMail.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Log the received data for debugging
error_log('Received data: ' . print_r($data, true));

// Validate required fields
$required_fields = ['name', 'email', 'company', 'message'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Missing required fields',
        'fields' => $missing_fields
    ]);
    exit;
}

// Validate email format
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

// Extract email domain
$email_domain = substr(strrchr($data['email'], "@"), 1);

// List of free email providers
$free_email_providers = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com', 'aol.com', 'zoho.com', 'protonmail.com'];

if (in_array($email_domain, $free_email_providers)) {
    http_response_code(400);
    echo json_encode(['error' => 'Free email addresses are not allowed']);
    exit;
}

// Extract company domain from input (removes 'www.' and protocol)
$company_domain = strtolower(trim($data['company']));
$company_domain = preg_replace('/^www\./', '', parse_url($company_domain, PHP_URL_HOST) ?: $company_domain);

// Ensure the company domain exactly matches the email domain
if ($email_domain !== $company_domain) {
    http_response_code(400);
    echo json_encode(['error' => 'Email domain must exactly match company domain']);
    exit;
}

try {
    // Insert data into the database
    $database->insert('consultation_requests', [
        'name' => $data['name'],
        'email' => $data['email'],
        'company' => $data['company'],
        'message' => $data['message'],
        'created_at' => date('Y-m-d H:i:s')
    ]);

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Consultation request received successfully',
        'data' => [
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'],
            'message' => $data['message']
        ]
    ]);

    // Send email to the user
    sendEmail($data['email'], 'Consultation Request Received', 'default', 'Thank you for your request. We will get back to you as soon as possible.', true, 'https://www.fayyaztravels.com');
} catch (Exception $e) {
    error_log('Error in corporate-visa.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ]);
}
