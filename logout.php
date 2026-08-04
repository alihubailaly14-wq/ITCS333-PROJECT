<?php
setcookie("user_id", "", time() - 3600, "/");
setcookie("name", "", time() - 3600, "/");
setcookie("email", "", time() - 3600, "/");

header("Location: login.php");
exit();
?>
