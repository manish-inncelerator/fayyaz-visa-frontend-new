<?php
$apiUrl = "https://fayyaztravels.com/api/email"; // Change to the correct URL of your API script

$payload = json_encode([
    "to" => "mshahi.biz@gmail.com",
    "subject" => "Test Email",
    "body" => "<p><b>Name: </b>Test User</p><br>
<p><b>Email: </b>test@gmail.com</p>"
]);

$headers = [
    "Content-Type: application/json",
    "Authorization: af408c18-c207-4e9e-b958-0941bd7848c7"
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($httpCode === 200) {
    echo "Email sent successfully: " . $response;
} else {
    echo "Error: " . $curlError . "\nResponse: " . $response;
}
