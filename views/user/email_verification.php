<?php

require 'database.php';

$emailVerificationCode = $_GET['code'] ?? null;

if ($emailVerificationCode) {
    // Use Medoo to select the user with the given email verification code
    $user = $database->get("users", "*", ["email_verification_code" => $emailVerificationCode]);

    if ($user) {
        // Update the user's email verification status
        $database->update("users", ["is_email_verified" => 1], ["id" => $user['id']]);

        echo "<div class='container'>
                <h1>Email Verification</h1>
                <p>Your email has been verified successfully.</p>
                <p>Redirecting to login page...</p>
              </div>";

        echo "<script>
                setTimeout(() => {
                    location.href = 'login?through=email_verification';
                }, 2000);
              </script>";
    } else {
        echo "<div class='container'>
                <h1>Email Verification</h1>
                <p>Invalid verification code. Please try again.</p>
              </div>";
    }
} else {
    echo "<div class='container'>
            <h1>Email Verification</h1>
            <p>No verification code provided.</p>
          </div>";
}
