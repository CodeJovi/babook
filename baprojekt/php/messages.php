<?php
include('connect.php');
include('../dashboard.php');

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// The logged-in user's ID
$user_id = $_SESSION['user_id'];  

// prepared statement to prevent SQL Injection
$stmt = $conn->prepare("SELECT DISTINCT user_info.id AS other_user_id, user_info.username 
                        FROM messages 
                        JOIN user_info ON (messages.sender_id = user_info.id OR messages.receiver_id = user_info.id)
                        WHERE (messages.sender_id = ? OR messages.receiver_id = ?) 
                        AND user_info.id != ?");
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Your Conversations</title>
</head>
<body>
    <div class="container">
        <h2>Meine Nachrichten</h2>
        <div class="conversations">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='conversation'>";
                    echo "<p>Conversation with: " . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "<a href='conversation.php?user_id=" . htmlspecialchars($row['other_user_id'], ENT_QUOTES, 'UTF-8') . "'>View Conversation</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>You have no active conversations.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
// Closing statement and connection
$stmt->close();
$conn->close();
?>
