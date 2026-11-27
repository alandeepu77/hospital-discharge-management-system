
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

$message = "";

// Handle discharge request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $admission_id = $_POST["admission_id"] ?? "";
    $reason = trim($_POST["reason"] ?? "");
    $doctor_id = $_SESSION["user_id"];

    if ($admission_id === "" || $reason === "") {

        $message = "Please select a patient and enter a reason.";

    } else {

        // Check whether the admission exists and is admitted
        $check = $conn->prepare(
            "SELECT admission_id
             FROM admissions
             WHERE admission_id = ?
             AND admission_status = 'Admitted'"
        );

        $check->execute([$admission_id]);

        if ($check->fetch()) {

            // Check for an existing pending request
            $existing = $conn->prepare(
                "SELECT request_id
                 FROM discharge_requests
                 WHERE admission_id = ?
                 AND status = 'Pending'"
            );

            $existing->execute([$admission_id]);

            if ($existing->fetch()) {

                $message = "A pending discharge request already exists for this admission.";

            } else {

                // Insert discharge request
                $stmt = $conn->prepare(
                    "INSERT INTO discharge_requests
                     (admission_id, doctor_id, reason)
                     VALUES (?, ?, ?)"
                );

                $stmt->execute([
                    $admission_id,
                    $doctor_id,
                    $reason
                ]);

                // Update admission status
                $update = $conn->prepare(
                    "UPDATE admissions
                     SET admission_status = 'Discharge Requested'
                     WHERE admission_id = ?"
                );

                $update->execute([$admission_id]);

                $message = "Discharge request submitted successfully!";
            }

        } else {

            $message = "This patient is not currently admitted.";

        }
    }
}

// Get admitted patients
$stmt = $conn->query(
    "SELECT a.admission_id, p.patient_name,
            a.admission_date, a.department
     FROM admissions a
     JOIN patients p ON a.patient_id = p.patient_id
     WHERE a.admission_status = 'Admitted'
     ORDER BY a.admission_id DESC"
);

$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Discharge</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f6f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: #087f8c;
            color: white;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px #ddd;
        }

        h2 {
            color: #087f8c;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #087f8c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #05636d;
        }

        .message {
            padding: 12px;
            background: #e0f2f1;
            color: #00695c;
            border-radius: 6px;
        }

        a {
            color: #087f8c;
            text-decoration: none;
        }
    </style>
</head>

<body>

<header>
    <h2>Doctor Dashboard - Discharge Request</h2>
</header>

<div class="container">

    <h2>Request Patient Discharge</h2>

    <?php if ($message !== ""): ?>
        <p class="message">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Select Patient:</label>

        <select name="admission_id" required>
            <option value="">-- Select Patient --</option>

            <?php foreach ($patients as $patient): ?>
                <option value="<?= $patient["admission_id"] ?>">
                    <?= htmlspecialchars($patient["patient_name"]) ?>
                    - Admission #<?= $patient["admission_id"] ?>
                </option>
            <?php endforeach; ?>

        </select>

        <label>Reason for Discharge:</label>

        <textarea
            name="reason"
            placeholder="Enter the reason for discharge..."
            required
        ></textarea>

        <button type="submit">Submit Discharge Request</button>

    </form>

    <p>
        <a href="dashboard.php">← Back to Doctor Dashboard</a>
    </p>

</div>

</body>
</html>
