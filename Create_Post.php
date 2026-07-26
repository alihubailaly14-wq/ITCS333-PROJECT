<!DOCTYPE html>
<html>

<head>
    <title>Create a new post</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="new-post">
    <div class="form-container">
        <h1>Create Your New Post</h1>
        <p clas="subtitle">Share with the peaple your new trip.</p>
        <form method="post">
            <label for="title">Enter your title of your vlog</label>
            <input type="text" name="title" id="title" placeholder="Title">
            <label for="upload">Upload the record of the vlog</label>
            <input type="file" name="post" id="post">
            <label for="comment">Any comment about the vlog</label>
            <textarea cols="15" rows="12"></textarea>
            <input type="submit" name="submit" id="submit">
        </form>
        <a href="Create_Post.php" class="secondary-btn">Undo</a>
    </div>
</body>

</html>