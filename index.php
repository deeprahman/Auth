<?php
    include './resource/session.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Homepage</title>
    </head>
    <body>
        <h2>User Authentication System</h2><hr>
        
        <?php if(!isset($_SESSION['username'])): ?>
        <p>
            You are currently not signed in, <a href="login.php">Login.</a> Not a member?
            <a href="signup.php">Sign up</a>
        </p>
        <?php else:?>
        <p>
            You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username'];?>
            <a href="logout.php">Log out.</a>
        </p>
        <?php endif?>
    </body>
</html>
