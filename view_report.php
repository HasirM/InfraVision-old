<?php
// Start the session

session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: index.php');
    exit;
}

include 'navbar.php'; 


// Check the role of the logged-in user
$role = $_SESSION['role'];

// Include database connection
require_once 'db.php';

// Fetch reports from the database
$sql = "SELECT * FROM reports";
$result = $conn->query($sql);

// Check if there are any reports
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
} else {
    $reports = []; // No reports found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>View Reports</h2>
    <!-- Search Bar -->
    <input type="text" id="searchInput" onkeyup="searchReports()" placeholder="Search...">
    <br><br>
    <table id="reportTable">
        <thead>
            <tr>
                <th onclick="sortTable(0)">ID</th>
                <th onclick="sortTable(1)">Image</th>
                <th onclick="sortTable(2)">Location</th>
                <th onclick="sortTable(3)">Additional Info</th>
                <th onclick="sortTable(4)">Duration</th>
                <th onclick="sortTable(5)">Severity</th>
                <th onclick="sortTable(6)">Landmark</th>
                <th onclick="sortTable(7)">Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo $report['id']; ?></td>
                    <td><img src="<?php echo $report['image']; ?>" alt="Report Image" width="100"></td>
                    <td><?php echo $report['location']; ?></td>
                    <td><?php echo $report['info']; ?></td>
                    <td><?php echo $report['duration']; ?></td>
                    <td><?php echo $report['severity']; ?></td>
                    <td><?php echo $report['landmark']; ?></td>
                    <td><?php echo $report['status']; ?></td>
                    <td>
                        <?php if ($role == 'admin'): ?>
                            <button onclick="viewReport(<?php echo $report['id']; ?>)">View</button>
                            <button onclick="editReport(<?php echo $report['id']; ?>)">Edit</button>
                            <button onclick="deleteReport(<?php echo $report['id']; ?>)">Delete</button>
                        <?php elseif ($role == 'govt'): ?>
                            <button onclick="viewReport(<?php echo $report['id']; ?>)">View</button>
                            <button onclick="editReport(<?php echo $report['id']; ?>)">Edit</button>
                        <?php else: ?>
                            <button onclick="viewReport(<?php echo $report['id']; ?>)">View</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Function to search reports
        function searchReports() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("reportTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break; // Break out of inner loop if found
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        }

        // Function to sort reports
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("reportTable");
            switching = true;
            dir = "asc"; // Set the sorting direction to ascending by default
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[n];
                    y = rows[i + 1].getElementsByTagName("td")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }

        function viewReport(id) {
            window.location.href = "show_report.php?id=" + id;
        }

        // Function to edit report
        function editReport(id) {
            window.location.href = "edit_report.php?id=" + id;
        }

        // Function to delete report
        function deleteReport(reportId) {
        if (confirm("Are you sure you want to delete this report?")) {
            window.location.href = "delete_report.php?id=" + reportId;
        }
    }
    </script>
</body>
</html>
