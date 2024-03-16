<?php
// Include database connection
require_once 'db.php';

// Check if report ID is provided in the request
if(isset($_GET['id'])) {
    $report_id = $_GET['id'];

    // SQL query to delete the report
    $delete_sql = "DELETE FROM reports WHERE id = $report_id";

    // Execute the query
    if ($conn->query($delete_sql) === TRUE) {
        // Report deleted successfully
        echo '<script>alert("Report deleted successfully.");</script>';
    } else {
        // Error deleting report
        echo '<script>alert("Error deleting report: ' . $conn->error . '");</script>';
    }

    // Redirect back to the view report page
    echo '<script>window.location.href = "view_report.php";</script>';
} else {
    // If report ID is not provided in the request
    echo '<script>alert("Report ID not provided.");</script>';
    // Redirect back to the view report page
    echo '<script>window.location.href = "view_report.php";</script>';
}
?>
