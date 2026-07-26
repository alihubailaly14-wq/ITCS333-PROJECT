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
            
            
<article class="post">

    <header class="post-header">
        <div class="user-info">
            <div class="avatar"></div>
            <a href="#" class="username">Ali Hubail</a>
            <span class="timestamp">• 1 w</span>
        </div>
        <button class="delete-btn">Delete</button>
    </header>
    
    <a href="post-detail.php?" class="post-link">
        <div class="post-image">
            <img src="https://placehold.co/100?text=Vlog" alt="Post Image">
        </div>
        
        <div class="post-content">
            <p><span class="username">Ali Hubail</span> This is a simple caption for the Flogram post. Clicking the image or this text will take you to the detail view.</p>
        </div>
    </a>
</article>

        </main>

    </body>
</html>