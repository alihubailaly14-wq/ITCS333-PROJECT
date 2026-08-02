<?php
session_start();
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login_id = trim($_POST["login_id"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($login_id === "" || $password === "") {
        $error = "Please complete all fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password_hash = ?");
        $stmt->execute([$login_id, hash('sha256', $password)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            session_regenerate_id(true);

            $_SESSION["logged_in"] = true;
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["name"] = $user["full_name"];
            $_SESSION["email"] = $user["email"];

            header("Location: home.php?login=success");
            exit();
        } else {
            $error = "Incorrect login information.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login page</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body class="Login">
        <div class="form-container">
            <h1>Log into Flogram</h1>

            <?php if (isset($error)): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="post">
                <fieldset>
                    <label for="login_id" style="display: none;">Email</label>

                    <input type="text" name="login_id" id="login_id"
                    placeholder="Email"
                    value="<?= htmlspecialchars($_POST["login_id"] ?? "") ?>">

                    <label for="password" style="display: none;">Password</label>

                    <input type="password" name="password" id="password"
                    placeholder="Password">

                    <input type="submit" name="submit" id="submit"
                    value="Log in" class="login-btn">
                </fieldset>
            </form>

            <a href="Registration.php" class="secondary-btn">You do not have an account? Create one</a>
        </div>
    </body>
</html>
