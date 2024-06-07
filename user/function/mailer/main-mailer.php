<?php
// Include PHPMailer autoloader

require '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// Create a new PHPMailer instance
$mail = new PHPMailer(true);

function send_email_user($email, $name, $message, $subject) {
    try {
      // Create a new PHPMailer instance
      $mail = new PHPMailer(true);
  
      // Set mailer to use SMTP
      $mail->isSMTP();
      
      // Specify Hostinger SMTP server (replace if using different provider)
      $mail->Host       = 'smtp.hostinger.com';
      
      // Enable SMTP authentication
      $mail->SMTPAuth   = true;
      
      // SMTP username (your email address)
      $mail->Username   = 'noreply@hungrydev.site';
      
      // SMTP password
      $mail->Password   = '@Password2024';
      
      // Enable TLS encryption
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      
      // TCP port to connect to (usually 587 for TLS or 465 for SSL)
      $mail->Port       = 587;
  
      // Sender's email address
      $mail->setFrom('noreply@hungrydev.site', '1920snkrs');
  
      // Recipient's email address
      $mail->addAddress("$email", "$name");
  
      // Email subject and body
      $mail->Subject = 'Email Verification';
      $mail->Body = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="container">
                <div class="header">
                    <h1>Order Notification</h1>
                </div>
                <div class="content">
                    <h2>' . $subject . '</h2>
                    <p>Dear' . $name . ',</p>
                    <p>${message}</p>
                    <p><strong>Order Details:</strong></p>
                    <ul>
                        ' . $message . '
                    </ul>
                    <a href="${orderDetails.orderLink}" class="button">View Order</a>
                </div>
                <div class="footer">
                    <p>Thank you for shopping with us!</p>
                    <p>&copy; 1920 SNKRS. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
      $mail->isHTML(true);
      // Send the email
      $mail->send();
    //   echo 'Email sent successfully';
    ?>





<?php
    } catch (Exception $e) {
        ?>
        <script>
            alert("Failed to send a verification email. Try again or contact the admin");
        </script>
        
        <?php
    //   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; //for debug
    }
  }
  

?>