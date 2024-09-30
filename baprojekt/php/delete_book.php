
<?php

include('../dashboard.php');
include('connect.php'); 
/*
// Check if book_id is set in POST
if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Directly using the book_id in the SQL query (insecure)
    $sql = "DELETE FROM books WHERE book_id = '$book_id'";

    // Check if the connection is valid and execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Book deleted successfully.";
        
        header("Location: profile.php");
    } else {
        echo "Error deleting book: " . $conn->error;
    }
} else {
    echo "No book ID provided.";

}

*/

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a book.");
}

// Check if the CSRF token is valid
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed.");
}

// Check if the book ID is set in the POST request
if (isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];  // Typecast to prevent SQL injection
    $user_id = $_SESSION['user_id'];    // Logged-in user ID

    // Check if the logged-in user owns the book before deleting
    $stmt = $conn->prepare("SELECT user_id FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();

        // Check if the logged-in user is the owner of the book
        if ($book['user_id'] == $user_id) {
            // Proceed with deleting the book
            $stmt_delete = $conn->prepare("DELETE FROM books WHERE book_id = ?");
            $stmt_delete->bind_param("i", $book_id);

            if ($stmt_delete->execute()) {
                echo "Book deleted successfully.";
                header("Location: profile.php");
                exit();
            } else {
                echo "Error deleting book: " . $conn->error;
            }
        } else {
            die("You do not have permission to delete this book.");
        }
    } else {
        die("Book not found.");
    }
} else {
    die("No book ID provided.");
}

$conn->close();
?>
