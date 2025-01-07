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

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'config_manager') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crId = $_POST['id'];
    if($_POST['action'] === 'update') {
        $devteam = $_POST['team'];
        $cr_priority = $_POST['cr_priority'];
        $stmt = $conn->prepare("UPDATE crs SET status = 'assigned', assigned_to = ?, cr_priority = ?, assign_time = NOW() WHERE id = ?");
        $stmt->bind_param('ssi', $devteam, $cr_priority, $crId);
        $stmt->execute();
        $stmt->close();
    }
    elseif ($_POST['action'] === 'reject') {
        $stmt = $conn->prepare("DELETE FROM crs WHERE id = ?");
        $stmt->bind_param('i', $crId);
        $stmt->execute();
        $stmt->close();
    }
}

$result = $conn->query("SELECT id, title, description, assigned_to, cr_priority FROM crs WHERE status='unassigned'");
$crs = $result->fetch_all(MYSQLI_ASSOC);
$result->close();

$devqueries = $conn->query("SELECT username FROM users WHERE role = 'developer'");
while ($row = $devqueries->fetch_assoc()) {
    $devteams[] = $row['username'];
}
$devqueries->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Change Request</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
    <h1 class="form-title">Assign Change Requests</h1>
        <?php if (count($crs) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Assigned Team</th>
                    <th>Priority</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crs as $cr): ?>
                    <tr>
                        <form method="POST">
                            <td><?= htmlspecialchars($cr['id']) ?></td>
                            <td><?= htmlspecialchars($cr['title']) ?></td>
                            <td><?= htmlspecialchars($cr['description']) ?></td>
                            <td>
                                <select name="team" required>
                                    <option value="">Select Team</option>
                                    <?php foreach ($devteams as $team): ?>
                                        <option value="<?= htmlspecialchars($team) ?>" <?= $cr['assigned_to'] === $team ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($team) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="cr_priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="high" <?= $cr['cr_priority'] === 'high' ? 'selected' : '' ?>>High</option>
                                    <option value="medium" <?= $cr['cr_priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option value="low" <?= $cr['cr_priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?= htmlspecialchars($cr['id']) ?>">
                                <button type="submit" class="table-btn" name="action" value="update">Update</button>
                                <button type="submit" class="table-btn" name="action" value="reject" formnovalidate>Reject</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No unassigned CRs.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
