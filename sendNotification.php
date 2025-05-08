<?php
require_once 'database.php';

/**
 * Send a notification to a user
 * 
 * @param int $customer_id The ID of the customer to send the notification to
 * @param string $message The notification message
 * @param string $type The type of notification
 * @param int $is_seen Default 0 (unread)
 * @param string|null $order_id Optional order ID associated with the notification
 * @return array Response with success status and message
 */
function sendNotification($customer_id, $message, $type, $is_seen = 0, $order_id = null)
{
    global $database;

    try {
        // Validate required parameters
        if (empty($customer_id) || empty($message) || empty($type)) {
            return [
                'success' => false,
                'message' => 'Customer ID, message, and type are required'
            ];
        }

        // Prepare notification data
        $notification_data = [
            'customer_id' => $customer_id,
            'notification_message' => $message,
            'type' => $type,
            'is_seen' => $is_seen,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Include order_id if provided
        if (!is_null($order_id)) {
            $notification_data['order_id'] = $order_id;
        }

        // Insert notification into database
        $result = $database->insert('notifications', $notification_data);

        if ($result->rowCount() > 0) {
            return [
                'success' => true,
                'message' => 'Notification sent successfully',
                'notification_id' => $database->id()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to send notification'
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error sending notification: ' . $e->getMessage()
        ];
    }
}

// Example usage:
/*
$result = sendNotification(
    customer_id: 123,
    message: "Your visa application has been approved!",
    type: "application",
    order_id: "order_456"
);

if ($result['success']) {
    echo "Notification sent successfully!";
} else {
    echo "Error: " . $result['message'];
}
*/
