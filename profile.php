<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_stmt = $conn->prepare("SELECT full_name, email, created_at FROM users WHERE user_id = ?");
$user_stmt->execute([$user_id]);
$user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

$post_stmt = $conn->prepare("
    SELECT post_id, post_text, image_path, created_at 
    FROM posts 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$post_stmt->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Flogram - Profile</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
    <body class="Home">
        
        <nav class="sidebar">
            <h2 class="logo">Flogram</h2>

            <p class="subtitle">
            <?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['full_name'] ?? 'User'); ?>
            </p>
            
            <ul class="nav-links">
                <li><a href="home.php"> Home</a></li>
                <li><a href="search.php"> Search</a></li>
                <li><a href="createpost.php"> Create Post</a></li>
                <li><a href="profile.php"> Profile</a></li>
                <li><a href="login.php"> Log Out</a></li>
            </ul>
        </nav>
        
        <main class="feed">
            
            <div class="profile-header">
                <div class="profile-avatar"></div>
                <div class="profile-name"><?php echo htmlspecialchars($user_data['full_name']); ?></div>
                <div class="profile-email"><?php echo htmlspecialchars($user_data['email']); ?></div>
                <div class="profile-stats">
                    Joined: <?php echo htmlspecialchars(date('F j, Y', strtotime($user_data['created_at']))); ?>
                </div>
            </div>

            <h3 class="section-title">Your Posts</h3>

            <?php if ($post_stmt->rowCount() == 0): ?>
                <div style="text-align: center; color: #8e8e8e; padding: 40px; background: #ffffff; border: 1px solid #dbdbdb; border-radius: 8px;">
                    You haven't shared any posts yet.
                </div>
            <?php endif; ?>
            
            <?php while($post = $post_stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <article class="post">
                <header class="post-header">
                    <div class="user-info">
                        <div class="avatar"></div>
                        <span class="username"><?php echo htmlspecialchars($user_data['full_name']); ?></span>
                        <span class="timestamp">• <?php echo htmlspecialchars($post['created_at']); ?></span>
                    </div>
                    
                    <a href="delete.php?id=<?php echo $post['post_id']; ?>" class="delete-btn" style="text-decoration: none;">Delete</a>
                </header>
                
                <a href="post-detail.php?id=<?php echo $post['post_id']; ?>" class="post-link">
                    <div class="post-image">
                        <?php if (!empty($post['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                        <?php else: ?>
                            <div style="padding: 20px; background: #f8f9fa; border-top: 1px solid #dbdbdb; border-bottom: 1px solid #dbdbdb;"></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="post-content">
                        <p>
                            <span class="username"><?php echo htmlspecialchars($user_data['full_name']); ?></span> 
                            <?php echo nl2br(htmlspecialchars($post['post_text'])); ?>
                        </p>
                    </div>
                </a>
            </article>
            <?php endwhile; ?>

        </main>

    </body>
</html>