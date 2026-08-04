<?php
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

            setcookie("user_id", $user["user_id"], time() + 3600, "/");
            setcookie("name", $user["full_name"], time() + 3600, "/");
            setcookie("email", $user["email"], time() + 3600, "/");

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
                <p class="error-msg"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="post">
                <fieldset>
                    <label for="login_id">Email</label>
                    <input type="text" name="login_id" id="login_id"
                    placeholder="Email"
                    value="<?= htmlspecialchars($_POST["login_id"] ?? "") ?>">

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"
                    placeholder="Password">

                    <input type="submit" name="submit" id="submit"
                    value="Log in">
                </fieldset>
            </form>

            <a href="Registration.php" class="secondary-btn">You do not have an account? Create one</a>
        </div>

        <script src="test.js"></script>
    </body>
</html>
