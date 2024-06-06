<?php
// Include PHPMailer autoloader

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// Create a new PHPMailer instance
$mail = new PHPMailer(true);

function send_verification_email($email, $username, $OneTimePassword) {
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
      $mail->addAddress("$email", "$username");
  
      // Email subject and body
      $mail->Subject = 'Email Verification';
      $mail->Body = "<!DOCTYPE html>
      <html lang='en'>
      <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <link rel='stylesheet' href='style.css'>
          <style>
              body {
                  font-family: sans-serif;
                  margin: 0;
                  padding: 0;
                  background-color: #f5f5f5;
              }
              .container {
                  width: 300px;
                  margin: 50px auto;
                  padding: 20px;
                  border: 1px solid #ccc;
                  border-radius: 5px;
                  background-color: #fff;
                  text-align: center; /* Center align the text within the container */
              }
              .header {
                  font-size: 20px;
                  font-weight: bold;
                  margin-bottom: 20px;
              }
              .code {
                  font-size: 36px;
                  font-weight: bold;
                  letter-spacing: 10px;
                  color: #222;
                  margin-bottom: 20px;
              }
              .disclaimer {
                  font-size: 12px;
                  color: #777;
                  margin-bottom: 20px; /* Add margin at the bottom */
              }
              .footer {
                  font-size: 10px;
                  color: #ccc;
                  margin-top: 20px;
              }
          </style>
      </head>
      <body>
          <div class='container'>
              <div class='header'>
                  One-Time Password
              </div>
              <div class='code'>
                            $OneTimePassword
              </div>
              <div class='disclaimer'>
                  Please make sure you never share this code with anyone.
              </div>
              <div class='footer'>
                  ©2024 1920 snkrs
              </div>
          </div>
      </body>
      </html>";
      $mail->isHTML(true);
      // Send the email
      $mail->send();
    //   echo 'Email sent successfully';
    ?>
<script>
    alert("OTP sent via email.");
</script>

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