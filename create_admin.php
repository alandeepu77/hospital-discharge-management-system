
<?php
require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($full_name === "" || $username === "" || $password === "") {
        $message = "Please fill in all fields.";
    } elseif (strlen($password) < 8) {
        $message = "Password must contain at least 8 characters.";
    } else {
        $check = $conn->prepare(
            "SELECT user_id FROM users WHERE username = ?"
        );
        $check->execute([$username]);

        if ($check->fetch()) {
            $message = "This username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, username, password, role)
                 VALUES (?, ?, ?, 'admin')"
            );

            $stmt->execute([
                $full_name,
                $username,
                $hashed_password
            ]);

            $message = "Admin account created successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin Account</title>
</head>
<body>
    <h2>Create Admin Account</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <label>Full Name:</label><br>
        <input type="text" name="full_name" required><br><br>

        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password (minimum 8 characters):</label><br>
        <input type="password" name="password" minlength="8" required><br><br>

        <button type="submit">Create Admin</button>
    </form>
</body>
</html>
