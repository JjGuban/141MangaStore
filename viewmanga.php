<?php
// viewmanga.php

session_start();
require 'core/dbConfig.php';
require 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$manga_id = $_GET['id'];
$manga = getMangaById($manga_id);

if (!$manga) {
    echo "Manga not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Manga - <?php echo htmlspecialchars($manga['title']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($manga['title']); ?></h1>
    <p>Genre: <?php echo htmlspecialchars($manga['genre']); ?></p>
    <p>Author: <?php echo htmlspecialchars($manga['author']); ?></p>
    <p>Price: <?php echo htmlspecialchars($manga['price']); ?></p>

    <form action="viewpurchase.php" method="POST">
        <input type="hidden" name="manga_id" value="<?php echo $manga['manga_id']; ?>">
        <button type="submit" name="buy">Buy</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
