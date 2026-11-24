
<?php
session_start();

require_once "../config/database.php";

// Allow only logged-in admins
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Get dashboard counts
$totalPatients = $conn->query(
    "SELECT COUNT(*) FROM patients"
)->fetchColumn();

$totalAdmissions = $conn->query(
    "SELECT COUNT(*) FROM admissions"
)->fetchColumn();

$pendingRequests = $conn->query(
    "SELECT COUNT(*) FROM discharge_requests WHERE status = 'Pending'"
)->fetchColumn();

$completedRequests = $conn->query(
    "SELECT COUNT(*) FROM discharge_requests WHERE status = 'Completed'"
)->fetchColumn();

// Get department task progress
$departments = $conn->query(
    "SELECT department,
            COUNT(*) AS total,
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed
     FROM discharge_tasks
     GROUP BY department"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f6fa;
            color: #263746;
        }

        header {
            background: #087f8c;
            color: white;
            padding: 22px 7%;
        }

        header h2 {
            margin: 0;
        }

        .container {
            width: 86%;
            margin: 30px auto;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }

        .card h3 {
            margin-top: 0;
            color: #536675;
            font-size: 16px;
        }

        .number {
            font-size: 30px;
            font-weight: bold;
            color: #087f8c;
        }

        h2.section-title {
            margin-top: 38px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 18px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e9ed;
        }

        th {
            background: #087f8c;
            color: white;
        }

        .empty {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        @media (max-width: 600px) {
            .container {
                width: 92%;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 10px 7px;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>Hospital Discharge Management System</h2>
</header>

<div class="container">

    <div class="welcome">
        <h2>Welcome, <?= htmlspecialchars($_SESSION["full_name"]) ?>!</h2>
        <p>Admin Dashboard — Overview of hospital discharge activities.</p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Total Patients</h3>
            <div class="number"><?= $totalPatients ?></div>
        </div>

        <div class="card">
            <h3>Total Admissions</h3>
            <div class="number"><?= $totalAdmissions ?></div>
        </div>

        <div class="card">
            <h3>Pending Discharge Requests</h3>
            <div class="number"><?= $pendingRequests ?></div>
        </div>

        <div class="card">
            <h3>Completed Discharges</h3>
            <div class="number"><?= $completedRequests ?></div>
        </div>

    </div>

    <h2 class="section-title">Department Progress</h2>

    <?php if (count($departments) > 0): ?>

        <table>
            <tr>
                <th>Department</th>
                <th>Total Tasks</th>
                <th>Pending</th>
                <th>In Progress</th>
                <th>Completed</th>
            </tr>

            <?php foreach ($departments as $department): ?>
                <tr>
                    <td><?= htmlspecialchars($department["department"]) ?></td>
                    <td><?= $department["total"] ?></td>
                    <td><?= $department["pending"] ?></td>
                    <td><?= $department["in_progress"] ?></td>
                    <td><?= $department["completed"] ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php else: ?>

        <p class="empty">No department tasks have been created yet.</p>

    <?php endif; ?>

</div>

</body>
</html>
