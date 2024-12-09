<?php
include('connect.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'developer') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $cr_id = intval($_POST['id']);
    $username = $_SESSION['username'];

    $query = "UPDATE crs 
              SET completion_status = 'completed' 
              WHERE id = ? AND assigned_to = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $cr_id, $username);

    if ($stmt->execute()) {
        echo "CR marked as completed.";
    } else {
        echo "Failed to mark the CR as completed.";
    }

    $stmt->close();
    $conn->close();
    header("Location: checkcr.php");
    exit;
}
?>
