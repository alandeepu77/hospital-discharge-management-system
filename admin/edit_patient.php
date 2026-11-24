
<?php
session_start();

require_once "../config/database.php";

// Admin access protection
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$error = "";
$message = "";

// Get patient ID
$patient_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$patient_id || $patient_id < 1) {
    die("Invalid patient ID.");
}

// Fetch patient details
$stmt = $conn->prepare(
    "SELECT * FROM patients WHERE patient_id = ?"
);
$stmt->execute([$patient_id]);

$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    die("Patient not found.");
}

// Update patient details
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["patient_name"] ?? "");
    $age = $_POST["age"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");

    if ($name === "") {
        $error = "Patient name is required.";
    } elseif ($age !== "" && (!ctype_digit($age) || (int)$age < 0 || (int)$age > 150)) {
        $error = "Please enter a valid age.";
    } elseif (!in_array($gender, ["Male", "Female", "Other", ""], true)) {
        $error = "Please select a valid gender.";
    } else {

        $update = $conn->prepare(
            "UPDATE patients
             SET patient_name = ?, age = ?, gender = ?, phone = ?, address = ?
             WHERE patient_id = ?"
        );

        $update->execute([
            $name,
            $age === "" ? null : (int)$age,
            $gender === "" ? null : $gender,
            $phone,
            $address,
            $patient_id
        ]);

        $message = "Patient details updated successfully!";

        // Refresh displayed patient details
        $stmt->execute([$patient_id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Patient</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f2f6fa;
    color: #263746;
}

header {
    background: #087f8c;
    color: white;
    padding: 20px 7%;
}

.container {
    max-width: 650px;
    width: 90%;
    margin: 35px auto;
}

.card {
    background: white;
    padding: 28px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.06);
}

h2 {
    color: #087f8c;
}

label {
    display: block;
    margin-top: 16px;
    font-weight: bold;
}

input, select, textarea {
    width: 100%;
    padding: 12px;
    margin-top: 7px;
    border: 1px solid #ccd5dc;
    border-radius: 6px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

button {
    margin-top: 22px;
    padding: 12px 22px;
    background: #087f8c;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #066773;
}

a {
    color: #087f8c;
    text-decoration: none;
}

.message {
    color: green;
}

.error {
    color: red;
}
</style>
</head>

<body>

<header>
    <h2>Hospital Discharge Management System</h2>
</header>

<div class="container">

    <div class="card">

        <h2>Edit Patient Details</h2>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">

            <label>Patient Name</label>
            <input
                type="text"
                name="patient_name"
                value="<?= htmlspecialchars($patient["patient_name"]) ?>"
                required
            >

            <label>Age</label>
            <input
                type="number"
                name="age"
                min="0"
                max="150"
                value="<?= htmlspecialchars((string)($patient["age"] ?? "")) ?>"
            >

            <label>Gender</label>
            <select name="gender">
                <option value="">Select Gender</option>

                <?php foreach (["Male", "Female", "Other"] as $option): ?>
                    <option value="<?= $option ?>"
                        <?= $patient["gender"] === $option ? "selected" : "" ?>>
                        <?= $option ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <label>Phone Number</label>
            <input
                type="tel"
                name="phone"
                value="<?= htmlspecialchars($patient["phone"] ?? "") ?>"
            >

            <label>Address</label>
            <textarea name="address" rows="3"><?= htmlspecialchars($patient["address"] ?? "") ?></textarea>

            <button type="submit">Update Patient</button>

        </form>

        <p>
            <a href="patients.php">← Back to Patient Management</a>
        </p>

    </div>

</div>

</body>
</html>
