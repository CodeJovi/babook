<?php
include('connect.php'); 
include('../dashboard.php');

// check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the user id from the URL or default to the logged-in user
if (isset($_GET['user_id'])) {
    $user_id = $conn->real_escape_string($_GET['user_id']);
} else {
    $user_id = $_SESSION['user_id'];  // defaulting to logged-in user
}

// Fetch the user's details using prepared statement
$stmt_user = $conn->prepare("SELECT * FROM user_info WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Fetch user's books using prepared statement
$stmt_books = $conn->prepare("SELECT * FROM books WHERE user_id = ?");
$stmt_books->bind_param("i", $user_id);
$stmt_books->execute();
$result_books = $stmt_books->get_result();

// Generate CSRF token if it doesn’t exist in the session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile</title>
</head>
<body>
    
    <div class="container">
        <h2><?php echo htmlspecialchars($user['username']); ?>'s books</h2>
        <div class="books-container"> 
        <?php
        if ($result_books->num_rows > 0) {
            // Display the user's books
            while($row = $result_books->fetch_assoc()) {
                echo "<div class='book'>";
                echo "<h3>" . htmlspecialchars($row['book_name']) . "</h3>";
                echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
                echo "<p>Genres: " . htmlspecialchars($row['genres']) . "</p>";
                echo "<p>Will trade for: " . htmlspecialchars($row['will_trade_genres']) . "</p>";
                echo "<img src='" . htmlspecialchars($row['book_image']) . "' alt='" . htmlspecialchars($row['book_name']) . "' style='width:150px;height:200px;'>";

                // Show delete button only if the logged-in user is viewing their own profile
                if ($user_id == $_SESSION['user_id']) {
                    // Add delete button with CSRF token
                    echo "<form action='delete_book.php' method='POST' onsubmit='return confirmDelete()'>";
                    echo "<input type='hidden' name='book_id' value='" . htmlspecialchars($row['book_id']) . "'>";
                    echo "<input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token']) . "'>";  // Add CSRF token to form
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>This user has not uploaded any books yet.</p>";
        }

        $conn->close();
        ?>
    </div>
    
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this book?');
        }
    </script>

</body>
</html>
