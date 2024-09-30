
<?php
include('php/connect.php'); 
include('dashboard.php'); 

// Fetch all books and user info from the database, ordered by upload date (latest first)
$sql = "SELECT books.book_name, books.author, books.genres, books.will_trade_genres, books.book_image, books.upload_date, user_info.username, user_info.id AS user_id 
        FROM books 
        JOIN user_info ON books.user_id = user_info.id
        ORDER BY books.upload_date DESC";
$result = $conn->query($sql);
/*
// Fetch all books and user info from the database
$sql = "SELECT books.book_name, books.author, books.genres, books.will_trade_genres, books.book_image, user_info.username, user_info.id AS user_id 
        FROM books 
        JOIN user_info ON books.user_id = user_info.id";
$result = $conn->query($sql);*/
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

<div class="container">
    <h1>BOOKSWAP</h1>

    <h2>Let's swap books!</h2>
    <div class="books-container">
        <?php
        if ($result->num_rows > 0) {
            // Loop through and display each book
            while($row = $result->fetch_assoc()) {
                echo "<div class='book'>";
                echo "<h3>" . htmlspecialchars($row['book_name']) . "</h3>";
                echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
                echo "<p>Genres: " . htmlspecialchars($row['genres']) . "</p>";
                echo "<p>Will trade for: " . htmlspecialchars($row['will_trade_genres']) . "</p>";

                // Display the username with a link to the user's profile page
                echo "<p>Uploaded by: <a href='php/profile.php?user_id=" . htmlspecialchars($row['user_id']) . "'>" . htmlspecialchars($row['username']) . "</a></p>";

                if (!empty($row['book_image'])) {
                    $imagePath = 'php/' . htmlspecialchars($row['book_image']);
                    echo "<img src='" . $imagePath . "' alt='" . htmlspecialchars($row['book_name']) . "'>";
                }

                  // Message button to start a conversation
                echo "<form action='php/conversation.php' method='GET'>";
                echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['user_id']) . "'>";  // Pass the user_id of the uploader
                echo "<button type='submit'>Message User</button>";
                echo "</form>";

                echo "</div>";
            }
        } else {
            echo "<p>No books have been uploaded yet.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
