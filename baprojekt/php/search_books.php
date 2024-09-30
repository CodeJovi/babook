<?php
include('connect.php');  
include('../dashboard.php');

if (isset($_GET['query'])) {
    $search_query = $_GET['query'];  

// prepared statements preventing SQL injection
    $stmt = $conn->prepare("SELECT * FROM books WHERE book_name LIKE ? OR author LIKE ?");
    $search_param = "%$search_query%";
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Search Results</title>
</head>
<body>
<div class="container">
    <h2>Search Results for "<?php echo htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); ?>"</h2>

    <div class="books-container">
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='book'>";
                echo "<h3>" . htmlspecialchars($row['book_name'], ENT_QUOTES, 'UTF-8') . "</h3>";
                echo "<p>Author: " . htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>Genres: " . htmlspecialchars($row['genres'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>Will trade for: " . htmlspecialchars($row['will_trade_genres'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<img src='" . htmlspecialchars($row['book_image'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row['book_name'], ENT_QUOTES, 'UTF-8') . "' style='width:150px;height:200px;'>";
                echo "</div>";
            }
        } else {
            echo "<p>No books found matching your search.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
