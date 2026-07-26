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
                <li><a href="create.php"> Create Post</a></li>
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
                        <a href="#" class="username">Ali Hubail</a>
                        <span class="timestamp">• 1 w</span>
                    </div>
                    
                    <button class="delete-btn">Delete</button>
                </header>
                
                
                <div class="post-image">
                    <img src="https://placehold.co/100?text=Vlog" alt="Post Image">
                </div>
                
                <div class="post-content">
                    <p><span class="username">Ali Hubail</span> This is the full detail view of the post. It includes the author, the timestamp, the uploaded picture, and this text description. The user can also delete the post from this view.</p>
                </div>
            </article>

        </main>

    </body>
</html>