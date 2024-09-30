<?php
include('connect.php');  // Connect to the database
include('../dashboard.php');  

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];  // The logged-in user's ID
$other_user_id = isset($_GET['user_id']) ? $conn->real_escape_string($_GET['user_id']) : 0;

// Fetch the username of the other user
$sql_other_user = "SELECT username FROM user_info WHERE id = '$other_user_id'";
$result_other_user = $conn->query($sql_other_user);
if ($result_other_user->num_rows > 0) {
    $other_user = $result_other_user->fetch_assoc();
    $other_username = htmlspecialchars($other_user['username']);  // Sanitize the username
} else {
    echo "User not found.";
    exit();
}

// Fetch the conversation between the logged-in user and the other user
$sql = "SELECT messages.*, user_info.username AS sender_name 
        FROM messages
        JOIN user_info ON messages.sender_id = user_info.id
        WHERE (messages.sender_id = '$user_id' AND messages.receiver_id = '$other_user_id') 
        OR (messages.sender_id = '$other_user_id' AND messages.receiver_id = '$user_id')
        ORDER BY messages.timestamp ASC";
$result = $conn->query($sql);

// Handle sending a new message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $conn->real_escape_string($_POST['message']);
    
    // Insert the new message into the database
    $sql_reply = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$user_id', '$other_user_id', '$message')";
    
    if ($conn->query($sql_reply) === TRUE) {
        header("Location: conversation.php?user_id=$other_user_id");  // Refresh the page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Conversation</title>
</head>
<body>
    <div class="container">
        <h2>Conversation with <?php echo htmlspecialchars($other_username); ?></h2>
        <div class="conversation-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='message'>";
                    if ($row['sender_id'] == $user_id) {
                        echo "<p><strong>You:</strong> " . htmlspecialchars($row['message']) . "</p>";
                    } else {
                        echo "<p><strong>" . htmlspecialchars($row['sender_name']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
                    }
                    echo "<p><small>Sent on: " . htmlspecialchars($row['timestamp']) . "</small></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No messages found in this conversation.</p>";
            }
            ?>
        </div>

        <h3>Send a Reply</h3>
        <form method="POST" action="conversation.php?user_id=<?php echo $other_user_id; ?>">
            <textarea name="message" placeholder="Enter your message..." required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>
