<?php
// Include database connection
require_once 'db.php';

// Check if the report ID is provided in the URL
if (isset($_GET['id'])) {
    $report_id = $_GET['id'];

    // Fetch the report details from the database based on the report ID
    $sql = "SELECT * FROM reports WHERE id = $report_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the report details
        $report = $result->fetch_assoc();
    } else {
        // If no report found with the provided ID, display an error message
        echo "No report found with the ID: $report_id";
        exit; // Stop further execution
    }
} else {
    // If no report ID provided in the URL, display an error message
    echo "Report ID is not provided.";
    exit; // Stop further execution
}

// Check if the form is submitted for updating the report
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated data from the form fields
    $location = $_POST['location'];
    $additional_info = $_POST['additional_info'];
    $duration = $_POST['duration'];
    $severity = $_POST['severity'];
    $status = $_POST['status'];
    $landmark = $_POST['landmark'];

    // Prepare and execute the SQL update statement
    $update_sql = "UPDATE reports SET 
        info = '$additional_info', 
        duration = '$duration', 
        severity = '$severity', 
        status = '$status', 
        landmark = '$landmark' 
        WHERE id = $report_id";

    if ($conn->query($update_sql) === TRUE) {
        echo '<script>alert("Report successfully updated.");</script>';

    // Wait for 3 seconds and then redirect to the view report page
    header("refresh:3; url=view_report.php");
    exit;
    } else {
        // JavaScript code to display an error alert box
    echo '<script>alert("Error updating report: ' . $conn->error . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            margin-top: 10px;
            padding: 8px;
            box-sizing: border-box;
        }
        img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Report</h2>
        <img src="<?php echo $report['image']; ?>" alt="Report Image">
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $report_id; ?>">
            <div>
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo $report['location']; ?>" readonly>
            </div>
            <div>
                <label for="additional_info">Additional Information:</label>
                <textarea id="additional_info" name="additional_info"><?php echo $report['info']; ?></textarea>
            </div>
            <div>
                <label for="duration">Duration of Damage:</label>
                <input type="text" id="duration" name="duration" value="<?php echo $report['duration']; ?>">
            </div>
            <div>
                <label for="severity">Severity of Damage:</label>
                <input type="text" id="severity" name="severity" value="<?php echo $report['severity']; ?>">
            </div>
            <div>
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="Pending" <?php if ($report['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="In Progress" <?php if ($report['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Resolved" <?php if ($report['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                </select>
            </div>
            <div>
                <label for="landmark">Landmark:</label>
                <input type="text" id="landmark" name="landmark" value="<?php echo $report['landmark']; ?>">
            </div>
            <button type="submit">Update Report</button>
        </form>
    </div>
</body>
</html>
