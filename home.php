<?php 

// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: index.php');
    exit;
}

include 'navbar.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfraVision</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div id="homeTitle">
            <h1>Welcome to InfraVision</h1>
            <h4>InfraVision is a user-friendly web-based application designed to streamline the process of reporting road damages. Our platform empowers users to easily capture images of road damages, submit detailed reports, and view previously submitted incidents.</h4>

            <h2>Key Features:</h2>

            <h4>Capture Images:</h4> Use our intuitive interface to capture clear images of road damages directly from your device. <br> 
            <h4>Submit Reports:</h4> Provide additional information such as damage severity, duration, and nearby landmarks to create comprehensive reports. <br>
            <h4>View Reports:</h4> Access a list of all submitted reports to stay informed about road damage incidents in your area. <br>

            <h4>Whether you're a concerned citizen or a road maintenance authority, InfraVision simplifies the reporting process, facilitating efficient communication and timely resolution of road-related issues.</h4>

            <button id="openCameraBtn">Open Camera</button>

            <h4>Start using InfraVision today and contribute to safer roads in your community!</h4>
        </div>
        <!-- <form id="reportSubmissionForm" action="submit_report.php" method="post" enctype="multipart/form-data"> -->
        <div id="cameraContainer" class="hidden">
            <video id="cameraFeed" autoplay></video>
            <button id="captureBtn" class="hidden">Capture Image</button>
        </div>
        <div id="confirmationContainer" class="hidden">
            <img id="capturedImage">
            <button id="retakeBtn">Retake Image</button>
            <button id="proceedBtn">Proceed</button>
        </div>
        <div id="reportForm" class="hidden">
            <h2>Report Form</h2>
            <div id="capturedImageContainer"></div>
            <button id="retakeImageBtn" class="hidden">Retake Image</button>
            <p id="date"></p>
            <p>Your Location:<span id="location"></span></p>
            <label for="additionalInfo">Additional Information:</label>
            <textarea id="additionalInfo" placeholder="Enter additional information..."></textarea>
            <label for="damageDuration">Duration of Damage:</label>
            <input type="text" id="damageDuration" placeholder="Enter duration of damage...">
            <label for="damageSeverity">Severity of Damage:</label>
            <input type="text" id="damageSeverity" placeholder="Enter severity of damage...">
            <label for="landmark">Landmark:</label>
            <input type="text" id="landmark" placeholder="Enter landmark...">
            <button type="submit" id="submitReportBtn">Submit</button>

              <!-- Error message -->
              <p id="errorMessage" class="error-message hidden">Please fill in all the required fields.</p>
              
        </div>
<!-- </form> -->

<!-- Modal -->
<div id="modal" class="modal">
  <div id="modal-content" class="modal-content">
    <span id="modal-message"></span>
  </div>
</div>

        <div id="viewReportPage" class="hidden">
            <h2>View Report</h2>
            <div id="reportDetails"></div>
            <button id="homeBtn">Home</button>
        </div>
        <div id="submitConfirmation" class="hidden">
            <p>Report submitted successfully!</p>
        </div>
    </div>
    <script src="script.js"></script>
    <!-- <script src="report_handling.js"></script> -->
</body>
</html>
