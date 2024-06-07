<?php
// Include PHPMailer autoloader
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_email_user($email, $name, $message, $subject, $body) {
    try {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Set mailer to use SMTP
        $mail->isSMTP();
        
        // Specify Hostinger SMTP server (replace if using a different provider)
        $mail->Host       = 'smtp.hostinger.com';
        
        // Enable SMTP authentication
        $mail->SMTPAuth   = true;
        
        // SMTP username (your email address)
        $mail->Username   = 'no-reply@1920snkrs.shop';
        
        // SMTP password
        $mail->Password   = '@Password2024';
        
        // Enable TLS encryption
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        
        // TCP port to connect to (usually 587 for TLS or 465 for SSL)
        $mail->Port       = 587;

        // Set sender's email address and name
        $mail->setFrom('no-reply@1920snkrs.shop', '1920snkrs');

        // Add recipient's email address and name
        $mail->addAddress($email, $name);

        // Email subject and body
        $mail->Subject = $subject;
        $mail->isHTML(true);

        // Compose the HTML email body
        $emailBody = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    h1, h2, h3 {
      color: #333;
      margin-bottom: 15px;
    }
    p {
      font-size: 16px;
      line-height: 1.5;
      color: #666;
      margin-bottom: 15px;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
    }
    .table th, .table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }
    .table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #2BA6CB;
      color: #fff;
      text-align: center;
      text-decoration: none;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    footer {
      text-align: center;
      padding: 20px;
      color: #aaa;
      font-size: 14px;
    }
    footer a {
      color: #2BA6CB;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    $message
  </div>
  <footer>
    <p>&copy; 1920 SNKRS. All Rights Reserved.</p>
    <p><a href="#">Terms & Conditions</a> | <a href="#">Privacy Policy</a></p>
  </footer>
</body>
</html>
HTML;

        // Set the email body
        $mail->Body = $emailBody;

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        // Failed to send email
        echo '<script>alert("Failed to send a verification email. Try again or contact the admin.");</script>';
    }
}
?>
