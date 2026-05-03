<?php
include "../backend/db.php";
include "../includes/navbar.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../index.html");
    exit();
}

$projects = mysqli_query($conn, "SELECT * FROM projects ORDER BY project_name");
$members  = mysqli_query($conn, "SELECT * FROM users WHERE role='Member' ORDER BY name");

$tasks = mysqli_query($conn, "
    SELECT tasks.*, 
           projects.project_name,
           users.name AS member_name
    FROM tasks
    JOIN projects ON tasks.project_id = projects.id
    JOIN users ON tasks.assigned_to = users.id
    ORDER BY tasks.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
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

        /* === Form Grid === */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
            gap: 16px;
            align-items: end;
            width: 100%;
        }

        .form-grid .span-2 { grid-column: span 2; }
        .form-grid .span-3 { grid-column: span 3; }

        @media (max-width: 1024px) {
            .form-grid { grid-template-columns: 1fr 1fr 1fr; }
        }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .span-2,
            .form-grid .span-3 { grid-column: span 1; }
        }

        /* === Field === */
        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* === Inputs === */
        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: #0f172a;
            font-size: 0.875rem;
            font-family: inherit;
            outline: none;
            resize: vertical;
            appearance: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        form input::placeholder,
        form textarea::placeholder { color: #94a3b8; }

        form select option { background: #fff; color: #0f172a; }

        form input:focus,
        form select:focus,
        form textarea:focus {
            border-color: #7c3aed;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
        }

        /* === Button === */
        .btn {
            width: 100%;
            padding: 11px 24px;
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(124,58,237,0.25);
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn:hover { opacity: 0.88; transform: translateY(-1px); }

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
            font-size: 0.865rem;
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
    </style>
</head>
<body>

<div class="content">

    <div class="page-title">
        <h1>Task Management</h1>
        <p>Assign and track tasks across your team</p>
    </div>

    <!-- Assign Task -->
    <div class="section-card">
        <h2>Assign Task</h2>
        <form action="../backend/create-task.php" method="POST">
            <div class="form-grid">

                <div class="field">
                    <label>Project</label>
                    <select name="project_id" required>
                        <option value="">Select Project</option>
                        <?php while($p = mysqli_fetch_assoc($projects)): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['project_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="field">
                    <label>Assign To</label>
                    <select name="assigned_to" required>
                        <option value="">Select Member</option>
                        <?php while($m = mysqli_fetch_assoc($members)): ?>
                            <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="field span-2">
                    <label>Task Title</label>
                    <input type="text" name="title" placeholder="Enter task title" required>
                </div>

                <div class="field">
                    <label>Due Date</label>
                    <input type="date" name="due_date" required>
                </div>

                <div class="field">
                    <label>&nbsp;</label>
                    <button class="btn" type="submit">Assign Task</button>
                </div>

                <div class="field span-3">
                    <label>Description</label>
                    <textarea name="description" placeholder="Enter task description" rows="2"></textarea>
                </div>

            </div>
        </form>
    </div>

    <!-- Task List -->
    <div class="section-card">
        <h2>Task List</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project</th>
                        <th>Assigned To</th>
                        <th>Task Title</th>
                        <th>Status</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($tasks)): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['member_name']); ?></td>
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
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
