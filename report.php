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

            <label for="cr_type">Type:</label>
            <select name="cr_type" id="cr_type">
                <option value="">All</option>
                <option value="bug" <?php if (isset($_GET['cr_type']) && $_GET['cr_type'] == 'bug') echo 'selected'; ?>>Bug</option>
                <option value="feature" <?php if (isset($_GET['cr_type']) && $_GET['cr_type'] == 'feature') echo 'selected'; ?>>Feature</option>
                <option value="improvement" <?php if (isset($_GET['cr_type']) && $_GET['cr_type'] == 'improvement') echo 'selected'; ?>>Improvement</option>
            </select>

            <label for="cr_priority">Priority:</label>
            <select name="cr_priority" id="cr_priority">
                <option value="">All</option>
                <option value="low" <?php if (isset($_GET['cr_priority']) && $_GET['cr_priority'] == 'low') echo 'selected'; ?>>Low</option>
                <option value="medium" <?php if (isset($_GET['cr_priority']) && $_GET['cr_priority'] == 'medium') echo 'selected'; ?>>Medium</option>
                <option value="high" <?php if (isset($_GET['cr_priority']) && $_GET['cr_priority'] == 'high') echo 'selected'; ?>>High</option>
            </select>
            <br>
            <br>
            <label for="title_search">Search by Title:</label>
            <input type="text" name="title_search" id="title_search" value="<?php if (isset($_GET['title_search'])) echo htmlspecialchars(trim($_GET['title_search'])); ?>" placeholder="Search by Title">
            <br>
            <br>
            <label for="description_search">Search by Description:</label>
            <input type="text" name="description_search" id="description_search" value="<?php if (isset($_GET['description_search'])) echo htmlspecialchars(trim($_GET['description_search'])); ?>" placeholder="Search by Description">

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
                    <th>Type</th>
                    <th>Priority</th>
                </tr>
            </thead>
            <tbody>
                <?php
                               // Build the SQL query
                               $sql = "SELECT * FROM crs WHERE 1=1"; // Assuming 'cr_table' is your table name

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
               
                               // Add cr_type filter if set
                               if (isset($_GET['cr_type']) && $_GET['cr_type'] != '') {
                                   $cr_type = $conn->real_escape_string($_GET['cr_type']);
                                   $sql .= " AND cr_type = '$cr_type'";
                               }
               
                               // Add cr_priority filter if set
                               if (isset($_GET['cr_priority']) && $_GET['cr_priority'] != '') {
                                   $cr_priority = $conn->real_escape_string($_GET['cr_priority']);
                                   $sql .= " AND cr_priority = '$cr_priority'";
                               }
               
                               // Add title search filter if set
                               if (isset($_GET['title_search']) && $_GET['title_search'] != '') {
                                   $title_search = trim($_GET['title_search']);
                                   $title_search = preg_replace('/[.,\'"“”]/', '', $title_search); // Remove delimiters
                                   $title_search = preg_replace('/\s+/', ' ', $title_search); // Remove extra spaces
                                   $title_search = $conn->real_escape_string($title_search);
                                   $sql .= " AND LOWER(title) LIKE LOWER('%$title_search%')";
                               }
               
                               // Add description search filter if set
                               if (isset($_GET['description_search']) && $_GET['description_search'] != '') {
                                   $description_search = trim($_GET['description_search']);
                                   $description_search = preg_replace('/[.,\'"“”]/', '', $description_search); // Remove delimiters
                                   $description_search = preg_replace('/\s+/', ' ', $description_search); // Remove extra spaces
                                   $description_search = $conn->real_escape_string($description_search);
                                   $sql .= " AND LOWER(description) LIKE LOWER('%$description_search%')";
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
                                               <td>{$row['cr_type']}</td>
                                               <td>{$row['cr_priority']}</td>
                                             </tr>";
                                   }
                               } else {
                                   echo "<tr><td colspan='11'>No records found</td></tr>";
                               }
                               $conn->close();
                               ?>
                           </tbody>
                       </table>
                   </div>
               </body>
               </html>