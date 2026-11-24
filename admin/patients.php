
<?php
session_start();

require_once "../config/database.php";

// Admin access protection
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$message = "";
$error = "";

// Register patient
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["patient_name"] ?? "");
    $age = $_POST["age"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");

    if ($name === "") {
        $error = "Please enter the patient's name.";
    } elseif ($age !== "" && (!ctype_digit($age) || (int)$age < 0 || (int)$age > 150)) {
        $error = "Please enter a valid age.";
    } elseif (!in_array($gender, ["Male", "Female", "Other", ""], true)) {
        $error = "Please select a valid gender.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO patients (patient_name, age, gender, phone, address)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $name,
            $age === "" ? null : (int)$age,
            $gender === "" ? null : $gender,
            $phone,
            $address
        ]);

        $message = "Patient registered successfully!";
    }
}

// Search patients
$search = trim($_GET["search"] ?? "");

$stmt = $conn->prepare(
    "SELECT * FROM patients
     WHERE patient_name LIKE ?
     ORDER BY patient_id DESC"
);

$stmt->execute(["%" . $search . "%"]);
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Patient Management</title>

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
    padding: 20px 7%;
}

header h2 {
    margin: 0;
}

.container {
    width: 90%;
    max-width: 1100px;
    margin: 30px auto;
}

.back {
    display: inline-block;
    margin-bottom: 20px;
    color: #087f8c;
    text-decoration: none;
    font-weight: bold;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.05);
}

h3 {
    margin-top: 0;
    color: #087f8c;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

input, select, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccd5dc;
    border-radius: 6px;
    margin-top: 7px;
}

textarea {
    resize: vertical;
}

button {
    background: #087f8c;
    color: white;
    border: none;
    padding: 12px 22px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 18px;
}

button:hover {
    background: #066773;
}

.message {
    color: green;
    margin-bottom: 15px;
}

.error {
    color: red;
    margin-bottom: 15px;
}

.search-form {
    display: flex;
    gap: 10px;
}

.search-form input {
    flex: 1;
}

.search-form button {
    margin-top: 7px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th, td {
    padding: 13px;
    text-align: left;
    border-bottom: 1px solid #e5e9ed;
}

th {
    background: #087f8c;
    color: white;
}

@media(max-width: 650px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<header>
    <h2>Hospital Discharge Management System</h2>
</header>

<div class="container">

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

    <div class="card">
        <h3>Register New Patient</h3>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">

            <div class="form-grid">

                <div>
                    <label>Patient Name *</label>
                    <input type="text" name="patient_name" required>
                </div>

                <div>
                    <label>Age</label>
                    <input type="number" name="age" min="0" max="150">
                </div>

                <div>
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label>Phone Number</label>
                    <input type="tel" name="phone">
                </div>

                <div style="grid-column: 1 / -1;">
                    <label>Address</label>
                    <textarea name="address" rows="3"></textarea>
                </div>

            </div>

            <button type="submit">Register Patient</button>

        </form>
    </div>

    <div class="card">

        <h3>Registered Patients</h3>

        <form method="GET" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Search patient by name"
                value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit">Search</button>
        </form>

        <br>

        <div class="table-wrapper">

            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>

                <?php if (count($patients) > 0): ?>

                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= $patient["patient_id"] ?></td>
                            <td><?= htmlspecialchars($patient["patient_name"]) ?></td>
                            <td><?= htmlspecialchars((string)($patient["age"] ?? "")) ?></td>
                            <td><?= htmlspecialchars($patient["gender"] ?? "") ?></td>
                            <td><?= htmlspecialchars($patient["phone"] ?? "") ?></td>
                            <td>
    <a href="edit_patient.php?id=<?= $patient['patient_id'] ?>">
        Edit
    </a>
</td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5">No patients found.</td>
                    </tr>

                <?php endif; ?>

            </table>

        </div>

    </div>

</div>

</body>
</html>
