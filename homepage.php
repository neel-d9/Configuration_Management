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

include('navbar.php');

$username = $_SESSION['username'];
$profilePicPath = "images/$username.jpg";
$defaultPicPath = "images/default.jpg";

$totalCRs = $completedCRs = $inProgressCRs = $untouchedCRs = $unassignedCRs = $assignedCRs = 0;

if ($userRole === "Customer Support") {
    $query = "SELECT 
                 COUNT(*) AS total, 
                 SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
                 SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) AS in_progress
              FROM crs 
              WHERE raised_by = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($totalCRs, $completedCRs, $inProgressCRs);
    $stmt->fetch();
    $stmt->close();
} elseif ($userRole === "Developer") {
    $query = "SELECT 
                 COUNT(*) AS total, 
                 SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
                 SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) AS in_progress,
                 SUM(CASE WHEN status = 'assigned' THEN 1 ELSE 0 END) AS assigned
              FROM crs 
              WHERE assigned_to = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($totalCRs, $completedCRs, $inProgressCRs, $untouchedCRs);
    $stmt->fetch();
    $stmt->close();
} elseif ($userRole === "Configuration Manager") {
    $query = "SELECT 
                 SUM(CASE WHEN status = 'unassigned' THEN 1 ELSE 0 END) AS unassigned,
                 SUM(CASE WHEN status IN ('assigned', 'in_progress') THEN 1 ELSE 0 END) AS assigned,
                 SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed
              FROM crs";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($unassignedCRs, $assignedCRs, $completedCRs);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
</head>
<body>
    <div class="big-card">
        <div class="card-header">
            <img src="<?php echo file_exists($profilePicPath) ? $profilePicPath : $defaultPicPath; ?>" 
                 alt="Profile Picture" 
                 class="profile-pic">
            <div class="user-info">
                <h1><?php echo htmlspecialchars($username); ?></h1>
                <p><?php echo htmlspecialchars($userRole); ?></p>
            </div>
        </div>
        <div class="card-body">
            <?php if ($userRole === "Customer Support"): ?>
                <p>Total CRs raised by you: <?php echo $totalCRs; ?></p>
                <p>Completed CRs raised by you: <?php echo $completedCRs; ?></p>
                <p>CRs in progress raised by you: <?php echo $inProgressCRs; ?></p>
            <?php elseif ($userRole === "Developer"): ?>
                <p>Total CRs assigned to you: <?php echo $totalCRs; ?></p>
                <p>CRs completed by you: <?php echo $completedCRs; ?></p>
                <p>Your CRs in progress: <?php echo $inProgressCRs; ?></p>
                <p>Your untouched CRs: <?php echo $untouchedCRs; ?></p>
            <?php elseif ($userRole === "Configuration Manager"): ?>
                <p>Total Unassigned CRs: <?php echo $unassignedCRs; ?></p>
                <p>Total Assigned CRs: <?php echo $assignedCRs; ?></p>
                <p>Total Completed CRs: <?php echo $completedCRs; ?></p>
            <?php else: ?>
                <p>Role-specific information is not available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
