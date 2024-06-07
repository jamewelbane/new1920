<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .container {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-group {
            position: relative;
        }

        .form-group input[type="email"] {
            padding-right: 35px;
        }

        .form-group .fa {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            cursor: pointer;
        }

        .text-danger {
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Forgot Password</h2>
        <form id="forgotPasswordForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                <div id="emailError" class="text-danger"></div>
            </div>
            <button type="button" id="checkEmail" class="btn btn-primary">Check Email</button>
            <center>
                <div id="loading" style="display: none; margin-bottom: 5px">
                    <img src="index-resources/assets/images/gif/loading2.gif" alt="Loading..." style="width: 20%">
                </div>
            </center>
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showLoading() {
            var loading = document.getElementById('loading');

            // Show the loading animation
            loading.style.display = 'block';

            setTimeout(function() {
                loading.style.display = 'none';
            }, 4000);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#checkEmail').click(function() {
                var email = $('#email').val();
                $.ajax({
                    url: 'main/check_email.php',
                    method: 'POST',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response == 'exists') {
                            showLoading();
                            // Email exists, redirect to verify_reset_pass.php
                            window.location.href = 'main/verify_reset_pass.php?email=' + email;
                        } else {
                            // Email doesn't exist, display error message
                            $('#emailError').html('Email does not exist in our records.');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>