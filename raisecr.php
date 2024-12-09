<?php

include 'connect.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['raiseCR'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $type = $_POST['type'];
    $raised_by = $_SESSION['username'];
    $sql = "INSERT INTO crs (title, description, raised_by, cr_type) VALUES ('$title', '$desc', '$raised_by', '$type')";
    $conn->query($sql);
    header("Location: raisecr.php");
    exit;
}

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
    <title>Raise Change Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
    <style>
        mark {
            background-color: #0047b3;

            color: white;
        }
    </style>
    <link rel="stylesheet" href="style2.css?v=1.0">

</head>

<body>
    <div class="container" id="raiseCR">
        <h1 class="form-title">Raise CR</h1>
        <form method="post" action=""> 
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="title" name="title" id="title" placeholder="Title" required>

            </div>
            <br>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="description" name="description" id="description" placeholder="Description" required>

            </div>
            <br>
            <select name="type" id="type" required>
                <option value="" disabled selected>Change Request Type</option>
                <option value="bug" <?php if (isset($_POST['type']) && $_POST['type'] == 'bug') echo 'selected'; ?>>Bug</option>
                <option value="feature" <?php if (isset($_POST['type']) && $_POST['type'] == 'feature') echo 'selected'; ?>>Feature</option>
                <option value="improvement" <?php if (isset($_POST['type']) && $_POST['type'] == 'improvement') echo 'selected'; ?>>Improvement</option>
            </select>
            <br>
            <br>
            <input type="submit" class="btn" value="Raise CR" name="raiseCR">
        </form>


    </div>
    <script src="script.js"></script>
</body>

</html>