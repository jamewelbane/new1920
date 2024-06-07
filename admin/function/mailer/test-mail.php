<?php
// Include PHPMailer autoloader
require '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Set mailer to use SMTP
    $mail->isSMTP();
    
    // Specify Hostinger SMTP server
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

    // Sender's email address
    $mail->setFrom('no-reply@1920snkrs.shop', '1920snkrs');

    // Recipient's email address
    $mail->addAddress('tahon53173@cnurbano.com', 'OTP');

    // Email subject and body
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = 'This is a test email sent from PHPMailer using test-mail.php.';

    // Send the email
    $mail->send();
    echo 'Email sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>