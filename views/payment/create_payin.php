<?php
require 'database.php';
require 'vendor/autoload.php';

use SameerShelavale\PhpCountriesArray\CountriesArray;

// Get order ID from URL safely
$orderId = trim($_GET['order_id'] ?? '');

// Validate order ID
if (empty($orderId)) {
    die("Error: Order ID is missing.");
}

// Get order details from database
$orderDetails = $database->get("orders", ["order_user_id", "country_id", "order_total"], ["order_id" => $orderId]);

if (!$orderDetails) {
    die("Error: Order not found for Order ID: " . htmlspecialchars($orderId));
}

// Get country details
$countryDetails = $database->get("visa_countries", ["country_name"], ["country_id" => $orderDetails['country_id']]);
if (!$countryDetails) {
    die("Error: Country not found.");
}

// Get user details
$user = $database->get("users", ["email", "first_name", "last_name", "country_code", "mobile_number", "country_code_alpha", "country_name"], ["user_id" => $orderDetails['order_user_id']]);
if (!$user) {
    die("Error: User not found.");
}

// Convert country_name to country_code
$countryList = CountriesArray::get('name', 'alpha2');
$countryCode = strtoupper($user['country_code_alpha'] ?? $countryList[$user['country_name']] ?? 'sg');

// Define transaction data
$tempTrId = 'PAYMENT_' . $orderId . '_' . mt_rand(100000, 999999);

$test_data = [
    'primary_email' => $user['email'],
    'primary_country' => $countryCode,
    'primary_fname' => $user['first_name'],
    'primary_lname' => $user['last_name'],
    'primary_ccode' => $user['country_code'],
    'primary_phone' => $user['mobile_number'],
    'temp_trid' => $tempTrId,
    'package_name' => 'Visa Application for ' . $countryDetails['country_name'] . ' - ' . $orderId . ' - order by ' . $user['first_name'] . ' ' . $user['last_name'],
    'price' => $orderDetails['order_total'],
    'currency' => 'SGD'
];

// Insert into payments table
$insertResult = $database->insert("payments", [
    "user_id" => $orderDetails['order_user_id'],
    "order_id" => $orderId,
    "transaction_id" => $tempTrId,
]);

if (!$insertResult) {
    die('Error: Failed to insert payment record.');
}

// Define API credentials
$apiKey = 'ak_29JHR0T4J9A4YQHZ8X04';
$secretKey = 'sk_PtU6HhA9mXDYJcAQ9unSMfUT2eilZOdi9QrAgODAx6n9nW2HbJN3HBf3usUkWKqEvgmzaSJrACWde6RUGJHfRt9TSrqAb8fsDTL42qOQQa3iq9sWnQE5OWgZK7OUylEw';

// Encode API credentials
$userauth = base64_encode("$apiKey:$secretKey");

// Define API endpoint
$paymenturl = 'https://service.tazapay.com/v3/checkout';

// Define redirect URLs
$host = $_SERVER['HTTP_HOST'];
$isLocalhost = strpos($host, 'localhost') !== false;

$base_url = $isLocalhost ? "http://localhost/visa_f" : "https://fayyaztravels.com/visa";

$complete_url = "{$base_url}/payment/success?tr_id={$tempTrId}";
$callback_url = "{$base_url}/payment/webhook?tr_id={$tempTrId}";
$error_url   = "{$base_url}/payment/error?tr_id={$tempTrId}";


$requestBody = [
    'customer_details' => [
        'email' => $test_data['primary_email'],
        'country' => $test_data['primary_country'],
        'name' => "{$test_data['primary_fname']} {$test_data['primary_lname']}",
    ],
    'invoice_currency' => $test_data['currency'],
    'remove_payment_methods' => ["local_bank_transfer_sgd"],
    'amount' => $test_data['price'] * 100, //multiply by 100 to convert to cents (https://docs.tazapay.com/reference/create-checkout -> read amount params)
    'shipping_details' => [
        'name' => "{$test_data['primary_fname']} {$test_data['primary_lname']}",
        'address' => ['country' => $test_data['primary_country']],
        'phone' => [
            'calling_code' => $test_data['primary_ccode'],
            'number' => $test_data['primary_phone'],
        ]
    ],
    'billing_details' => [
        'name' => "{$test_data['primary_fname']} {$test_data['primary_lname']}",
        'address' => ['country' => $test_data['primary_country']],
        'phone' => [
            'calling_code' => $test_data['primary_ccode'],
            'number' => $test_data['primary_phone'],
        ]
    ],
    'seller_details' => [
        'name' => 'Fayyaz Travels Pte Ltd', // change this
        'email' => 'info@fayyaztravels.com', // change this
        'country' => 'SG', // or your seller country
    ],
    'reference_id' => $tempTrId,
    'transaction_description' => $test_data['package_name'],
    'success_url' => $complete_url,
    'webhook_url' => $callback_url,
    'cancel_url' => $error_url,
];

/*
echo '<div style="display: flex; justify-content: space-between;">';
echo '<div style="width: 45%;"><h4>Request Body 1</h4><pre>';
print_r($requestBody1);
echo '</pre></div>';

echo '<div style="width: 45%;"><h4>Request Body</h4><pre>';
print_r($requestBody);
echo '</pre></div>';
echo '</div>';
die();
*/

// Convert to JSON
$jsonPayload = json_encode($requestBody, JSON_PRETTY_PRINT);
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Error: JSON Encoding Failed - ' . json_last_error_msg());
}

// Initialize cURL request
$ch = curl_init($paymenturl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $jsonPayload,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Authorization: Basic ' . $userauth,
        'Content-Type: application/json',
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
]);

// Execute cURL request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors
if (curl_errno($ch)) {
    $error_message = curl_error($ch);
    curl_close($ch);
    die('Error: Tazapay API Request Failed - ' . $error_message);
}

// Close cURL session
curl_close($ch);

// Parse API response
$data = json_decode($response, true);

// Validate JSON decoding
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Error: JSON Decoding Failed - ' . json_last_error_msg());
}

// Debugging Output
echo 'HTTP Status Code: ' . $httpCode . PHP_EOL;
print_r($data);

// Handle API response
if (isset($data['status']) && $data['status'] === 'success' && isset($data['data']['url'])) {
    // save the transaction id in the database
    $tazapay_payment_id = $data['data']['id'];
    $database->update("payments", ["tazapay_payment_id" => $tazapay_payment_id], ["transaction_id" => $tempTrId]);
    header("Location: " . $data['data']['url']);
    exit();
} else {
    die('Error: Payment request failed. Response: ' . print_r($data, true));
}
