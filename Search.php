<?php

session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$search = "";
$results = array();

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    if ($search != "") {

        $stmt = $conn->prepare("
            SELECT posts.*, users.full_name
            FROM posts
            JOIN users ON posts.user_id = users.user_id
            WHERE posts.post_text LIKE ?
            ORDER BY posts.created_at DESC
        ");

        $stmt->execute(array("%" . $search . "%"));
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Flogram - Search</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="Home">

    <nav class="sidebar">

        <h2 class="logo">Flogram</h2>

        <p class="subtitle">
            <?php echo $_SESSION['name']; ?>
        </p>

        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="Search.php">Search</a></li>
            <li><a href="createpost.php">Create Post</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="login.php">Log Out</a></li>
        </ul>

    </nav>

    <main class="feed">

        <div class="create-post-box">

            <h2>Search Posts</h2>

            <form method="get" action="Search.php">

                <input
                    type="text"
                    name="search"
                    placeholder="Search posts..."
                    value="<?php echo $search; ?>"
                >

                <input type="submit" value="Search">

            </form>

        </div>

        <?php

        foreach ($results as $row) {

        ?>

            <article class="post">

                <header class="post-header">

                    <div class="user-info">

                        <div class="avatar"></div>

                        <a href="#" class="username">
                            <?php echo $row['full_name']; ?>
                        </a>

                        <span class="timestamp">
                            <?php echo $row['created_at']; ?>
                        </span>

                    </div>

                    <?php

                    if ($_SESSION['user_id'] == $row['user_id']) {

                    ?>

                        <a
                            href="delete.php?id=<?php echo $row['post_id']; ?>"
                            class="delete-btn"
                        >
                            Delete
                        </a>

                    <?php

                    }

                    ?>

                </header>

                <a
                    href="post-detail.php?id=<?php echo $row['post_id']; ?>"
                    class="post-link"
                >

                    <div class="post-image">

                        <?php

                        if (!empty($row['image_path'])) {

                        ?>

                            <img
                                src="<?php echo $row['image_path']; ?>"
                                alt="Post Image"
                            >

                        <?php

                        } else {

                        ?>

                            <img
                                src="https://placehold.co/600x400?text=No+Image"
                                alt="No Image"
                            >

                        <?php

                        }

                        ?>

                    </div>

                    <div class="post-content">

                        <p>

                            <span class="username">
                                <?php echo $row['full_name']; ?>
                            </span>

                            <?php echo $row['post_text']; ?>

                        </p>

                    </div>

                </a>

            </article>

        <?php

        }

        if ($search != "" && count($results) == 0) {

        ?>

            <p>No posts found.</p>

        <?php

        }

        ?>

    </main>

</body>

</html>