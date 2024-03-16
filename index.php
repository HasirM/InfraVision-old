<?php
// Start the session
session_start();

// Include the database connection
require_once 'db.php';

// Check if the user is already logged in, redirect to home page if logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SESSION['role'] === 'user') {
        header('location: home.php');
    } elseif ($_SESSION['role'] === 'admin') {
        header('location: view_report.php');
    } elseif ($_SESSION['role'] === 'govt') {
        header('location: view_report.php');
    }
    exit;
}

// Initialize variables
$username = $password = '';
$username_err = $password_err = '';

// Process form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter your username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Check input errors before logging in
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT user_id, username, password, role FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($user_id, $username, $hashed_password, $role);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $role;

                            // Redirect user based on role
                            if ($role === 'user') {
                                header('location: home.php');
                            } elseif ($role === 'admin') {
                                header('location: view_report.php');
                            } elseif ($role === 'govt') {
                                header('location: view_report.php');
                            }
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered is not valid.';
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    <p>Forgot your password? <a href="forgot_password.php">Reset here</a>.</p>
</body>
</html>
