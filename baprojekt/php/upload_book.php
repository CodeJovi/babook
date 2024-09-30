<?php
// Include necessary files
include('connect.php');  
include('../dashboard.php');  

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Generate a CSRF token if it doesn't already exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check CSRF token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<p>Invalid CSRF token.</p>";
        exit();
    }

    // Retrieve and sanitize form input using prepared statements
    $book_name = $_POST['book_name'];
    $author = $_POST['author'];

    // Handle multiple genres if selected
    if (is_array($_POST['genres'])) {
        $genres = implode(', ', $_POST['genres']);
    } else {
        $genres = $_POST['genres'];
    }

    $will_trade_genres = $_POST['will_trade_genres'];

    // Handle file upload
    $target_dir = "uploads/";  
    $imageFileType = strtolower(pathinfo($_FILES["book_image"]["name"], PATHINFO_EXTENSION));

    // Limit file size to 5MB
    $max_file_size = 5000000; // 5MB
    if ($_FILES['book_image']['size'] > $max_file_size) {
        echo "<p>The file is too large. Maximum file size is 5MB.</p>";
        exit();
    }

    // MIME type validation using Fileinfo
    $mime_type = mime_content_type($_FILES['book_image']['tmp_name']);
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($mime_type, $allowed_types)) {
        echo "<p>Only JPG, PNG, and GIF files are allowed. The file uploaded is not a valid image.</p>";
        exit();
    }

    // Rename the file to avoid potential attacks
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES["book_image"]["tmp_name"], $target_file)) {
        // Set secure permissions on the uploaded file
        chmod($target_file, 0644);

        // Insert book data into the database using prepared statements
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO books (user_id, book_name, author, genres, will_trade_genres, book_image) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $user_id, $book_name, $author, $genres, $will_trade_genres, $target_file);

        if ($stmt->execute()) {
            header("Location: profile.php");  // Redirect to profile after a successful upload
            exit();
        } else {
            // Log the error, but show a generic message to the user
            error_log("Database error: " . $stmt->error);
            echo "<p>Sorry, there was an error processing your request.</p>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<p>Sorry, there was an error uploading your file.</p>";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Upload Book</title>
</head>
<body class="upload-page">
    <div class="form-container">
        <h2>Upload Your Book</h2>

        <form method="POST" action="upload_book.php" enctype="multipart/form-data">
            <label for="book_name">Name of Book:</label>
            <input type="text" id="book_name" name="book_name" required>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>

            <label for="genres">Genre:</label>
            <select id="genres" name="genres" required>
                <option value="Roman">Novel</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Klassiker">Classics</option>
                <option value="Science Fiction">Science Fiction</option>
                <option value="Krimi & Thriller">Crime novels & Thrillers</option>
                <option value="Romantik">Romance</option>
                <option value="Sachbuch">Non-Fiction</option>
            </select>

            <label for="will_trade_genres">Will trade for following books:</label>
            <input type="text" id="will_trade_genres" name="will_trade_genres" placeholder="Enter your preference" required>

            <label for="book_image">Upload Picture of the Book:</label>
            <input type="file" id="book_image" name="book_image" accept="image/*" required>

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <input type="submit" value="Upload Book">
        </form>
    </div>
</body>
</html>
