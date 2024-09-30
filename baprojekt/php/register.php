<?php

include('connect.php');  
include('../dashboard.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  

    // Check if email already exists
    $checkEmail = "SELECT * FROM user_info WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $error = 'Email already registered.';
    } else {
        // inserting the new user into database
        $sql = "INSERT INTO user_info (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php"); 
            exit();
        } else {
            $error = 'Error: ' . $conn->error;
        }
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
    <title>Register</title>
</head>
<body class="register-page">
    
    <div class="form-container">
        <h2>Register</h2>

        <!-- displaying error message if registration fails -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
