<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = trim(stripslashes(strip_tags($data['email'])));
    $apiKey = '91c9ddae728a7b026e8806f04c07b350-us7';
    $listId = '0bc3313356';
    $dc = substr($apiKey, strpos($apiKey, '-') + 1); // Extract datacenter from API key

    $url = "https://$dc.api.mailchimp.com/3.0/lists/$listId/members/";
    $postData = json_encode([
        'email_address' => $email,
        'status' => 'subscribed',
        "merge_fields" => [
            "MMERGE4" => 'Website',
        ]
    ]);



    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode('user:' . $apiKey)
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo json_encode(['success' => false, 'message' => $error]);
    } else {
        echo $response;
    }
}
