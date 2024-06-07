<?php
require_once("../database/connection.php"); // Include your database link file

// Function to perform bcrypt encryption
function encryptPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Validate email and new password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email and new password from the form
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];

    // Perform validation on the new password (e.g., minimum length)
    if (strlen($newPassword) < 8) {
        // Redirect or display error message for invalid password
        header("Location: error_page.php");
        exit();
    }

    // Encrypt the new password using bcrypt
    $hashedPassword = encryptPassword($newPassword);

    // Update the password in the 'users' table
    $query = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";
    $result = mysqli_query($link, $query);

    if ($result) {
        // Password update successful, redirect to success page
        header("Location: ../index.php");
        exit();
    } else {
        // Password update failed, handle the error (e.g., display error message or redirect to error page)
        header("Location: error_page.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
    <!-- Add your CSS file or internal styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input[type="password"] {
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            input[type="password"],
            button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>New Password</h2>
        <!-- HTML form for password update -->
        <form action="" method="post">
            <!-- Add input field for new password -->
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required minlength="8">
            <!-- Hidden field to pass email -->
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>

</html>
