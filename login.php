
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hospital Discharge Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #e0f2f1, #e3f2fd);
        }

        .login-box {
            width: 360px;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #087e8b;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 7px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #087e8b;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #05636d;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            color: #087e8b;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>Welcome Back</h2>
        <p class="subtitle">Hospital Discharge Management System</p>

        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Enter your password" required>

            <button type="submit">Login</button>
        </form>

        <div class="back">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>

</body>
</html>
