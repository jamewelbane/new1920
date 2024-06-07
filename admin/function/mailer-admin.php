<?php
function generateEmailTemplate($type, $orderDetails) {
    $subject = '';
    $message = '';
    switch ($type) {
        case 'confirmation':
            $subject = 'Your Order Has Been Confirmed!';
            $message = "Your order #{$orderDetails['orderId']} has been confirmed and is ready to ship. Please keep your lines open as a representative from our store will contact you for the delivery process.";
            break;
        case 'completion':
            $subject = 'Your Order Has Been Completed!';
            $message = "Your order #{$orderDetails['orderId']} has been successfully delivered. Thank you for shopping with us!";
            break;
        case 'cancellation':
            $subject = 'Your Order Has Been Cancelled';
            $message = "We regret to inform you that your order #{$orderDetails['orderId']} has been cancelled. Please contact our support for more details.";
            break;
        default:
            $subject = 'Order Notification';
            $message = "Your order #{$orderDetails['orderId']} has been updated. Please check your order details for more information.";
    }

    return [
        'subject' => $subject,
        'body' => "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        width: 100%;
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        background-color: #4CAF50;
                        color: #ffffff;
                        padding: 10px 0;
                        text-align: center;
                        border-radius: 8px 8px 0 0;
                    }
                    .content {
                        padding: 20px;
                        line-height: 1.6;
                    }
                    .content h2 {
                        color: #333333;
                    }
                    .content p {
                        margin: 10px 0;
                    }
                    .footer {
                        text-align: center;
                        padding: 10px;
                        font-size: 12px;
                        color: #888888;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        margin: 10px 0;
                        font-size: 16px;
                        color: #ffffff;
                        background-color: #4CAF50;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Order Notification</h1>
                    </div>
                    <div class='content'>
                        <h2>{$subject}</h2>
                        <p>Dear {$orderDetails['customerName']},</p>
                        <p>{$message}</p>
                        <p><strong>Order Details:</strong></p>
                        <ul>
                            <li>Order ID: {$orderDetails['orderId']}</li>
                            <li>Order Date: {$orderDetails['orderDate']}</li>
                            <li>Shipping Address: {$orderDetails['shippingAddress']}</li>
                        </ul>
                        <a href='{$orderDetails['orderLink']}' class='button'>View Order</a>
                    </div>
                    <div class='footer'>
                        <p>Thank you for shopping with us!</p>
                        <p>&copy; " . date('Y') . " Your Store. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        "
    ];
}

?>