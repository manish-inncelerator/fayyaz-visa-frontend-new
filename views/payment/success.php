<?php
session_start();

require 'database.php';
require 'sendMail.php';

// if (!isset($_SESSION['user_id'])) {
//     header("Location: /login");
//     exit();
// }

$tr_id = trim(strip_tags($_GET['tr_id']));

if (empty($tr_id)) {
    die("Invalid transaction ID");
}

// get payment details
$payment_details = $database->get("payments", "*", ["transaction_id" => $tr_id]);

// order id
$orderId = $payment_details['order_id'];

// country id
$country_id = $database->get("orders", "country_id", ["order_id" => $orderId]);

// get country name
$country_name = $database->get("visa_countries", "country_name", ["country_id" => $country_id]);

// get user name
$user_name = $database->get("users", "first_name", ["user_id" => $payment_details['user_id']]);

// echo $country_id;
// echo $country_name;

// die();

if (!$payment_details) {
    die("Transaction not found.");
}

$userEmail = $database->get("users", "email", ["user_id" => $payment_details['user_id']]);

// Ensure email is valid
if (!$userEmail) {
    die("User email not found.");
}

// Update orders table
$update_order = $database->update("orders", ["is_paid" => '1', "is_finished" => '1'], ["order_id" => $payment_details['order_id']]);

// Update payments table
$update_payment = $database->update("payments", ["is_successful" => '1'], ["transaction_id" => $tr_id]);

if ($update_order && $update_payment) {
    // echo "Payment status updated successfully.";
    // sendEmail($userEmail, "Payment Successful - Order # " . $payment_details['order_id'], "default", "Your payment for Order # " . $payment_details['order_id'] . " has been successful. Thank you for your order. We will process your order soon.");

    // save invoice data in database
    $invoice = $database->insert("invoices", [
        "order_id" => $orderId,
        "invoice_creation_date" => date("Y-m-d H:i:s")
    ]);

    if ($invoice) {

        // Send email to user
        $emailSent = sendEmail(
            $userEmail,
            "Order #$orderId Payment Successful",
            'default',
            'Hi ' . $user_name . '! Your order #' . $orderId . ' has been paid successfully. We\'ll start processing it shortly, and you\'ll receive more details soon.',
            true,
            "https://fayyaztravels.com/visa/applications"
        );

        // Send email to admin and visa team
        // Define your recipients (array of email addresses)
        $recipients = ($country_name != "Singapore") ?
            ['info@fayyaztravels.com', 'visa@fayyaztravels.com'] :
            ['info@fayyaztravels.com', 'sales@fayyaztravels.com'];

        // Define CC recipients
        $ccRecipients = ['accounts@fayyaztravels.com', 'muhammad@fayyaztravesl.com'];

        // Define email subject
        $subject = "New Order #$orderId for $country_name – Visa Application Paid, Processing To Be Started";

        // Define email content (HTML)
        $email_body = '<h2>Hi Team 👋</h2>
            <p>Great news! A new visa order <strong>#' . $orderId . '</strong> from ' . $user_name . ' for ' . $country_name . ' has been paid successfully and is now ready for processing.</p>
            <p>Please log in to the <b>admin panel</b> to begin processing the application at your earliest convenience.</p>
            <p>Let\'s keep things moving smoothly! 🚀</p>';

        // Optional button configuration
        $isBtn = true; // Set to true to include a button
        $btnUrl = "https://fayyaztravels.com/visa/admin/login.html"; // URL for the button

        // Send the batch emails with CC
        $results = sendBatchEmails($recipients, $subject, 'default', $email_body, $isBtn, $btnUrl, $ccRecipients);

        if (!$emailSent) {
            echo "There was an error sending the email. But anyway your payment was successful and your order is being processed. You will receive further details soon. Also, you may have received an email with the payment details on your registered email address from Tazapay. Our payment processing partner.";
        }
    } else {
        echo "There was an error sending the email. But anyway your payment was successful and your order is being processed. You will receive further details soon. Also, you may have received an email with the payment details on your registered email address from Tazapay. Our payment processing partner.";
    }
} else {
    // echo "Failed to update payment status.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful | Fayyaz Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --success: #10b981;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
        }

        body {
            background: linear-gradient(135deg, #f0f4ff 0%, #ebf5ff 100%);
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            padding: 1rem;
        }

        .success-container {
            max-width: 500px;
            width: 100%;
        }

        .success-card {
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06);
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .success-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--success));
        }

        .company-logo {
            width: 100px;
            margin-bottom: 2rem;
        }

        .success-icon-wrapper {
            width: 90px;
            height: 90px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .success-icon {
            color: var(--success);
            font-size: 2.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .success-title {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary), var(--success));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .success-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .transaction-card {
            background: var(--light-bg);
            border-radius: 16px;
            padding: 1.2rem;
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .transaction-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .transaction-value {
            font-weight: 600;
            font-size: 1rem;
            background: linear-gradient(90deg, var(--primary), var(--success));
            padding: 0.5rem 1rem;
            border-radius: 20px;
            color: white;
        }

        .notification-box {
            display: flex;
            align-items: center;
            background: rgba(67, 97, 238, 0.08);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .notification-icon {
            background: rgba(67, 97, 238, 0.15);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .notification-icon i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .notification-text {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--primary), #6366f1);
            border: none;
            border-radius: 50px;
            padding: 0.9rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.3px;
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.25);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(67, 97, 238, 0.3);
            background: linear-gradient(90deg, #3b56e4, #5657f0);
        }

        .contact-info {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .contact-info a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .contact-info a:hover {
            color: #3b56e4;
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: radial-gradient(circle, rgba(226, 232, 240, 1) 0%, rgba(248, 250, 252, 0) 100%);
            margin: 1.5rem 0;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .success-card {
                padding: 2rem 1.5rem;
            }

            .transaction-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .transaction-value {
                margin-top: 0.5rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="success-container">
        <div class="success-card">
            <img src="../assets/images/main-logo.png" alt="Fayyaz Travels Logo" class="company-logo">

            <div class="success-icon-wrapper">
                <i class="bi bi-check-lg success-icon"></i>
            </div>

            <h1 class="success-title">Payment Successful</h1>
            <p class="success-subtitle">Your transaction has been completed</p>

            <div class="transaction-card">
                <span class="transaction-label">Transaction ID</span>
                <span class="transaction-value"><?= $tr_id ?></span>
            </div>

            <div class="notification-box">
                <div class="notification-icon">
                    <i class="bi bi-envelope-check"></i>
                </div>
                <div class="notification-text">
                    A confirmation email has been sent to your registered email address. Please check your inbox (and spam folder).
                </div>
            </div>

            <div class="notification-box">
                <div class="notification-icon">
                    <i class="bi bi-telephone"></i>
                </div>
                <div class="notification-text">
                    Our expert visa team will contact you within 24 hours to discuss the next steps.
                </div>
            </div>

            <a href="/visa/" class="btn btn-primary">
                <i class="bi bi-house-door me-2"></i>Return to Home
            </a>

            <div class="divider"></div>

            <p class="contact-info">
                Questions? Contact us at <a href="mailto:info@fayyaztravels.com">info@fayyaztravels.com</a>
            </p>
        </div>
    </div>

    <!-- Deferred loading of JavaScript -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script> -->
</body>

</html>