<?php

    // 1. Error Reporting: These lines tell PHP to show every mistake on the screen.
    // This is great for students while coding, but should be removed in real websites.
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
        
    // 2. Connection Credentials: The "Address" and "Keys" to your database.
    $host = "localhost";    // Where the database lives (usually your own computer)
    $user = "root";         // The master username for the database
    $password = ""; // The password for that user
    $database = "project_db";   // The specific database name we want to talk to

    // 3. The Try-Catch Block: This is like a safety net. 
    // We "TRY" to connect; if it fails, the "CATCH" block handles the error gracefully.
    try {
        /* PDO (PHP Data Objects) is a secure way to talk to databases.
           We create a new connection object ($conn) by passing the host, 
           database name, username, and password.
        */
        $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);

        /* Set Error Mode: This line tells PDO to "throw an exception" (scream) 
           whenever a SQL query has a typo or a table is missing. 
           Without this, some errors might stay silent and leave you wondering why it failed.
        */
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // (Optional) You could echo "Connected!" here just to test, then remove it.

    } catch (PDOException $e) {
        // 4. If something goes wrong (wrong password, db doesn't exist), 
        // we stop the script (die) and show the specific error message.
        die("Connection failed: " . $e->getMessage());
    }
?>