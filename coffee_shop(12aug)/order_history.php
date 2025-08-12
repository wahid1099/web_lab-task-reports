<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION["user"];
$conn = new mysqli("localhost", "root", "", "labtask");
$serial = $user["serial"];
$result = $conn->query("SELECT * FROM orders WHERE serial='$serial' ORDER BY date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Fonts for modern look -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .header strong {
            font-size: 1.1em;
            color: #333;
        }
        .header a {
            text-decoration: none;
            color: #1976d2;
            margin-left: 18px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .header a:hover {
            color: #0d47a1;
        }
        h2 {
            margin-top: 0;
            color: #1976d2;
            font-weight: 700;
            letter-spacing: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            background: #fafbfc;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 14px 12px;
            text-align: left;
        }
        th {
            background: #1976d2;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #1565c0;
        }
        tr:nth-child(even) {
            background: #f1f5f9;
        }
        tr:hover {
            background: #e3f2fd;
        }
        @media (max-width: 600px) {
            .container {
                padding: 12px;
            }
            th, td {
                padding: 8px 6px;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <strong><?= htmlspecialchars($user["email"]) ?></strong>
        <div>
            <a href="coffee.php">Back to Coffee</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <h2>Your Order History</h2>
    <table>
        <tr>
            <th>Serial</th>
            <th>Item</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row["serial"]) ?></td>
            <td><?= htmlspecialchars($row["item"]) ?></td>
            <td><?= htmlspecialchars($row["amount"]) ?></td>
            <td><?= htmlspecialchars($row["date"]) ?></td>
            <td>On Processing</td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>