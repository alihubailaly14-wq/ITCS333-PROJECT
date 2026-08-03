<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* 1. GET PHASE:
   Get the current user's ID from the Session
   and fetch their current information.
*/
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT * FROM users WHERE user_id = ?"
);
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";

/* 3. POST PHASE:
   When the user clicks Update,
   get the values from the form.
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['user_id'];
    $full_name = trim($_POST['update_name']); // Matched to form input name
    $email = trim($_POST['update_email']);    // Matched to form input name

    if ($id != $_SESSION['user_id']) {
        $message = "Invalid user.";
    } elseif (empty($full_name)) {
        $message = "Full name is required.";
    } elseif (empty($email)) {
        $message = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email.";
    } else {

        // Check if another user has the email
        $checkEmail = $conn->prepare(
            "SELECT user_id
             FROM users
             WHERE email = ? AND user_id != ?"
        );
        $checkEmail->execute([$email, $id]);

        if ($checkEmail->fetch()) {
            $message = "This email is already registered.";
        } else {

            /* 4. UPDATE QUERY:
               Update the user's name and email.
            */
            $update_stmt = $conn->prepare(
                "UPDATE users
                 SET full_name = ?, email = ?
                 WHERE user_id = ?"
            );
            $update_stmt->execute([$full_name, $email, $id]);

            $_SESSION['name'] = $full_name;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email;

            header("Location: profile.php?updated=1");
            exit();
        }
    }
}

/* Get User Posts */
$post_stmt = $conn->prepare("
    SELECT post_id, post_text,
           image_path, created_at
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
            <?php
            echo htmlspecialchars(
                $_SESSION['name'] ??
                $_SESSION['full_name'] ??
                'User'
            );
            ?>
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

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
            <div class="subtitle">
                Profile successfully updated!
            </div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="error-msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="profile-header">
            <div class="profile-avatar"></div>
            <div class="profile-name"><?php echo htmlspecialchars($row['full_name']); ?></div>
            <div class="profile-email"><?php echo htmlspecialchars($row['email']); ?></div>
            <div class="profile-stats">
                Joined: <?php echo htmlspecialchars(date('F j, Y', strtotime($row['created_at']))); ?>
            </div>
            
            <!-- Removed inline styles to let the secondary-btn class handle the layout natively -->
            <button id="toggleEditBtn" class="secondary-btn">Edit Profile</button>

            <!-- Kept display: none for the JavaScript toggle logic -->
            <div id="editFormContainer" style="display: none;">
                <form id="profileUpdateForm" method="POST" action="">
                    <fieldset>
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>">
                        
                        <label for="update_name">Full Name</label>
                        <input type="text" name="update_name" id="update_name" value="<?php echo htmlspecialchars($row['full_name']); ?>">

                        <label for="update_email">Email</label>
                        <input type="text" name="update_email" id="update_email" value="<?php echo htmlspecialchars($row['email']); ?>">

                        <input type="submit" value="Update Profile">
                    </fieldset>
                </form>
            </div>
        </div>

        <h3 class="section-title">Your Posts</h3>

        <?php if ($post_stmt->rowCount() == 0): ?>
            <div class="post">
                <div class="post-content subtitle">
                    You haven't shared any posts yet.
                </div>
            </div>
        <?php endif; ?>

        <?php while ($post = $post_stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <article class="post">
                <header class="post-header">
                    <div class="user-info">
                        <div class="avatar"></div>
                        <span class="username">
                            <?= htmlspecialchars($row['full_name']) ?>
                        </span>
                        <span class="timestamp">
                            • <?= htmlspecialchars($post['created_at']) ?>
                        </span>
                    </div>
                    <a href="delete.php?id=<?= $post['post_id'] ?>" class="delete-btn">Delete</a>
                </header>

                <a href="post-detail.php?id=<?= $post['post_id'] ?>" class="post-link">
                    <div class="post-image">
                        <?php if (!empty($post['image_path'])): ?>
                            <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
                        <?php else: ?>
                            <img src="https://placehold.co/600x400?text=No+Image" alt="No Image">
                        <?php endif; ?>
                    </div>

                    <div class="post-content">
                        <p>
                            <span class="username">
                                <?= htmlspecialchars($row['full_name']) ?>
                            </span>
                            <?= nl2br(htmlspecialchars($post['post_text'])) ?>
                        </p>
                    </div>
                </a>
            </article>
        <?php endwhile; ?>

    </main>

    <script src="test.js"></script>
</body>
</html>