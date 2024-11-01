<?php
// register.php

session_start();
require 'core/dbConfig.php';
require 'core/models.php';
require 'core/validate.php';
$error = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate form fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username or email already exists
        $existingUser = getUserByUsernameOrEmail($username, $email);
        if ($existingUser) {
            $error = 'Username or email already taken.';
        } else {
            // Create new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            if (registerUser($username, $email, $hashedPassword)) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - 141 Manga Store</title>
    <style>
        /* Background Styling */
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right, #111111, #333333);
            color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        /* Container Styling */
        .container {
            width: 90%;
            max-width: 400px;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.85);
            border: 2px solid #ff4c4c;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        /* Header */
        h1 {
            font-size: 32px;
            color: #ffeb3b;
            margin-bottom: 20px;
            font-family: "Comic Sans MS", sans-serif;
            text-shadow: 1px 1px 5px #ff4c4c;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            color: #bbb;
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #777;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            font-size: 16px;
            box-sizing: border-box;
        }

        /* Button Styling */
        button {
            width: 100%;
            padding: 12px;
            background-color: #ff4c4c;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ff1c1c;
        }

        /* Register Link Styling */
        .register-link {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }

        .register-link a {
            color: #ffeb3b;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 500px) {
            h1 {
                font-size: 28px;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register for 141 Manga Store</h1>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <?php if (isset($error)) { echo "<p style='color: #ff4c4c;'>$error</p>"; } ?>
            <button type="submit">Register</button>
        </form>
        <div class="register-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
