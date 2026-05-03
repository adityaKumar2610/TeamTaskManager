<?php
include "../backend/db.php";
include "../includes/navbar.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Member') {
    header("Location: ../index.html");
    exit();
}

$id = $_GET['id'];

$task = mysqli_query($conn, "
    SELECT * FROM tasks
    WHERE id='$id' AND assigned_to='".$_SESSION['user_id']."'
");

$data = mysqli_fetch_assoc($task);

if (!$data) {
    die("Task not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task</title>
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
            display: flex;
            justify-content: center;
        }

        /* === Card === */
        .section-card {
            background: #fff;
            border-radius: 16px;
            padding: 32px 36px;
            width: 100%;
            max-width: 520px;
            height: fit-content;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e8edf5;
        }

        /* === Task Info === */
        .task-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .task-desc {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.6;
        }

        /* === Field === */
        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .field label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* === Select === */
        form select {
            width: 100%;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: #0f172a;
            font-size: 0.875rem;
            font-family: inherit;
            outline: none;
            appearance: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        form select option { background: #fff; color: #0f172a; }

        form select:focus {
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
            margin-top: 4px;
            box-shadow: 0 4px 14px rgba(124,58,237,0.25);
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn:hover { opacity: 0.88; transform: translateY(-1px); }

        /* === Back Link === */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: #7c3aed; }
    </style>
</head>
<body>

<div class="content">
    <div>
        <a class="back-link" href="my-tasks.php">← Back to My Tasks</a>

        <div class="section-card">
            <div class="task-title"><?php echo htmlspecialchars($data['title']); ?></div>
            <div class="task-desc"><?php echo htmlspecialchars($data['description']); ?></div>

            <form action="../backend/update-task.php" method="POST">
                <input type="hidden" name="task_id" value="<?php echo $data['id']; ?>">

                <div class="field">
                    <label>Update Status</label>
                    <select name="status" required>
                        <option value="Pending"     <?php echo $data['status']=='Pending'     ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $data['status']=='In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed"   <?php echo $data['status']=='Completed'   ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>

                <button class="btn" type="submit">Update Status</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
