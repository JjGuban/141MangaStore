<?php
// viewpurchase.php

session_start();
require 'core/dbConfig.php';
require 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy'])) {
    $manga_id = $_POST['manga_id'];
    $user_id = $_SESSION['user_id'];

    createPurchase($user_id, $manga_id);
    echo "Purchase successful! <a href='dashboard.php'>Back to Dashboard</a>";
    exit();
}

// For a potential refund, you could implement a similar handler.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchases</title>
</head>
<body>
    <h1>Manage Your Purchases</h1>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
