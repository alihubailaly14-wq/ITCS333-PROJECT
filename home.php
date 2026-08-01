<?php
session_start();
include 'connect.php';

$stmt = $conn->prepare("
    SELECT posts.post_id, posts.post_text, posts.image_path, posts.created_at, users.full_name 
    FROM posts 
    JOIN users ON posts.user_id = users.user_id 
    ORDER BY posts.created_at DESC
");
$stmt->execute();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flogram - Home</title>
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
        
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="subtitle">
                User Created! Welcome to Flogram.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['login']) && $_GET['login'] == 'success'): ?>
            <div class="subtitle">
                Welcome back, <?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['full_name'] ?? 'User'); ?>!
            </div>
        <?php endif; ?>
        
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <article class="post">
            <header class="post-header">
                <div class="user-info">
                    <div class="avatar"></div>
                    <a href="#" class="username"><?php echo htmlspecialchars($row['full_name']); ?></a>
                    <span class="timestamp">• <?php echo htmlspecialchars($row['created_at']); ?></span>
                </div>
                <a href="delete.php?id=<?php echo $row['post_id']; ?>" class="delete-btn">Delete</a>
            </header>
            
            <a href="post-detail.php?id=<?php echo $row['post_id']; ?>" class="post-link">
                <div class="post-image">
                    <?php if (!empty($row['image_path'])) { ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image">
                    <?php } else { ?>
                        <img src="https://placehold.co/600x400?text=No+Image" alt="No Image">
                    <?php } ?>
                </div>
                
                <div class="post-content">
                    <p>
                        <span class="username"><?php echo htmlspecialchars($row['full_name']); ?></span> 
                        <?php echo nl2br(htmlspecialchars($row['post_text'])); ?>
                    </p>
                </div>
            </a>
        </article>
        <?php } ?>

    </main>
</body>
</html>
