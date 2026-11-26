
<?php
session_start();
require_once "../config/database.php";

// Check doctor login
if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "doctor"
) {
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION["user_id"];

// Count admitted patients
$stmt = $conn->query(
    "SELECT COUNT(*) FROM admissions
     WHERE admission_status = 'Admitted'"
);
$admitted_count = $stmt->fetchColumn();

// Count doctor's discharge requests
$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM discharge_requests
     WHERE doctor_id = ?"
);
$stmt->execute([$doctor_id]);
$request_count = $stmt->fetchColumn();

// Get admitted patients
$stmt = $conn->query(
    "SELECT a.admission_id, p.patient_name, p.age,
            p.gender, a.admission_date, a.department,
            a.admission_status
     FROM admissions a
     JOIN patients p ON a.patient_id = p.patient_id
     ORDER BY a.admission_id DESC"
);
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f1f6f9;
        }

        header {
            background: #087f8c;
            color: white;
            padding: 20px;
        }

        .container {
            padding: 25px;
        }

        .cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            flex: 1;
            min-width: 180px;
            box-shadow: 0 2px 8px #ddd;
        }

        .card h2 {
            color: #087f8c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #087f8c;
            color: white;
        }

        h3 {
            margin-top: 30px;
        }

        a {
            color: white;
            text-decoration: none;
        }

        @media (max-width: 700px) {
            .container {
                padding: 12px;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>Doctor Dashboard</h2>
    <p>Welcome, <?= htmlspecialchars($_SESSION["full_name"]) ?></p>
    <a href="../login.php">Logout</a>
</header>

<div class="container">

    <div class="cards">

        <div class="card">
            <h3>Admitted Patients</h3>
            <h2><?= $admitted_count ?></h2>
        </div>

        <div class="card">
            <h3>My Discharge Requests</h3>
            <h2><?= $request_count ?></h2>
        </div>

    </div>

    <h3>Patient Admission Records</h3>

    <table>
        <tr>
            <th>Admission ID</th>
            <th>Patient Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Admission Date</th>
            <th>Department</th>
            <th>Status</th>
        </tr>

        <?php foreach ($patients as $patient): ?>
        <tr>
            <td><?= htmlspecialchars($patient["admission_id"]) ?></td>
            <td><?= htmlspecialchars($patient["patient_name"]) ?></td>
            <td><?= htmlspecialchars($patient["age"] ?? "") ?></td>
            <td><?= htmlspecialchars($patient["gender"] ?? "") ?></td>
            <td><?= htmlspecialchars($patient["admission_date"]) ?></td>
            <td><?= htmlspecialchars($patient["department"] ?? "") ?></td>
            <td><?= htmlspecialchars($patient["admission_status"]) ?></td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
