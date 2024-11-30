<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CR Management Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>CR Management Dashboard</h1>

        <!-- Filter Form -->
        <form method="GET" action="">
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="">All</option>
                <option value="unassigned" <?php if (isset($_GET['status']) && $_GET['status'] == 'unassigned') echo 'selected'; ?>>Unassigned</option>
                <option value="assigned" <?php if (isset($_GET['status']) && $_GET['status'] == 'assigned') echo 'selected'; ?>>Assigned</option>
                <option value="in_progress" <?php if (isset($_GET['status']) && $_GET['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                <option value="completed" <?php if (isset($_GET['status']) && $_GET['status'] == 'completed') echo 'selected'; ?>>Completed</option>
            </select>

            <label for="completion_status">Completion Status:</label>
            <select name="completion_status" id="completion_status">
                <option value="">All</option>
                <option value="pending" <?php if (isset($_GET['completion_status']) && $_GET['completion_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="completed" <?php if (isset($_GET['completion_status']) && $_GET['completion_status'] == 'completed') echo 'selected'; ?>>Completed</option>
            </select>

            <button type="submit">Filter</button>
        </form>

        <table id="crsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Raised By</th>
                    <th>Raise Time</th>
                    <th>Status</th>
                    <th>Assign Time</th>
                    <th>Assigned To</th>
                    <th>Completion Status</th>
                    <th>Labels</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Build the SQL query with filters
                $sql = "SELECT * FROM crs WHERE 1=1"; // Start with a base query

                // Add status filter if set
                if (isset($_GET['status']) && $_GET['status'] != '') {
                    $status = $conn->real_escape_string($_GET['status']);
                    $sql .= " AND status = '$status'";
                }

                // Add completion status filter if set
                if (isset($_GET['completion_status']) && $_GET['completion_status'] != '') {
                    $completion_status = $conn->real_escape_string($_GET['completion_status']);
                    $sql .= " AND completion_status = '$completion_status'";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['raised_by']}</td>
                                <td>{$row['raise_time']}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['assign_time']}</td>
                                <td>{$row['assigned_to']}</td>
                                <td>{$row['completion_status']}</td>
                                <td>{$row['labels']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No records found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>