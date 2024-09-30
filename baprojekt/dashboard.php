<?php

session_set_cookie_params([
    'httponly' => true,  // JavaScript will not be able to access session cookies
    'secure' => false,   // Set to false because running on localhost, (setting true when using HTTPS in production)
    'samesite' => 'Strict'  // cookie-prevention cross-site requests (mitigates CSRF)
]);

session_start();  // start the session to track if the user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">  
    <title>Homepage</title>
</head>
<body>

<div class="dashboard">
    <div class="left">
        <a href="/baprojekt/index.php">Home</a>
        
        <form action="/baprojekt/php/search_books.php" method="GET" style="margin-top: 10px;">
            <input type="text" name="query" placeholder="Search for books..." required>
            <button type="submit">Search</button>
        </form>
    </div>
    
    <div class="right">
    <?php if (isset($_SESSION['username'])): ?>
    
    <div class="dropdown">
        <a href="javascript:void(0)" class="dropbtn"><?php echo $_SESSION['username']; ?></a>
        <div class="dropdown-content">
            <a href="/baprojekt/php/upload_book.php">Upload New Book</a>
            <a href="/baprojekt/php/profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">My Books</a>
            <a href="/baprojekt/php/messages.php">My Messages</a>
        </div>
    </div>
    <a href="/baprojekt/php/logout.php" class="logout">Logout</a>
<?php else: ?>
    
    <a href="/baprojekt/php/login.php">Login</a>
    <a href="/baprojekt/php/register.php">Register</a>
<?php endif; ?>

    </div>
</div>

</body>
</html>