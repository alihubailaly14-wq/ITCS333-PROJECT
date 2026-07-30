<?php

$posts = [
    [
        'post_id' => 1,
        'user_id' => 1,
        'author' => 'Ali Hubail',
        'post_text' => 'This is my first blog post.',
        'image_path' => 'https://placehold.co/600x350',
        'created_at' => '2026-07-28 10:30:00'
    ],
    [
        'post_id' => 2,
        'user_id' => 2,
        'author' => 'Ahmed Mohammed',
        'post_text' => 'Welcome to our community blog.',
        'image_path' => 'https://placehold.co/600x350',
        'created_at' => '2026-07-30 18:45:00'
    ],
    [
        'post_id' => 3,
        'user_id' => 1,
        'author' => 'Ali Hubail',
        'post_text' => 'Today I learned about PHP arrays.',
        'image_path' => 'https://placehold.co/600x350',
        'created_at' => '2026-07-29 14:20:00'
    ]
];

function sortPostsByNewest($posts)
{
    usort($posts, function ($post1, $post2) {
        return strtotime($post2['created_at'])
            - strtotime($post1['created_at']);
    });

    return $posts;
}

$posts = sortPostsByNewest($posts);

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
            <ul class="nav-links">
                <li><a href="home.php"> Home</a></li>
                <li><a href="search.php"> Search</a></li>
                <li><a href="Create_Post.php"> Create Post</a></li> 
                <li><a href="profile.php"> Profile</a></li>
                <li><a href="login.php"> Log Out</a></li>
            </ul>
        </nav>

        <main class="feed">

        <!-- هنا عدلت الكود عشان يعرض كل البوستات -->
<?php foreach ($posts as $post): ?>

    <article class="post">

        <header class="post-header">
            <div class="user-info">

                <div class="avatar"></div>

                <a href="#" class="username">
                    <?php echo htmlspecialchars($post['author']); ?>
                </a>

                <span class="timestamp">
                    • <?php echo htmlspecialchars($post['created_at']); ?>
                </span>

            </div>

            <button class="delete-btn">Delete</button>
        </header>

        <a
            href="post-detail.php?id=<?php echo $post['post_id']; ?>"
            class="post-link"
        >

            <div class="post-image">
                <img
                    src="<?php echo htmlspecialchars($post['image_path']); ?>"
                    alt="Post Image"
                >
            </div>

            <div class="post-content">
                <p>
                    <span class="username">
                        <?php echo htmlspecialchars($post['author']); ?>
                    </span>

                    <?php echo htmlspecialchars($post['post_text']); ?>
                </p>
            </div>

        </a>

    </article>

    <!-- هنا خلص عرض كل البوستات -->

<?php endforeach; ?>

        </main>

    </body>
</html>