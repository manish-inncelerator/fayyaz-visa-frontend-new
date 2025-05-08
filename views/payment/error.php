<?php
session_start();

require 'database.php';
require 'sendMail.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit();
}

$tr_id = trim(strip_tags($_GET['tr_id']));

if (empty($tr_id)) {
    die("Invalid transaction ID");
}

$payment_details = $database->get("payments", "*", ["transaction_id" => $tr_id]);

if (!$payment_details) {
    die("Transaction not found.");
}

$orderId = $payment_details['order_id'];

$userEmail = $database->get("users", "email", ["user_id" => $payment_details['user_id']]);

// Ensure email is valid
if (!$userEmail) {
    die("User email not found.");
}

// Update orders table
$update_order = $database->update("orders", ["is_payment_failed" => '1'], ["order_id" => $payment_details['order_id']]);

// Update payments table
$update_payment = $database->update("payments", ["is_failed" => '1'], ["transaction_id" => $tr_id]);

if ($update_order && $update_payment) {
    // echo "Payment status updated successfully.";
    $emailSent = sendEmail(
        $userEmail,
        "Order #$orderId Payment Failed",
        'default',
        'Hi! Your order #' . $orderId . ' payment was unsuccessful.',
        true,
        "https://fayyaztravels.com/visa/applications"
    );
    if (!$emailSent) {
        echo "There was an error sending the email. FYI, your payment was unsuccessful. Also, you may have received an email with the payment details on your registered email address from Tazapay. Our payment processing partner.";
    }
    // echo "Failed to update payment status.";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed | Fayyaz Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
        }

        body {
            background: linear-gradient(135deg, #fff1f2 0%, #fef2f2 100%);
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            padding: 1rem;
        }

        .failure-container {
            max-width: 500px;
            width: 100%;
        }

        .failure-card {
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06);
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .failure-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--danger), var(--warning));
        }

        .company-logo {
            width: 100px;
            margin-bottom: 2rem;
        }

        .failure-icon-wrapper {
            width: 90px;
            height: 90px;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .failure-icon {
            color: var(--danger);
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

        .failure-title {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--danger), var(--warning));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .failure-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .error-card {
            background: var(--light-bg);
            border-radius: 16px;
            padding: 1.2rem;
            margin: 1.5rem 0;
            border-left: 4px solid var(--danger);
        }

        .error-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--danger);
            margin-bottom: 0.5rem;
        }

        .error-message {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 0;
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

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-danger {
            background: linear-gradient(90deg, var(--danger), #f87171);
            border: none;
            border-radius: 50px;
            padding: 0.9rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.3px;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.25);
            transition: all 0.3s ease;
            white-space: nowrap;
            text-decoration: none;
            color: #fff;
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(239, 68, 68, 0.3);
            background: linear-gradient(90deg, #e03c3c, #f06666);
            color: #fff;
            text-decoration: none;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #e2e8f0;
            color: var(--text-secondary);
            border-radius: 50px;
            padding: 0.9rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-decoration: none;
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
            text-decoration: none;
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
            .failure-card {
                padding: 2rem 1.5rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-danger,
            .btn-outline {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="failure-container">
        <div class="failure-card">
            <img src="../assets/images/main-logo.png" alt="Fayyaz Travels Logo" class="company-logo">

            <div class="failure-icon-wrapper">
                <i class="bi bi-exclamation-circle failure-icon"></i>
            </div>

            <h1 class="failure-title">Payment Failed</h1>
            <p class="failure-subtitle">Your transaction could not be completed</p>

            <div class="notification-box">
                <div class="notification-icon">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="notification-text">
                    Please check your payment details and ensure sufficient funds are available in your account.
                </div>
            </div>

            <div class="notification-box">
                <div class="notification-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <div class="notification-text">
                    Our support team is available to assist you. Contact us if you continue experiencing issues.
                </div>
            </div>

            <div class="btn-group">
                <a href="/visa/payment/pay?order_id=<?= $orderId; ?>" class="btn btn-danger">
                    <i class="bi bi-arrow-repeat me-2"></i>Try Again
                </a>
                <a href="mailto:info@fayyaztravels.com" class="btn btn-outline">
                    <i class="bi bi-headset me-2"></i>Contact Support
                </a>
            </div>

            <div class="divider"></div>

            <p class="contact-info">
                Questions? Contact us at <a href="mailto:info@fayyaztravels.com">info@fayyaztravels.com</a> or call <a href="tel:+6562352900">+65 6235 2900</a>
            </p>
        </div>
    </div>

    <!-- Deferred loading of JavaScript -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script> -->
</body>

</html>