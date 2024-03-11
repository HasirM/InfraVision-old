<?php
require_once 'db.php';

$username = $email_phone = $new_password = $confirm_password = '';
$username_err = $email_phone_err = $new_password_err = $confirm_password_err = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate username, email, or phone number
    $username = trim($_POST['username']);
    $email_phone = trim($_POST['email_phone']);

    // Validate new password
    $new_password = trim($_POST['new_password']);
    if (empty($new_password)) {
        $new_password_err = 'Please enter the new password.';
    } elseif (strlen($new_password) < 6) {
        $new_password_err = 'Password must have at least 6 characters.';
    }

    // Validate confirm password
    $confirm_password = trim($_POST['confirm_password']);
    if (empty($confirm_password)) {
        $confirm_password_err = 'Please confirm the password.';
    } elseif ($new_password != $confirm_password) {
        $confirm_password_err = 'Password does not match.';
    }

    // Check input errors before updating the password
    if (empty($username_err) && empty($email_phone_err) && empty($new_password_err) && empty($confirm_password_err)) {
        // Check if the username, email, or phone number exists in the database
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email OR phone_number = :phone_number";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email_phone);
            $stmt->bindParam(':phone_number', $email_phone);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    // Username, email, or phone number exists, update password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql_update = "UPDATE users SET password = :password WHERE username = :username OR email = :email OR phone_number = :phone_number";
                    $stmt_update = $pdo->prepare($sql_update);
                    $stmt_update->bindParam(':password', $hashed_password);
                    $stmt_update->bindParam(':username', $username);
                    $stmt_update->bindParam(':email', $email_phone);
                    $stmt_update->bindParam(':phone_number', $email_phone);

                    if ($stmt_update->execute()) {
                        // Password updated successfully
                        $success_message = 'Password updated successfully. Redirecting to login page...';
                        header("refresh:3;url=index.php");
                    } else {
                        echo 'Oops! Something went wrong. Please try again later.';
                    }
                } else {
                    $email_phone_err = 'No account found with that username, email, or phone number.';
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            unset($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label for="email_phone">Email/Phone:</label>
            <input type="text" id="email_phone" name="email_phone" value="<?php echo htmlspecialchars($email_phone); ?>">
            <span><?php echo $email_phone_err; ?></span>
        </div>
        <div>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password">
            <span><?php echo $new_password_err; ?></span>
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>
    <?php if (!empty($success_message)) : ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
</body>
</html>
