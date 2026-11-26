
<?php
require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($full_name === "" || $username === "" || $password === "") {
        $message = "Please fill in all fields.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, username, password, role)
                 VALUES (?, ?, ?, 'doctor')"
            );

            $stmt->execute([
                $full_name,
                $username,
                $hashed_password
            ]);

            $message = "Doctor account created successfully!";

        } catch (PDOException $e) {
            $message = "Could not create account. The username may already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Doctor Account</title>
</head>
<body>

    <h2>Create Doctor Account</h2>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="POST">
        <label>Doctor Name:</label><br>
        <input type="text" name="full_name" required><br><br>

        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Create Doctor Account</button>
    </form>

</body>
</html>
