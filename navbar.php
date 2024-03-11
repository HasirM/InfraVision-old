<?php
if (session_status() === PHP_SESSION_NONE) {
    // Start the session
    session_start();
}
// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // User is logged in
    $username = $_SESSION['username'];
    $welcome_message = "Welcome, $username!";
    $logout_button = '<a href="logout.php">Logout</a>';
} else {
    // User is not logged in
    $welcome_message = 'Welcome!';
    $logout_button = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <style>
        /* Add your CSS styles for the navigation bar here */
        /* Example styles */
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
        }

        .welcome-message {
            float: left;
            margin-right: 20px;
        }

        .logout-button {
            float: right;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="welcome-message"><?php echo $welcome_message; ?></div>
        <div class="logout-button"><?php echo $logout_button; ?></div>
    </div>
</body>
</html>
