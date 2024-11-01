<?php
session_start();
require 'core/dbConfig.php';
require 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$manga_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$manga_id) {
    header("Location: dashboard.php");
    exit();
}

// Check if the manga exists and belongs to the current user
$manga = getMangaById($manga_id);
if (!$manga || $manga['user_id'] != $user_id) {
    header("Location: dashboard.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    if (deleteManga($manga_id)) {
        header("Location: dashboard.php?delete=success");
        exit();
    } else {
        $message = "Error deleting manga. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Manga - 141 Manga Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right, #111111, #333333);
            color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

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

        h1 {
            color: #ffeb3b;
            font-family: "Comic Sans MS", sans-serif;
            margin-bottom: 30px;
            text-shadow: 1px 1px 5px #ff4c4c;
        }

        .manga-info {
            background-color: rgba(255, 76, 76, 0.1);
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            text-align: left;
        }

        .manga-info p {
            margin: 10px 0;
        }

        .warning-text {
            color: #ff4c4c;
            font-weight: bold;
            margin: 20px 0;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        button, .cancel-button {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        button {
            background-color: #ff4c4c;
            color: #fff;
        }

        button:hover {
            background-color: #ff1c1c;
        }

        .cancel-button {
            background-color: #333;
            color: #fff;
        }

        .cancel-button:hover {
            background-color: #444;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            background-color: rgba(255, 76, 76, 0.1);
            border: 1px solid #ff4c4c;
            color: #ff4c4c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Manga</h1>

        <?php if ($message): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="manga-info">
            <p><strong>Title:</strong> <?php echo htmlspecialchars($manga['title']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($manga['author']); ?></p>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($manga['genre']); ?></p>
            <p><strong>Price:</strong> ₱<?php echo htmlspecialchars($manga['price']); ?></p>
        </div>

        <p class="warning-text">Are you sure you want to delete this manga? This action cannot be undone.</p>

        <form method="POST" action="">
            <div class="button-group">
                <button type="submit" name="confirm_delete">Delete Manga</button>
                <a href="dashboard.php" class="cancel-button">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>