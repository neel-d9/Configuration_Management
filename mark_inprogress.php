<?php
include('connect.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'developer') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $cr_id = intval($_POST['id']);
    $new_status = $_POST['status'];
    $username = $_SESSION['username'];

    $query = "UPDATE crs 
              SET status = ? 
              WHERE id = ? AND assigned_to = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sis', $new_status, $cr_id, $username);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status updated to '$new_status'.";
    } else {
        $_SESSION['message'] = "Failed to update status.";
    }

    $stmt->close();
    $conn->close();
    header("Location: checkcr.php");
    exit;
}
?>
