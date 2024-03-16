<?php
// Include the database connection
session_start();

require_once 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the JSON data sent from the client-side JavaScript
    $data = json_decode(file_get_contents("php://input"));

    // Extract report data from the JSON object
    $imageData = $data->image;
    $location = $data->location;
    $info = $data->info;
    $duration = $data->duration;
    $severity = $data->severity;
    $landmark = $data->landmark;
    $username = $_SESSION['username'];// Assuming username is stored in the session
    $userId = getUserIdByUsername($username);

    echo "Submitted By: " . $submittedByUsername . "<br>";

    // Convert base64 image data to file and save in uploads folder
    $imagePath = saveBase64Image($imageData);

    // Insert the report data into the database
    $sql = "INSERT INTO reports (image, location, info, duration, severity, landmark, status, submitted_by, submission_date) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, NOW())";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssss", $imagePath, $location, $info, $duration, $severity, $landmark, $userId);
        if ($stmt->execute()) {
            // Report inserted successfully
            // echo json_encode(array("message" => "Report submitted successfully."));
            echo json_encode(array("message" => "Report submitted successfully"));
        } else {
            // Error occurred while inserting report
            http_response_code(500); // Internal Server Error
            echo json_encode(array("error" => "Unable to submit report. Please try again later."));
        }
        $stmt->close();
    } else {
        // Error preparing SQL statement
        http_response_code(500); // Internal Server Error
        echo json_encode(array("error" => "Unable to prepare SQL statement."));
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("error" => "Invalid request method."));
}

// Close the database connection
$conn->close();

// Function to save base64 image data to file
function saveBase64Image($base64Data) {
    // Extract image data and MIME type from base64 string
    list($type, $data) = explode(';', $base64Data);
    list(, $data) = explode(',', $data);
    $data = base64_decode($data);

    // Generate unique filename for the image
    $filename = uniqid() . '.png'; // Assuming the image format is PNG

    // Specify the directory to save the image
    $uploadDir = 'uploads/';

    // Save the image to the specified directory
    file_put_contents($uploadDir . $filename, $data);

    // Return the path to the saved image
    return $uploadDir . $filename;
}

// Function to get user ID by username
function getUserIdByUsername($username) {
    global $conn;
    $sql = "SELECT user_id FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();
        return $userId;
    } else {
        return null;
    }
}
?>
