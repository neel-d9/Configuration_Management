<?php

$roleMapping = [
    "config_manager" => "Configuration Manager",
    "developer" => "Developer",
    "customer_support" => "Customer Support",
];

$userRole = isset($_SESSION['role']) && isset($roleMapping[$_SESSION['role']])
    ? $roleMapping[$_SESSION['role']]
    : "Unknown Role";
?>

<div class="navbar">
    <div class="logo">
        <a href="index.php">Alpine Technologies</a>
    </div>
    <div class="nav-buttons">
        <?php if ($userRole == "Configuration Manager"): ?>
            <a href="managecr.php">
                <button>Manage CR</button>
            </a>
        <?php endif; ?>
        <?php if ($userRole == "Developer"): ?>
            <a href="checkcr.php">
                <button>Check Assigned CR</button>
            </a>
        <?php endif; ?>
        <?php if ($userRole == "Developer" || $userRole == "Customer Support"): ?>
            <a href="raisecr.php">
                <button>Raise New CR</button>
            </a>
        <?php endif; ?>
        <?php if ($userRole == "Developer" || $userRole == "Customer Support" || $userRole == "Configuration Manager"): ?>
            <a href="report.php">
                <button>View CR Reports</button>
            </a>
        <?php endif; ?>

        <div class="divider"></div>

        <?php if (!isset($_SESSION['username'])): ?>
            <a href="index.php">
                <button>Login</button>
            </a>
        <?php else: ?>
            <div class="user-info">
                <span>Signed in as <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php">
                    <button class="logout-button">Logout</button>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
