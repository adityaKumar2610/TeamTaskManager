<?php
include "../includes/navbar.php";
include "../backend/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../index.html");
    exit();
}

$totalUsers     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='Member'"));
$totalProjects  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM projects"));
$totalTasks     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks"));
$completedTasks = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks WHERE status='Completed'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            margin: 0;
            background: #f4f6fb;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            padding-top: 72px;
        }

        .content {
            width: 100%;
            min-height: calc(100vh - 72px);
            padding: 30px;
        }

        /* === Page Title === */
        .page-title {
            margin-bottom: 28px;
        }

        .page-title h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
        }

        .page-title p {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* === Stat Cards === */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        @media (max-width: 768px) { .cards { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .cards { grid-template-columns: 1fr; } }

        .card-box {
            background: #fff;
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e8edf5;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .card-box .icon {
            font-size: 1.8rem;
            margin-bottom: 12px;
        }

        .card-box h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-box p {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        /* === Section Card === */
        .section-card {
            width: 100%;
            background: #fff;
            border-radius: 16px;
            padding: 28px 32px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e8edf5;
        }

        .section-card h2 {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* === Quick Links === */
        .menu {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .menu a {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s, color 0.2s;
        }

        .menu a:hover {
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            border-color: transparent;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(124,58,237,0.25);
        }
    </style>
</head>
<body>

<div class="content">

    <div class="page-title">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h1>
        <p>Here's what's happening with your team today</p>
    </div>

    <!-- Stat Cards -->
    <div class="cards">
        <div class="card-box">
            <div class="icon">👥</div>
            <h2><?php echo $totalUsers; ?></h2>
            <p>Total Members</p>
        </div>
        <div class="card-box">
            <div class="icon">📁</div>
            <h2><?php echo $totalProjects; ?></h2>
            <p>Total Projects</p>
        </div>
        <div class="card-box">
            <div class="icon">✅</div>
            <h2><?php echo $totalTasks; ?></h2>
            <p>Total Tasks</p>
        </div>
        <div class="card-box">
            <div class="icon">🏆</div>
            <h2><?php echo $completedTasks; ?></h2>
            <p>Completed Tasks</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="section-card">
        <h2>Quick Links</h2>
        <div class="menu">
            <a href="projects.php">📁 Projects</a>
            <a href="members.php">👥 Members</a>
            <a href="tasks.php">✅ Tasks</a>
            <a href="reports.php">📊 Reports</a>
        </div>
    </div>

</div>

</body>
</html>
