<?php

session_start();
$conn = new mysqli("localhost", "root", "", "labtask");
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["user"] = $row;
            header("Location: coffee.php");
            exit();
        } else {
            $err = "Invalid password!";
        }
    } else {
        $err = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            width: 350px;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #222;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        input[type="email"], input[type="password"] {
            padding: 0.8rem;
            border: 1px solid #dcdde1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #4078c0;
            outline: none;
        }
        button {
            padding: 0.8rem;
            background: #4078c0;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover {
            background: #305a8c;
        }
        .error {
            color: #e74c3c;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.98rem;
        }
        label {
            font-weight: 500;
            margin-bottom: 0.3rem;
            color: #555;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form method="post">
        <div>
            <label for="email">DIU Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <?php if ($err): ?>
        <div class="error"><?= $err ?></div>
    <?php endif; ?>
</div>
</body>
</html>