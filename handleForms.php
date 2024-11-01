<?php
// core/handleForms.php

require 'dbConfig.php';
require 'models.php';
require 'validate.php';

// Registration handler
if (isset($_POST['register'])) {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    if (validatePassword($password)) {
        createUser($username, $email, $password);
        header("Location: login.php");
    } else {
        echo "Password must be at least 8 characters.";
    }
}

// Login handler
if (isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    $user = getUserByUsername($username);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: dashboard.php");
    } else {
        echo "Invalid login credentials.";
    }
}
?>
