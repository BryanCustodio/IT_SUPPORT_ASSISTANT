<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = hash("sha256", $_POST['password']);

    $sql = "SELECT * FROM it_users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['it_user'] = $res->fetch_assoc();
        header("Location: ../it_support/dashbourd.php");
        exit; // Ensure to exit after redirection
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>IT Support Login</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <h2>🔐 IT Support Login</h2>
  <form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <button type="submit">Login</button>
  </form>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
