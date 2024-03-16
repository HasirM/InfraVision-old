<?php
// Include the database connection
require_once 'db.php';

// Initialize variables
$username = $password = $confirm_password = $email = $phone_number = '';
$username_err = $password_err = $confirm_password_err = $email_err = $phone_number_err = '';

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
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = 'Password must have at least 6 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm your password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate phone number
    if (empty(trim($_POST['phone_number']))) {
        $phone_number_err = 'Please enter your phone number.';
    } else {
        $phone_number = trim($_POST['phone_number']);
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($phone_number_err)) {
        $sql = "INSERT INTO users (username, email, password, phone_number) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $param_username, $param_email, $param_password, $param_phone_number);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
            $param_phone_number = $phone_number;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header('location: index.php');
                exit();
            } else {
                $error_message = 'Something went wrong. Please try again later.';
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
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
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
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
            <span><?php echo $phone_number_err; ?></span>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
</body>
</html>
