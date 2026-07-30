<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["cpassword"] ?? "";
    $birthday = $_POST["birthday"] ?? "";
    $username = trim($_POST["username"] ?? "");

    // Validate Name
    if ($name === "") {
        $errors['name'] = "Name is required.";
    }

    // Validate Email
    if ($email === "") {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } elseif (isset($_SESSION["registered_user"]["email"]) && strcasecmp($email, $_SESSION["registered_user"]["email"]) === 0) {
        $errors['email'] = "Email already exists.";
    }

    
    if ($password === "") {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters.";
    }

    
    if ($confirm === "") {
        $errors['cpassword'] = "Please confirm your password.";
    } elseif ($password !== $confirm) {
        $errors['cpassword'] = "Passwords do not match.";
    }


    if ($birthday === "") {
        $errors['birthday'] = "Birthday is required.";
    }

    
    if ($username === "") {
        $errors['username'] = "Username is required.";
    }

    
    if (!isset($_POST["agree"])) {
        $errors['agree'] = "You must agree to the terms.";
    } 
    
    
    if (empty($errors)) {
        $_SESSION["registered_user"] = [
            "name" => $name,
            "email" => $email,
            "username" => $username,
            "birthday" => $birthday,
            "password_hash" => password_hash($password, PASSWORD_DEFAULT)
        ];

       
        header("Location: home.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registration page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="Registration">
    <div class="form-container">
        <h1>Get started on Flogram with a new Account</h1>
        <p class="subtitle">A Flogram Account lets you access multiple features easily and securely.</p>

        <form method="post">
            <fieldset>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Full name" 
                value="<?= htmlspecialchars($_POST["name"] ?? "") ?>">
                
                <span class="error-msg"><?= $errors['name'] ?? '' ?></span>

                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="Email"
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                
                <span class="error-msg"><?= $errors['email'] ?? '' ?></span>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password">
                
                <span class="error-msg"><?= $errors['password'] ?? '' ?></span>

                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password">
                
                <span class="error-msg"><?= $errors['cpassword'] ?? '' ?></span>

                <label for="birthday">Birthday</label>
                <input type="date" name="birthday" id="birthday"
                value="<?= htmlspecialchars($_POST["birthday"] ?? "") ?>">
                
                <span class="error-msg"><?= $errors['birthday'] ?? '' ?></span>

                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Username"
                value="<?= htmlspecialchars($_POST["username"] ?? "") ?>">
                
                <span class="error-msg"><?= $errors['username'] ?? '' ?></span>

                <div class="checkbox-container">
                    <input type="checkbox" name="agree" id="agree" <?= isset($_POST["agree"]) ? "checked" : "" ?>>
                    <label for="agree">I agree to Flogram's Terms, Privacy Policy and Cookies Policy.</label>
                </div>
                
                <span class="error-msg"><?= $errors['agree'] ?? '' ?></span>

                <input type="submit" name="submit" id="submit" value="Submit">
            </fieldset>
        </form>

        <a href="login.php" class="secondary-btn">I already have an account</a>
    </div>
</body>

</html>
