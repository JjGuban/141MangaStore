<?php
// index.php

session_start();
require 'core/dbConfig.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>141 Manga Store - Login</title>
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
            width: 400px;
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
        }

        input[type="text"],
        input[type="password"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>141 Manga Store</h1>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
        
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
