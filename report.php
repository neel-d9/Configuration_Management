<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
include("connect.php");

$roleMapping = [
    "config_manager" => "Configuration Manager",
    "developer" => "Developer",
    "customer_support" => "Customer Support",
];

$userRole = isset($_SESSION['role']) && isset($roleMapping[$_SESSION['role']]) 
      ? $roleMapping[$_SESSION['role']] 
      : "Unknown Role"; 

include("navbar.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CR Management Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="form-title">CR Management Dashboard</h1>

       
        <form method="GET" action="">
            <div class="form-container">
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="">All</option>
                        <option value="unassigned" <?php if (isset($_GET['status']) && $_GET['status'] == 'unassigned') echo 'selected'; ?>>Unassigned</option>
                        <option value="assigned" <?php if (isset($_GET['status']) && $_GET['status'] == 'assigned') echo 'selected'; ?>>Assigned</option>
                        <option value="in_progress" <?php if (isset($_GET['status']) && $_GET['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                        <option value="completed" <?php if (isset($_GET['status']) && $_GET['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="completion_status">Completion Status:</label>
                    <select name="completion_status" id="completion_status">
                        <option value="">All</option>
                        <option value="pending" <?php if (isset($_GET['completion_status']) && $_GET['completion_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                        <option value="completed" <?php if (isset($_GET['completion_status']) && $_GET['completion_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cr_type">Type:</label>
                    <select name="cr_type" id="cr_type">
                        <option value="">All</option>
                        <option value="bug" <?php if (isset($_GET['cr_type']) && $_GET['cr_type'] == 'bug') echo 'selected'; ?>>Bug</option>
                        <option value="feature" <?php if (isset($_GET['cr_type']) && $_GET['cr_type'] == 'feature') echo 'selected'; ?>>Feature</option>
                        <option value="improvement" <?php if (isset($_GET['cr_type']) && $_GET['cr_type'] == 'improvement') echo 'selected'; ?>>Improvement</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cr_priority">Priority:</label>
                    <select name="cr_priority" id="cr_priority">
                        <option value="">All</option>
                        <option value="low" <?php if (isset($_GET['cr_priority']) && $_GET['cr_priority'] == 'low') echo 'selected'; ?>>Low</option>
                        <option value="medium" <?php if (isset($_GET['cr_priority']) && $_GET['cr_priority'] == 'medium') echo 'selected'; ?>>Medium</option>
                        <option value="high" <?php if (isset($_GET['cr_priority']) && $_GET['cr_priority'] == 'high') echo 'selected'; ?>>High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="from_date">Start Date:</label>
                    <input type="date" name="from_date" id="from_date" value="<?php if (isset($_GET['from_date'])) echo htmlspecialchars(trim($_GET['from_date'])); ?>">
                </div>

                <div class="form-group">
                    <label for="to_date">End Date:</label>
                    <input type="date" name="to_date" id="to_date" value="<?php if (isset($_GET['to_date'])) echo htmlspecialchars(trim($_GET['to_date'])); ?>">
                </div>

                <div class="form-group">
                    <label for="title_search">Search by Title:</label>
                    <input type="text" name="title_search" id="title_search" value="<?php if (isset($_GET['title_search'])) echo htmlspecialchars(trim($_GET['title_search'])); ?>" placeholder="Search by Title">
                </div>

                <div class="form-group">
                    <label for="description_search">Search by Description:</label>
                    <input type="text" name="description_search" id="description_search" value="<?php if (isset($_GET['description_search'])) echo htmlspecialchars(trim($_GET['description_search'])); ?>" placeholder="Search by Description">
                </div>

                <div class="submit-group">
                    <button type="submit" class="report-btn">Filter</button>
                </div>
            </div>
        </form>

        <?php
        
        $error_message = "";

        
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
           
            if (isset($_GET['from_date']) && isset($_GET['to_date']) && $_GET['from_date'] != '' && $_GET['to_date'] != '') {
                $from_date = $_GET['from_date'];
                $to_date = $_GET['to_date'];

                
                $fromDateTime = new DateTime($from_date);
                $toDateTime = new DateTime($to_date);

                
                if ($toDateTime <= $fromDateTime) {
                    $error_message = "The 'End' date must be after the 'Start' date.";
                }
            }
        }
        ?>

        <?php
        
        if (!empty($error_message)) {
            echo "<div style='color: red;'>$error_message</div>";
        }
        ?>

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
                
                $sql = "SELECT * FROM crs WHERE 1=1";

                
                if (isset($_GET['status']) && $_GET['status'] != '') {
                    $status = $conn->real_escape_string($_GET['status']);
                    $sql .= " AND status = '$status'";
                }

               
                if (isset($_GET['completion_status']) && $_GET['completion_status'] != '') {
                    $completion_status = $conn->real_escape_string($_GET['completion_status']);
                    $sql .= " AND completion_status = '$completion_status'";
                }

                
                if (isset($_GET['cr_type']) && $_GET['cr_type'] != '') {
                    $cr_type = $conn->real_escape_string($_GET['cr_type']);
                    $sql .= " AND cr_type = '$cr_type'";
                }

                
                if (isset($_GET['cr_priority']) && $_GET['cr_priority'] != '') {
                    $cr_priority = $conn->real_escape_string($_GET['cr_priority']);
                    $sql .= " AND cr_priority = '$cr_priority'";
                }

                
                if (isset($_GET['from_date']) && $_GET['from_date'] != '' && empty($error_message)) {
                    $from_date = $conn->real_escape_string($_GET['from_date']);
                    $sql .= " AND raise_time >= '$from_date'";
                }

               
                if (isset($_GET['to_date']) && $_GET['to_date'] != '' && empty($error_message)) {
                    $to_date = $conn->real_escape_string($_GET['to_date']);
                    $sql .= " AND raise_time <= '$to_date'";
                }

                
                if (isset($_GET['title_search']) && $_GET['title_search'] != '') {
                    $title_search = trim($_GET['title_search']);
                    $title_search = preg_replace('/[.,\'"“”]/', '', $title_search); 
                    $title_search = preg_replace('/\s+/', ' ', $title_search); 
                    $title_search = $conn->real_escape_string($title_search);
                    $sql .= " AND LOWER(title) LIKE LOWER('%$title_search%')";
                }
                
                
                if (isset($_GET['description_search']) && $_GET['description_search'] != '') {
                    $description_search = trim($_GET['description_search']);
                    $description_search = preg_replace('/[.,\'"“”]/', '', $description_search); 
                    $description_search = preg_replace('/\s+/', ' ', $description_search);
                    $description_search = $conn->real_escape_string($description_search);
                    $sql .= " AND LOWER(description) LIKE LOWER('%$description_search%')";
                }

                
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    
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