<?php
// cancel.php

session_start();
require 'core/models.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if a purchase ID is provided in the URL
if (isset($_GET['purchase_id'])) {
    $purchase_id = $_GET['purchase_id'];
    $user_id = $_SESSION['user_id'];

    // Perform the cancellation
    cancelPurchase($purchase_id, $user_id);

    // Redirect back to the dashboard with a cancellation success message
    header("Location: dashboard.php?cancel=success");
} else {
    header("Location: dashboard.php?cancel=failed");
}
exit();
