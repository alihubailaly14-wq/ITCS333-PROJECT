<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$post_id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT posts.post_id, posts.post_text, posts.image_path, posts.created_at, posts.user_id, users.full_name 
    FROM posts 
    JOIN users ON posts.user_id = users.user_id 
    WHERE posts.post_id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Flogram - Post Details</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
    <body class="Home">
        
        <nav class="sidebar">
            <h2 class="logo">Flogram</h2>
            <ul class="nav-links">
                <li><a href="home.php"> Home</a></li>
                <li><a href="search.php"> Search</a></li>
                <li><a href="createpost.php"> Create Post</a></li>
                <li><a href="profile.php"> Profile</a></li>
                <li><a href="login.php"> Log Out</a></li>
            </ul>
        </nav>
        
        <main class="feed">
            
            <div class="back-link">
                <a href="home.php">⬅ Back to Feed</a>
            </div>
            
            <article class="post detail-post">
                <header class="post-header">
                    <div class="user-info">
                        <div class="avatar"></div>
                        <a href="#" class="username"><?php echo htmlspecialchars($post['full_name']); ?></a>
                        <span class="timestamp">• <?php echo htmlspecialchars($post['created_at']); ?></span>
                    </div>
                    
                    <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
                        <a href="delete.php?id=<?php echo $post['post_id']; ?>" class="delete-btn">Delete</a>
                    <?php endif; ?>
                </header>
                
                <div class="post-image">
                    <?php if (!empty($post['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image">
                    <?php else: ?>
                        <img src="https://placehold.co/600x400?text=No+Image" alt="No Image">
                    <?php endif; ?>
                </div>
                
                <div class="post-content">
                    <p>
                        <span class="username"><?php echo htmlspecialchars($post['full_name']); ?></span> 
                        <?php echo nl2br(htmlspecialchars($post['post_text'])); ?>
                    </p>
                </div>
            </article>

        </main>
        <script src="test.js"></script>

    </body>
</html>