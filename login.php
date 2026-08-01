<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login_id = trim($_POST["login_id"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($login_id === "" || $password === "") {
        $error = "Please complete all fields.";
    } elseif (!isset($_SESSION["registered_user"])) {
        $error = "Account not found.";
    } else {
        $user = $_SESSION["registered_user"];

        $correct_login =
            strcasecmp($login_id, $user["email"]) === 0 ||
            strcasecmp($login_id, $user["username"]) === 0;

        if ($correct_login && isset($user["password_hash"]) && password_verify($password, $user["password_hash"])) {
            session_regenerate_id(true);

          $_SESSION["logged_in"] = true;
          $_SESSION["user"] = $user;
          $_SESSION["name"] = $user["name"];
          $_SESSION["email"] = $user["email"];
          $_SESSION["username"] = $user["username"];

            header("Location: home.php");
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
                    <label for="login_id" style="display: none;">Mobile number, username or email</label>

                    <input type="text" name="login_id" id="login_id"
                    placeholder="Mobile number, username or email"
                    value="<?= htmlspecialchars($_POST["login_id"] ?? "") ?>">

                    <label for="password" style="display: none;">Password</label>

                    <input type="password" name="password" id="password"
                    placeholder="Password">

                    <input type="submit" name="submit" id="submit"
                    value="Log in" class="login-btn">
                </fieldset>
            </form>

            <a href="forgot_password.php" class="secondary-btn">Forgot password?</a>

            <a href="Regestaration.php" class="secondary-btn">You do not have an account? Create one</a>
        </div>
    </body>
</html>

