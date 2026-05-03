<?php
include "../backend/db.php";
include "../includes/navbar.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Member') {
    header("Location: ../index.html");
    exit();
}

$userId = $_SESSION['user_id'];

$tasks = mysqli_query($conn, "
    SELECT tasks.*, projects.project_name
    FROM tasks
    JOIN projects ON tasks.project_id = projects.id
    WHERE tasks.assigned_to='$userId'
    ORDER BY tasks.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
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

        /* === Badges === */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-pending   { background: #fef9c3; color: #a16207; }
        .badge-progress  { background: #dbeafe; color: #1d4ed8; }
        .badge-completed { background: #dcfce7; color: #15803d; }

        /* === Action Button === */
        .btn-update {
            display: inline-block;
            text-decoration: none;
            padding: 7px 16px;
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            color: #fff;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(124,58,237,0.2);
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn-update:hover {
            opacity: 0.88;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="content">

    <div class="page-title">
        <h1>My Tasks</h1>
        <p>Track and update the status of your assigned tasks</p>
    </div>

    <div class="section-card">
        <h2>Task List</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($tasks)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td>
                            <?php
                                $status = $row['status'];
                                $cls = match(strtolower($status)) {
                                    'completed'   => 'badge-completed',
                                    'in progress' => 'badge-progress',
                                    default       => 'badge-pending'
                                };
                            ?>
                            <span class="badge <?php echo $cls; ?>"><?php echo $status; ?></span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($row['due_date'])); ?></td>
                        <td>
                            <a class="btn-update" href="update-task.php?id=<?php echo $row['id']; ?>">
                                Update
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
