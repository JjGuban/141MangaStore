<?php
// core/models.php

require 'dbConfig.php';


// Get user by username for login validation
function getUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([trim($username)]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get user by ID for profile display and editing
function getUserById($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update user information
function updateUser($user_id, $username, $email) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->execute([trim($username), trim($email), $user_id]);
}

// Delete user account
function deleteUser($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
}

// Create manga listing
function createManga($title, $genre, $author, $price, $user_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO manga (title, genre, author, price, user_id, added_by) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([trim($title), trim($genre), trim($author), $price, $user_id]);
}


// Get manga by ID for details display
function getMangaById($manga_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM manga WHERE manga_id = ?");
    $stmt->execute([$manga_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Update manga information
function updateManga($manga_id, $title, $genre, $author, $price) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE manga SET title = ?, genre = ?, author = ?, price = ?, last_updated = NOW() WHERE manga_id = ?");
    $stmt->execute([trim($title), trim($genre), trim($author), $price, $manga_id]);
}


// Delete manga listing
function deleteManga($manga_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM manga WHERE manga_id = ?");
    $stmt->execute([$manga_id]);
}

// Fetch all mangas with uploader's username
function getAllMangas() {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT manga.*, users.username
        FROM manga
        JOIN users ON manga.user_id = users.user_id
        ORDER BY manga.added_by DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create purchase record for manga
function createPurchase($user_id, $manga_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO purchases (user_id, manga_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $manga_id]);
}

// Get user purchases
function getUserPurchases($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.purchase_id, m.title, m.author, m.genre, m.price, p.purchase_date FROM purchases p JOIN manga m ON p.manga_id = m.manga_id WHERE p.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Delete a purchase
function deletePurchase($purchase_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM purchases WHERE purchase_id = ?");
    $stmt->execute([$purchase_id]);
}

// Check if an active purchase already exists
function getPurchase($user_id, $manga_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM purchases WHERE user_id = ? AND manga_id = ? AND cancel_date IS NULL");
    $stmt->execute([$user_id, $manga_id]);
    return $stmt->fetch();
}

// Add a new purchase
function addPurchase($user_id, $manga_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO purchases (user_id, manga_id, purchase_date) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $manga_id]);
}

// Cancel a purchase by updating the cancel_date
function cancelPurchase($purchase_id, $user_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE purchases SET cancel_date = NOW() WHERE purchase_id = ? AND user_id = ?");
    $stmt->execute([$purchase_id, $user_id]);
}

// Fetch active purchases (not canceled)
function getActivePurchases($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.*, m.title 
        FROM purchases p 
        JOIN manga m ON p.manga_id = m.manga_id 
        WHERE p.user_id = ? AND p.cancel_date IS NULL
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch canceled purchases
function getCanceledPurchases($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.*, m.title 
        FROM purchases p 
        JOIN manga m ON p.manga_id = m.manga_id 
        WHERE p.user_id = ? AND p.cancel_date IS NOT NULL
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check if a user with the same username or email already exists
function getUserByUsernameOrEmail($username, $email) {
    global $pdo;
    $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username, ':email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Register a new user
function registerUser($username, $email, $password) {
    global $pdo;
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password]);
}

// Updates the User's informations
function updateUserWithPassword($user_id, $username, $email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?");
    $stmt->execute([trim($username), trim($email), $password, $user_id]);
}

function getUserMangas($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT * FROM manga 
        WHERE user_id = ? 
        ORDER BY added_by DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
