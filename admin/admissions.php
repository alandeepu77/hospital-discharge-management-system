
<?php
session_start();
require_once "../config/database.php";

// Allow only logged-in admins
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$message = "";
$error = "";

// Add a new admission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id = $_POST["patient_id"] ?? "";
    $admission_date = $_POST["admission_date"] ?? "";
    $department = trim($_POST["department"] ?? "");

    if ($patient_id === "" || $admission_date === "" || $department === "") {
        $error = "Please fill in all required fields.";
    } else {
        try {
            // Check whether the selected patient exists
            $check = $conn->prepare(
                "SELECT patient_id FROM patients WHERE patient_id = ?"
            );
            $check->execute([$patient_id]);

            if (!$check->fetch()) {
                $error = "Please select a valid patient.";
            } else {
                $stmt = $conn->prepare(
                    "INSERT INTO admissions
                    (patient_id, admission_date, department, admission_status)
                    VALUES (?, ?, ?, 'Admitted')"
                );

                $stmt->execute([
                    $patient_id,
                    $admission_date,
                    $department
                ]);

                $message = "Admission registered successfully!";
            }
        } catch (PDOException $e) {
            $error = "Unable to register admission. Please try again.";
        }
    }
}

// Get patients for the dropdown
$patients = $conn->query(
    "SELECT patient_id, patient_name FROM patients ORDER BY patient_name"
)->fetchAll(PDO::FETCH_ASSOC);

// Get all admissions
$admissions = $conn->query(
    "SELECT a.admission_id, a.admission_date, a.department,
            a.admission_status, p.patient_name
     FROM admissions a
     INNER JOIN patients p ON a.patient_id = p.patient_id
     ORDER BY a.admission_id DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Management</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f7f8;
            margin: 0;
            padding: 25px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            color: #087f8c;
        }

        .card {
            background: white;
            padding: 22px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background: #087f8c;
            color: white;
            padding: 11px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 18px;
            cursor: pointer;
        }

        button:hover {
            background: #06636d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #087f8c;
            color: white;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .back {
            display: inline-block;
            margin-bottom: 18px;
            color: #087f8c;
            text-decoration: none;
        }
    </style>
</head>

<body>
<div class="container">

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

    <h1>Admission Management</h1>

    <div class="card">
        <h2>Register New Admission</h2>

        <?php if ($message): ?>
            <p class="success">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <label>Select Patient</label>
            <select name="patient_id" required>
                <option value="">-- Select Patient --</option>

                <?php foreach ($patients as $patient): ?>
                    <option value="<?= $patient['patient_id'] ?>">
                        <?= htmlspecialchars($patient['patient_name']) ?>
                        (ID: <?= $patient['patient_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Admission Date</label>
            <input type="date" name="admission_date" required>

            <label>Department</label>
            <input type="text" name="department"
                   placeholder="Enter department" required>

            <button type="submit">Register Admission</button>
        </form>
    </div>

    <div class="card">
        <h2>Admission Records</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Admission Date</th>
                <th>Department</th>
                <th>Status</th>
            </tr>

            <?php if (count($admissions) > 0): ?>
                <?php foreach ($admissions as $admission): ?>
                    <tr>
                        <td><?= $admission['admission_id'] ?></td>
                        <td><?= htmlspecialchars($admission['patient_name']) ?></td>
                        <td><?= htmlspecialchars($admission['admission_date']) ?></td>
                        <td><?= htmlspecialchars($admission['department']) ?></td>
                        <td><?= htmlspecialchars($admission['admission_status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No admission records found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</div>
</body>
</html>
