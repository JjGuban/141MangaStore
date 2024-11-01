<?php
// purchase.php

session_start();
require 'core/models.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if a manga ID is provided in the URL
if (isset($_GET['manga_id'])) {
    $manga_id = $_GET['manga_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has an active (non-cancelled) purchase of this manga
    $existingPurchase = getPurchase($user_id, $manga_id);

    if (!$existingPurchase) {
        // Record the purchase
        addPurchase($user_id, $manga_id);
        header("Location: dashboard.php?purchase=success");
    } else {
        header("Location: dashboard.php?purchase=already_active");
    }
} else {
    header("Location: dashboard.php?purchase=failed");
}
exit();