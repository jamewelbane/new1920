<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("../database/connection.php");

function generateOTP($length = 4)
{
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

function handleValidationError($errorMessage)
{

    echo "<script>
              var errorMessage = '" . $errorMessage . "';
              if (errorMessage) {
                  if (confirm(errorMessage)) {
                    history.back();
                  }
              }
            </script>";
    exit();
}

//success message alert
function handleValidationSuccess($successMessage)
{
    echo "<script>
          var successMessage = '" . $successMessage . "';
          if (successMessage) {
              if (confirm(successMessage)) {
                window.location.href = '../index';
              }
          }
        </script>";
}

require("otp/otp_mail.php");


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['email'])) {
    $email = htmlspecialchars($_GET["email"]);

    // Generate OTP
    $otp = generateOTP();

    // Store OTP in session
    $_SESSION['reset_pass_otp'] = $otp;

    if ($otp === null) {
        handleValidationError("Failed to generate an OTP. Contact your admin");
    } else {
        send_verification_email($email, $otp);
    }

    $successValidation1 = "Redirecting for email verification....";

    echo "<script>
        var successMessage = '" . $successValidation1 . "';
        if (successMessage) {
            if (confirm(successMessage)) {
              
            }
        }
      </script>";
}
?>



<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OTP Verification Form</title>
    <link rel="stylesheet" href="assets/verify-style.css">
</head>

</head>

<body>
    <div class="container">
        <div class="title">Verification Code</div>
        <p>We have sent a verification code to your email: <?= $email ?></p>
        <form id="otpForm" action="#" onsubmit="showLoading()">
            <div>
                <input id="otp" type="text" maxlength="4">
            </div>
            <button id="verifyBtn" type="submit">Submit</button>
            <button id="requestNewOtpBtn" onclick="showLoading()" type="button" style="margin-top: 10px">Request New OTP</button>
            <div id="loading">
                <img src="../index-resources/assets/images/gif/loading2.gif" alt="Loading..." style="width: 20%">
            </div>
        </form>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function showLoading() {
        var loading = document.getElementById('loading');

        // Show the loading animation
        loading.style.display = 'block';

        setTimeout(function() {
            loading.style.display = 'none';
        }, 3000);
    }
</script>

<script>
    // Set PHP variables as JavaScript variables
    var email = <?php echo json_encode($email); ?>;
</script>

<script>
    $(document).ready(function() {
        $('#verifyBtn').click(function(event) {
            // Prevent default form submission
            event.preventDefault();

            // Show loading animation
            $('#loading').show();

            // Get OTP from input fields
            var otp = $('#otp').val();

            // AJAX request to handle OTP verification
            $.ajax({
                type: 'POST',
                url: 'otp/verify_otp.php',
                data: {
                    otp: otp,
                    email: email
                },
                success: function(response) {
                    // Hide loading animation
                    $('#loading').hide();

                    // Process the response
                    if (response === 'success') {
                        // OTP verified successfully
                        alert('OTP verified. Redirecting...');
                        window.location.href = '../new_password.php';
                    } else {
                        // OTP verification failed
                        alert('Invalid OTP! Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    // Hide loading animation
                    $('#loading').hide();

                    // Handle error if any
                    console.error(xhr.responseText);
                    alert('An error occurred while verifying OTP. Please try again later.');
                }
            });
        });

        $('#requestNewOtpBtn').click(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'request_new_otp.php',
                data: {
                    email: <?php echo json_encode($email); ?>
                },
                success: function(response) {
                    if (response === 'success') {
                        alert('New OTP has been sent to your email.');
                    } else {
                        alert(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while requesting a new OTP. Please try again later.');
                }
            });
        });
    });
</script>




</html>