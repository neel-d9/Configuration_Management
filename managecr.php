<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
include("connect.php");

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
    <link rel="stylesheet" href="styles.css">    
</head>
<body>
    <div class="container">
    <h1>Assign Change Requests</h1>
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
                                <button type="submit" name="action" value="update">Update</button><br>
                                <button type="submit" name="action" value="reject" formnovalidate>Reject</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
