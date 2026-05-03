<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
.navbar {
    position: fixed;       /* ← fixes it to the top */
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    background: rgba(15, 12, 41, 0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 14px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.4);
}

.logo {
    font-size: 1.2rem;
    font-weight: 700;
    background: linear-gradient(90deg, #a78bfa, #60a5fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.3px;
}

.nav-links {
    display: flex;
    gap: 8px;
    align-items: center;
}

.nav-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    padding: 8px 14px;
    border-radius: 9px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background 0.2s, color 0.2s;
}

.nav-links a:hover {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
}

.user-name {
    font-weight: 600;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.5);
    padding: 0 8px;
}

.logout-btn {
    background: linear-gradient(135deg, #dc2626, #f87171) !important;
    color: #fff !important;
    border-radius: 9px;
    padding: 8px 16px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    transition: opacity 0.2s, transform 0.15s !important;
}

.logout-btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    background: linear-gradient(135deg, #dc2626, #f87171) !important;
}
</style>

<div class="navbar">
    <div class="logo">Team Task Manager</div>

    <div class="nav-links">

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "Admin"): ?>
            <a href="/TeamTaskManager/admin/dashboard.php">Dashboard</a>
            <a href="/TeamTaskManager/admin/projects.php">Projects</a>
            <a href="/TeamTaskManager/admin/members.php">Members</a>
            <a href="/TeamTaskManager/admin/tasks.php">Tasks</a>
            <a href="/TeamTaskManager/admin/reports.php">Reports</a>
        <?php endif; ?>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "Member"): ?>
            <a href="/TeamTaskManager/member/dashboard.php">Dashboard</a>
            <a href="/TeamTaskManager/member/my-tasks.php">My Tasks</a>
        <?php endif; ?>

        <span class="user-name">
            <?php echo htmlspecialchars($_SESSION['name'] ?? 'Guest'); ?>
        </span>

        <a class="logout-btn" href="/TeamTaskManager/logout.php">Logout</a>
    </div>
</div>
