<?php
// edituser.php
session_start();
require 'core/dbConfig.php';
require 'core/models.php';
require 'core/validate.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user = getUserById($_SESSION['user_id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // First verify the current password
    if (!empty($current_password)) {
        if (password_verify($current_password, $user['password'])) {
            if (!empty($new_password) && !empty($confirm_password)) {
                if ($new_password === $confirm_password) {
                    if (strlen($new_password) >= 8) {
                        // Update password
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        updateUserWithPassword($_SESSION['user_id'], $username, $email, $hashed_password);
                        $message = "Profile and password updated successfully!";
                    } else {
                        $message = "New password must be at least 8 characters long.";
                    }
                } else {
                    $message = "New passwords do not match.";
                }
            } else {
                // Update only username and email
                updateUser($_SESSION['user_id'], $username, $email);
                $message = "Profile updated successfully!";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    } else {
        // Update only username and email
        updateUser($_SESSION['user_id'], $username, $email);
        $message = "Profile updated successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - 141 Manga Store</title>
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

        .section-title {
            color: #ffeb3b;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.2em;
            border-bottom: 1px solid #ff4c4c;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        
        <?php if ($message): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="edituser.php" method="POST">
            <div class="section-title">Profile Information</div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="section-title">Change Password (Optional)</div>
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password">
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password">
            </div>

            <button type="submit">Save Changes</button>
        </form>
        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>