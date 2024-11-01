<?php
// dashboard.php

session_start();
require 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Assign session user_id to $user_id for convenience
$user_id = $_SESSION['user_id'];

// Fetch all available mangas
$availableMangas = getAllMangas();

// Fetch active and canceled purchases for the logged-in user
$activePurchases = getActivePurchases($user_id);
$canceledPurchases = getCanceledPurchases($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - 141 Manga Store</title>
    <style>
        /* Global Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        h1, h2 {
            color: #ffeb3b;
            font-family: "Comic Sans MS", sans-serif;
        }

        /* Logout Button Styling */
        .logout-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #ff4c4c;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            float: right;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .logout-button:hover {
            background-color: #ff1c1c;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #555;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #ffeb3b;
        }

        /* Action Buttons */
        .button {
            padding: 8px 12px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin: 2px;
        }

        .buy-button {
            background-color: #4caf50;
        }

        .buy-button:hover {
            background-color: #45a049;
        }

        .cancel-button {
            background-color: #4caf50;
        }

        .cancel-button:hover {
            background-color: #45a049;
        }

        .edit-button {
            background-color: #ffa500;
            margin-right: 5px;
        }

        .edit-button:hover {
            background-color: #ff8c00;
        }

        .delete-button {
            background-color: #ff4c4c;
        }

        .delete-button:hover {
            background-color: #ff1c1c;
        }

        .add-manga-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffa500;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 15px;
            transition: background-color 0.3s;
        }

        .add-manga-button:hover {
            background-color: #ff8500;
        }

        /* Settings Section */
        .settings-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #222;
            border-radius: 10px;
            text-align: center;
        }

        .settings-link {
            display: inline-block;
            padding: 10px 15px;
            background-color: #ffeb3b;
            color: #1a1a1a;
            font-weight: bold;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .settings-link:hover {
            background-color: #e0c500;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout-button">Logout</a>
        <h1>Welcome to 141 Manga Store!</h1>
        
        <!-- "Add Your Own Manga!" Button -->
        <a href="addmanga.php" class="add-manga-button">Add Your Own Manga!</a>
        
        <!-- Your Uploaded Manga Section -->
        <h2>Your Uploaded Manga</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php
            $userMangas = getUserMangas($_SESSION['user_id']);
            foreach ($userMangas as $manga): 
            ?>
                <tr>
                    <td><?= htmlspecialchars($manga['title']) ?></td>
                    <td><?= htmlspecialchars($manga['author']) ?></td>
                    <td><?= htmlspecialchars($manga['genre']) ?></td>
                    <td>₱<?= htmlspecialchars($manga['price']) ?></td>
                    <td>
                        <a href="editmanga.php?id=<?= $manga['manga_id'] ?>" class="button edit-button">Edit</a>
                        <a href="deletemanga.php?id=<?= $manga['manga_id'] ?>" class="button delete-button" onclick="return confirm('Are you sure you want to delete this manga?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <!-- Available Mangas Section -->
        <h2>Available Mangas</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Price</th>
                <th>Uploaded By</th>
                <th>Upload Time</th>
                <th>Action</th>
            </tr>
            <?php foreach ($availableMangas as $manga): ?>
                <tr>
                    <td><?= htmlspecialchars($manga['title']) ?></td>
                    <td><?= htmlspecialchars($manga['author']) ?></td>
                    <td><?= htmlspecialchars($manga['genre']) ?></td>
                    <td>₱<?= htmlspecialchars($manga['price']) ?></td>
                    <td><?= htmlspecialchars($manga['username']) ?></td>
                    <td><?= htmlspecialchars($manga['added_by']) ?></td>
                    <td><a href="purchase.php?manga_id=<?= $manga['manga_id'] ?>" class="button buy-button">Buy</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Purchases Section -->
        <h2>Your Purchases</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Purchase Date</th>
                <th>Cancel Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($activePurchases as $purchase): ?>
                <tr>
                    <td><?= htmlspecialchars($purchase['title']) ?></td>
                    <td><?= htmlspecialchars($purchase['purchase_date']) ?></td>
                    <td><?= htmlspecialchars($purchase['cancel_date']) ?></td>
                    <td>
                        <a href="cancel.php?purchase_id=<?= $purchase['purchase_id'] ?>" 
                           class="button cancel-button" 
                           onclick="return confirm('Are you sure you want to cancel this purchase?');">
                            Cancel
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Canceled Purchases Section -->
        <h2>Canceled Purchases</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Purchase Date</th>
                <th>Cancel Date</th>
            </tr>
            <?php foreach ($canceledPurchases as $purchase): ?>
                <tr>
                    <td><?= htmlspecialchars($purchase['title']) ?></td>
                    <td><?= htmlspecialchars($purchase['purchase_date']) ?></td>
                    <td><?= htmlspecialchars($purchase['cancel_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Settings Section -->
        <div class="settings-section">
            <h2>Account Settings</h2>
            <a href="edituser.php" class="settings-link">Edit Your Information</a>
            <a href="deleteuser.php" class="settings-link">Delete Account</a>
        </div>
    </div>
</body>
</html>