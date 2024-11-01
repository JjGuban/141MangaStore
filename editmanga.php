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

$manga = getMangaById($manga_id);

if (!$manga || $manga['user_id'] != $user_id) {
    header("Location: dashboard.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $genre = trim($_POST['genre']);
    $author = trim($_POST['author']);
    $price = trim($_POST['price']);

    if (!empty($title) && !empty($genre) && !empty($author) && !empty($price) && is_numeric($price)) {
        updateManga($manga_id, $title, $genre, $author, $price);
        $message = "Manga updated successfully!";
        $manga = getMangaById($manga_id); // Refresh manga data
    } else {
        $message = "Please fill in all fields and enter a valid price.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Manga - 141 Manga Store</title>
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
        }

        h1 {
            color: #ffeb3b;
            text-align: center;
            font-family: "Comic Sans MS", sans-serif;
            margin-bottom: 30px;
            text-shadow: 1px 1px 5px #ff4c4c;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #bbb;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #777;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            font-size: 16px;
            box-sizing: border-box;
        }

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
            margin-bottom: 15px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ff1c1c;
        }

        .back-link {
            display: block;
            text-align: center;
            color: #ffeb3b;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            background-color: rgba(255, 235, 59, 0.1);
            border: 1px solid #ffeb3b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Manga</h1>
        
        <?php if ($message): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($manga['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="genre">Genre:</label>
                <input type="text" name="genre" id="genre" value="<?php echo htmlspecialchars($manga['genre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($manga['author']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" step="0.01" value="<?php echo htmlspecialchars($manga['price']); ?>" required>
            </div>

            <button type="submit">Update Manga</button>
        </form>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>