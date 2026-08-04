<?php

include 'connect.php';

if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$post_id = $_GET['id'];
$user_id = $_COOKIE['user_id'];

$stmt = $conn->prepare("
    DELETE FROM posts
    WHERE post_id = ?
    AND user_id = ?
");

$stmt->execute([$post_id, $user_id]);

header("Location: home.php?delete=success");
exit();

?>