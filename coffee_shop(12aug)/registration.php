<?php
session_start();
$conn = new mysqli("localhost", "root", "", "labtask");
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $birth = $_POST["birth"];
    $password = $_POST["password"];

    // Validation
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $err = "Name invalid!";
    } elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@diu\.edu\.bd$/", $email)) {
        $err = "Email must be @diu.edu.bd!";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/", $password)) {
        $err = "Password must be 9+ chars, a-z, A-Z, 0-9!";
    } else {
        // Serial: birth year + 01 (auto increment)
        $year = date("Y", strtotime($birth));
        $result = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE YEAR(birth)='$year'");
        $row = $result->fetch_assoc();
        $serial = $year . str_pad($row['cnt'] + 1, 2, "0", STR_PAD_LEFT);

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (name, email, birth, password, serial) VALUES ('$name', '$email', '$birth', '$hash', '$serial')");
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <style>
        body {
            background: #f6f8fa;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 28px;
            font-weight: 600;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        input[type="text"], input[type="email"], input[type="date"], input[type="password"] {
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        input:focus {
            border-color: #2563eb;
            outline: none;
        }
        button {
            padding: 12px;
            background: linear-gradient(90deg,#2563eb 0%, #1e40af 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover {
            background: linear-gradient(90deg,#1e40af 0%, #2563eb 100%);
        }
        .error {
            color: #ef4444;
            text-align: center;
            margin-top: 12px;
            font-size: 15px;
        }
        label {
            font-size: 15px;
            color: #374151;
            margin-bottom: 4px;
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <form method="post">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="email">DIU Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="birth">Birth Date</label>
            <input type="date" name="birth" id="birth" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Register</button>
    </form>
    <?php if ($err): ?>
        <div class="error"><?= $err ?></div>
    <?php endif; ?>
</div>
</body>
</html>