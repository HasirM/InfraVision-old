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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Report Details</h2>
        <table>
            <tr>
                <th>ID</th>
                <td><?php echo $report['id']; ?></td>
            </tr>
            <tr>
                <th>Image</th>
                <td><img src="<?php echo $report['image']; ?>" alt="Report Image" width="200"></td>
            </tr>
            <tr>
                <th>Location</th>
                <td><?php echo $report['location']; ?></td>
            </tr>
            <tr>
                <th>Additional Info</th>
                <td><?php echo $report['info']; ?></td>
            </tr>
            <tr>
                <th>Duration</th>
                <td><?php echo $report['duration']; ?></td>
            </tr>
            <tr>
                <th>Severity</th>
                <td><?php echo $report['severity']; ?></td>
            </tr>
            <tr>
                <th>Landmark</th>
                <td><?php echo $report['landmark']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $report['status']; ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
