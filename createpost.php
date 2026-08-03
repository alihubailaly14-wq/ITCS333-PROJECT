<?php
session_start();
include 'connect.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = trim($_POST['title'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $user_id = $_SESSION['user_id'];
    $image_path = null;

    if (!empty($title) && !empty($comment)) {
        $post_text = $title . "\n\n" . $comment;
    } else {
        $post_text = !empty($title) ? $title : $comment;
    }

    $has_image = isset($_FILES['post']) && $_FILES['post']['error'] == UPLOAD_ERR_OK;

    if(empty($post_text) && !$has_image){
        $error = "A post cannot be empty. Please add text or upload an image!";
    }

    if(empty($error) && $has_image){
        $filetmppath   = $_FILES['post']['tmp_name'];
        $filename      = $_FILES['post']['name'];
        $fileparts     = explode('.', $_FILES['post']['name']);
        $extension     = strtolower(end($fileparts));

        $allowedext = ['jpg', 'png', 'jpeg'];

        if(in_array($extension, $allowedext)){
            $uploadDir = 'uploads/';
            
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0755, true);
            }

            $newfilename = uniqid('post_', true) . '.' . $extension;
            $destpath = $uploadDir . $newfilename;

            if (move_uploaded_file($filetmppath, $destpath)) {
                $image_path = $destpath;
            } else {
                $error = "There was an error moving the uploaded file.";
            }
        } else {
            $error = "Invalid file format. Only PNG and JPG files are allowed.";
        }
    }
    
    if(empty($error)){
        try {
            $stmt = $conn->prepare("INSERT INTO posts (user_id, post_text, image_path) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $post_text, $image_path]);
            
            header("Location: home.php?post=success");
            exit();
        } catch (PDOException $e) {
            $error = "Database error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Flogram - Create Post</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body class="Home">
        
        <nav class="sidebar">
            <h2 class="logo">Flogram</h2>
            <p class="subtitle">
            <?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['full_name'] ?? 'User'); ?>
            </p>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="createpost.php">Create Post</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="login.php">Log Out</a></li>
            </ul>
        </nav>

        <main class="feed">
            
            <div class="form-container">
                <h1>Create a New Post</h1>
                <p class="subtitle">Share with the people your new trip.</p>
                
                <?php if(!empty($error)): ?>
                    <p class="error-msg"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <label for="title">Title (Optional)</label>
                    <input type="text" name="title" id="title" placeholder="Enter a title...">
                    
                    <label for="comment">Caption</label>
                    <textarea name="comment" id="comment" rows="3" placeholder="What's on your mind?"></textarea>
                    
                    <!-- Cleaned up character counter using the disclaimer class -->
                    <div id="char-counter" class="disclaimer">0 / 280 characters</div>

                    <label for="post">Upload Image (JPG/PNG)</label>
                    <input type="file" name="post" id="post" accept=".jpg, .jpeg, .png">

                    <input type="submit" id="postSubmitBtn" name="submit" value="Post to Feed">
                </form>
            </div>

        </main>
        
        <script src="test.js"></script>
    </body>
</html>