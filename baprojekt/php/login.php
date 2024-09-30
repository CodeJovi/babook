<?php
include('connect.php');
include('../dashboard.php');

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate E-Mail format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid CSRF token';
    } else {
        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT id, username, email, password FROM user_info WHERE email = ?");
        $stmt->bind_param("s", $email);
      
        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                header("Location: ../index.php");
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Login</title>
</head>
<body class="login-page">
    <div class="form-container">
        <h2>Login</h2>

        <!-- error message if login fails -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <p>Noch nicht registriert? <a href="register.php">Registriere dich hier</a></p>
    </div>
</body>
</html>
