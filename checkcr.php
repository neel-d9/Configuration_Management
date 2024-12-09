<?php
session_start();
include('connect.php');

$roleMapping = [
    "config_manager" => "Configuration Manager",
    "developer" => "Developer",
    "customer_support" => "Customer Support",
  ];
  
$userRole = isset($_SESSION['role']) && isset($roleMapping[$_SESSION['role']]) 
      ? $roleMapping[$_SESSION['role']] 
      : "Unknown Role"; 
include('navbar.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'developer') {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];

$query = "SELECT id, title, description, cr_type, cr_priority, completion_status 
          FROM crs 
          WHERE assigned_to = ? AND completion_status = 'pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Assigned CRs</title>
    <link rel="stylesheet" href="style2.css?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Your Assigned Change Requests</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['cr_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['cr_priority']); ?></td>
                            <td>
                                <form method="POST" action="mark_completed.php">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit">Mark as Completed</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No CRs assigned to you.</p>
        <?php endif; ?>

        <?php $stmt->close(); $conn->close(); ?>
    </div>
</body>
</html>
