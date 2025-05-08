<?php

// Start session
session_start();

// Include database and sendMail
include('../../database.php');
include('../../sendMail.php');

// Ensure that the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
    exit;
}

// Retrieve and sanitize POST data
$traveller_count = isset($_POST['traveller_count']) ? filter_var($_POST['traveller_count'], FILTER_SANITIZE_NUMBER_INT) : 1;
$country_id = isset($_POST['country_id']) ? htmlspecialchars($_POST['country_id'], ENT_QUOTES, 'UTF-8') : 1;
$journey_date = isset($_POST['date_of_journey']) ? htmlspecialchars($_POST['date_of_journey'], ENT_QUOTES, 'UTF-8') : '';
$date_of_arrival = isset($_POST['date_of_arrival']) ? htmlspecialchars($_POST['date_of_arrival'], ENT_QUOTES, 'UTF-8') : '';
$pricing_id = isset($_POST['pricing_id']) ? filter_var($_POST['pricing_id'], FILTER_SANITIZE_NUMBER_INT) : '';

// Ensure dates are valid
if (empty($journey_date) || empty($date_of_arrival)) {
    http_response_code(400); // Bad Request
    echo "Invalid journey or arrival dates.";
    exit;
}

// Validate the journey_date and date_of_arrival format (Y-m-d)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $journey_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_of_arrival)) {
    http_response_code(400); // Bad Request
    echo "Invalid date format.";
    exit;
}

// Validate pricing_id to prevent SQL injection
$whereClause = ['country_id' => $country_id]; // Always filter by country_id

if (!empty($pricing_id) && $pricing_id != '0') {
    $whereClause['id'] = $pricing_id; // Add pricing_id filter only if it's not 0
}

// Fetch details from the database
$fees = $database->get(
    'visa_pricing',
    [
        'visa_assistance_fee',
        'embassy_fee',
        'vfs_service_fee',
        'single_entry_fee',
        'multiple_entry_fee',
        'visa_on_arrival_fee'
    ],
    $whereClause
);

if (!$fees) {
    http_response_code(404); // Not Found
    echo "Country not found.";
    exit;
}

// Extract fee details
$embassy_fee = $fees['embassy_fee'];
$our_fee = $fees['visa_assistance_fee'];
$vfs_fee = $fees['vfs_service_fee'];
$single_entry_fee = $fees['single_entry_fee'];
$multiple_entry_fee = $fees['multiple_entry_fee'];
$visa_on_arrival_fee = $fees['visa_on_arrival_fee'];

// Calculate total amount
$total_amount = ($embassy_fee + $our_fee + $vfs_fee + $single_entry_fee + $multiple_entry_fee + $visa_on_arrival_fee) * $traveller_count;

// Generate order_id
$order_id = strtoupper(date('Ymd') . "-" . substr(bin2hex(random_bytes(4)), 0, 8));

// Insert into orders table
$insert_status = $database->insert('orders', [
    'country_id' => $country_id,
    'order_id' => $order_id,
    'no_of_pax' => $traveller_count,
    'order_total' => $total_amount,
    'order_date' => date('Y-m-d H:i:s'),
    'is_ordered' => 1,
    'journey_date_departure' => $journey_date,
    'journey_date_arrival' => $date_of_arrival
]);

if ($insert_status) {
    // Retrieve user email from session (ensure it's set)
    $email = $_SESSION['email'] ?? null;

    if ($email) {
        // Send email notification if email exists
        sendEmail($email, "Order #$order_id Created", 'default', 'Hi, your order has been successfully created. Please login to continue.', true, "https://fayyaztravels.com/visa/auth/login?o=$order_id");
    } else {
        // Handle case if email is not available in session (e.g., guest user)
        // Optionally, log the order without sending the email.
        error_log("No email found for order: $order_id");
    }

    // Redirect based on user session
    if (isset($_SESSION['user_id'])) {
        header('location: ../../application/' . $order_id . '/persona?through=login');
    } else {
        header('location: ../../auth/login?o=' . $order_id);
    }
    exit;
} else {
    http_response_code(500); // Internal Server Error
    echo "Failed to create order.";
}
