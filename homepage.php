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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style2.css?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="logo">Alpine Technologies</div>
        <div class="nav-buttons">
            <button onclick="alert('Button 1 clicked')">1</button>
            <button onclick="alert('Button 2 clicked')">2</button>
            <a href="logout.php">
              <button>Logout</button>
            </a>
        </div>
    </div>
    <div class="card">
      <div class="card-border-top">
      </div>
      <div class="img">
      </div>
      <span><?php echo $_SESSION['username']; ?></span>
      <p class="job"><?php echo $userRole; ?></p>
      <?php if ($userRole=="Configuration Manager"): ?>
        <a href="managecr.php">
          <button>Manage CR</button>
        </a>
      <?php endif; ?>
      <?php if ($userRole=="Developer"): ?>
        <a href="checkcr.php">
          <button>Check assigned CR</button>
        </a>
      <?php endif; ?>
      <?php if ($userRole=="Developer" || $userRole=="Customer Support"): ?>
        <a href="raisecr.php">
          <button>Raise New CR</button>
        </a>
      <?php endif; ?>
    </div>
</body>
</html>