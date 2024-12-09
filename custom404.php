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

include('navbar.php');
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
    <h1>Lost in the woods?</h1>
    <br>
    <p>Oops! The page you are looking for does not exist.</p>
    <br>
    <a class="c404btn" href="/Configuration_Management/homepage.php">Return to Home</a>
    <br>
    <a class="c404btn" href="javascript:history.back();">Return to Previous Page</a>
</body>
</html>
