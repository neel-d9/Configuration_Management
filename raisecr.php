<?php

include 'connect.php';

//if(!isset($_SESSION[]))

if (isset($_POST['raiseCR'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    /*
    $type = $_POST['cr_type'];
    */
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
    }
    $raised_by = $_SESSION['username'];
    $sql = "INSERT INTO crs (title, description, raised_by) VALUES ('$title', '$desc', '$raised_by')";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raise Change Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        mark {
            background-color: #0047b3;

            color: white;
        }
    </style>
    <link rel="stylesheet" href="style2.css">
</head>

<body>
    <div class="container" id="raiseCR">
        <h1 class="form-title">Raise CR</h1>
        <form method="post" action="raisecr.php"> <!-- raisecr php -->
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="title" name="title" id="title" placeholder="Title" required>

            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="description" name="description" id="description" placeholder="Description" required>

            </div>
            <div class="dropdown">
                <div class="dropdown-trigger">CR types</div>
                <div class="dropdown-content">
                    <a href="#" data-value="bug">Bug</a>
                    <a href="#" data-value="feature">Feature</a>
                    <a href="#" data-value="improvement">Improvement</a>
                </div>
            </div>
            <input type="hidden" name="cr_type" id="cr_type" value="">

            <input type="submit" class="btn" value="Raise CR" name="raiseCR">
        </form>


    </div>
    <script src="script.js"></script>
</body>

</html>