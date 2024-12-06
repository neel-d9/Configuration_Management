<?php
session_start();
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
    <title>Page Not Found</title>
    <link rel="stylesheet" href="style2.css?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
</head>
<body>
<div class="navbar">
        <div class="logo">
            <a href="index.php">Alpine Technologies</a>
        </div>
        <div class="nav-buttons">
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

            <div class="divider"></div>
            
            <?php if (!isset($_SESSION['username'])): ?>
              <a href="index.php">
                <button>Login</button>
              </a>
            <?php else: ?>
              <div class="user-info">
                <span>Signed in as <?php echo $_SESSION['username'] ?></span>
                <a href="logout.php">
                    <button class="logout-button">Logout</button>
                </a>
              </div>
            <?php endif; ?>
        </div>
    </div>
    <h1>Lost in the woods?</h1>
    <br>
    <p>Oops! The page you are looking for does not exist.</p>
    <p><a href="/Configuration_Management/homepage.php">Return to Home</a></p>
</body>
</html>
