<?php
include "../backend/db.php";
include "../includes/navbar.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../index.html");
    exit();
}

$report = mysqli_query($conn, "
    SELECT
        users.name,
        COUNT(tasks.id) AS total_tasks,
        SUM(CASE WHEN tasks.status='Completed'   THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN tasks.status='Pending'     THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN tasks.status='In Progress' THEN 1 ELSE 0 END) AS progress
    FROM users
    LEFT JOIN tasks ON users.id = tasks.assigned_to
    WHERE users.role='Member'
    GROUP BY users.id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
        .page-title { margin-bottom: 24px; }

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

        /* === Table === */
        .table-wrap { overflow-x: auto; width: 100%; }

        table { width: 100%; border-collapse: collapse; }

        thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 13px 16px;
            text-align: left;
            white-space: nowrap;
            border-bottom: 1px solid #e8edf5;
        }

        tbody td {
            padding: 14px 16px;
            font-size: 0.875rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f8fafc; }

        /* === Stat Badges === */
        .stat {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .stat-total    { background: #ede9fe; color: #6d28d9; }
        .stat-done     { background: #dcfce7; color: #15803d; }
        .stat-pending  { background: #fef9c3; color: #a16207; }
        .stat-progress { background: #dbeafe; color: #1d4ed8; }
    </style>
</head>
<body>

<div class="content">

    <div class="page-title">
        <h1>Member Task Report</h1>
        <p>Overview of task progress for each team member</p>
    </div>

    <div class="section-card">
        <h2>Task Summary</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Total Tasks</th>
                        <th>Completed</th>
                        <th>Pending</th>
                        <th>In Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($report)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><span class="stat stat-total"><?php echo $row['total_tasks']; ?></span></td>
                        <td><span class="stat stat-done"><?php echo $row['completed']; ?></span></td>
                        <td><span class="stat stat-pending"><?php echo $row['pending']; ?></span></td>
                        <td><span class="stat stat-progress"><?php echo $row['progress']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
