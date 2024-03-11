<?php
// Include the database connection
require_once 'db.php';

// Process form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare an insert statement
    $sql = "INSERT INTO reports (image_url, additional_info, duration_of_damage, severity_of_damage, landmark)
            VALUES (:image_url, :additional_info, :duration_of_damage, :severity_of_damage, :landmark)";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind parameters
        $stmt->bindParam(':image_url', $_POST['image_url']);
        $stmt->bindParam(':additional_info', $_POST['additional_info']);
        $stmt->bindParam(':duration_of_damage', $_POST['duration_of_damage']);
        $stmt->bindParam(':severity_of_damage', $_POST['severity_of_damage']);
        $stmt->bindParam(':landmark', $_POST['landmark']);

        // Execute the statement
        if ($stmt->execute()) {
            // Report submitted successfully
            echo 'Report submitted successfully!';
        } else {
            // Error handling
            echo 'Oops! Something went wrong.';
        }
    }

    // Close statement
    unset($stmt);

    // Close connection
    unset($pdo);
}
?>
