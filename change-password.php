<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All password fields are required.";
    } elseif (strlen($new_password) < 8) {
        $message = "New password must be at least 8 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
    } else {
        // Fetch current user password hash from database
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $hashed_current = hash('sha256', $current_password);

        if ($user && $user['password_hash'] === $hashed_current) {
            // Update to new password
            $new_hashed = hash('sha256', $new_password);
            $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $update_stmt->execute([$new_hashed, $user_id]);

            $success_msg = "Password successfully updated!";
        } else {
            $message = "Incorrect current password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Flogram - Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="Login">
    <div class="form-container">
        <h1>Change Password</h1>
        <p class="subtitle">Ensure your account is using a secure password.</p>

        <?php if (!empty($message)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
            <div class="subtitle" style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center;">
                <?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <fieldset>
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" placeholder="Current password">

                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" placeholder="New password">

                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">

                <input type="submit" value="Update Password">
            </fieldset>
        </form>

        <a href="profile.php" class="secondary-btn">Back to Profile</a>
    </div>
</body>
</html>