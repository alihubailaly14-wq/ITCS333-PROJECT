<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    
    $flag = true;
    $message = "";
    
    if(empty($name) || empty($email) || empty($password) || empty($cpassword) || empty($_POST['agree'])){
        $flag = false;
        $message .= "Some fields are empty or policy not agreed! <br/>";
    }

    if(strlen($password) < 8){
        $flag = false;
        $message .= "Password must be at least 8 characters <br/>";
    }
    
    if(!($password == $cpassword)){
        $flag = false;
        $message .= "Password and Confirm password do not match! <br/>";
    }
    
    if($flag) {
        $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmail->execute([$email]);
        
        if ($checkEmail->rowCount() > 0) {
            $message = "This email is already registered! Please log in instead.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (email, password_hash, full_name) VALUES (?, ?, ?)");
            $hashedPassword = hash('sha256', $password); 
            $stmt->execute([$email, $hashedPassword, $name]); 
            
            header('Location: login.php?registered=1');
            exit();
        }
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
                <input type="text" name="name" id="name" placeholder="Full name">

                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="Email">

                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password">

                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password">

                <div class="checkbox-container">
                    <input type="checkbox" name="agree" id="agree">
                    <label for="agree">I agree to Flogram's Terms, Privacy Policy and Cookies Policy.</label>
                </div>
                
                <input type="submit" name="submit" id="submit" value="Submit">
            </fieldset>
        </form>

        <a href="login.php" class="secondary-btn">I already have an account</a>
        
        <?php if(!empty($message)): ?>
            <div class="error-msg" style="margin-top: 15px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="test.js"></script>
</body>
</html>