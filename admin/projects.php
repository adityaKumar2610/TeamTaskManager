<?php
include "../backend/db.php";
include "../includes/navbar.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../index.html");
    exit();
}

$projects = mysqli_query($conn, "
    SELECT projects.*, users.name AS creator
    FROM projects
    JOIN users ON projects.created_by = users.id
    ORDER BY projects.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
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
            margin-bottom: 24px;
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
            grid-template-columns: 1fr 2fr auto;
            gap: 14px;
            align-items: end;
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
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
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        form input::placeholder,
        form textarea::placeholder { color: #94a3b8; }

        form input:focus,
        form textarea:focus {
            border-color: #7c3aed;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
        }

        /* === Button === */
        .btn {
            padding: 10px 24px;
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(124,58,237,0.25);
            transition: opacity 0.2s, transform 0.15s;
            white-space: nowrap;
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
            font-size: 0.875rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f8fafc; }

        /* === Description clamp === */
        .desc-cell {
            max-width: 280px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #94a3b8;
            font-size: 0.82rem;
        }

        /* === Creator Badge === */
        .badge-creator {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            background: #ede9fe;
            color: #6d28d9;
            border: 1px solid #ddd6fe;
        }
    </style>
</head>
<body>

<div class="content">

    <div class="page-title">
        <h1>Projects</h1>
        <p>Create and manage your team projects</p>
    </div>

    <!-- Create Project -->
    <div class="section-card">
        <h2>Create Project</h2>
        <form action="../backend/create-project.php" method="POST">
            <div class="form-grid">
                <div class="field">
                    <label>Project Name</label>
                    <input type="text" name="project_name" placeholder="Enter project name" required>
                </div>
                <div class="field">
                    <label>Description</label>
                    <input type="text" name="description" placeholder="Enter project description">
                </div>
                <div class="field">
                    <label>&nbsp;</label>
                    <button class="btn" type="submit">Create Project</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Project List -->
    <div class="section-card">
        <h2>Project List</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($projects)): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                        <td class="desc-cell"><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><span class="badge-creator"><?php echo htmlspecialchars($row['creator']); ?></span></td>
                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
