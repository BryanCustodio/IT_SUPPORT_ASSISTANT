<?php 
session_start();
include './includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = hash("sha256", $_POST['password']);

    $sql = "SELECT * FROM it_users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['it_user'] = $res->fetch_assoc();
        header("Location: ./it_support/dashbourd.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>IT Help Request & Login</title>
  <link rel="stylesheet" href="./assets/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #004080;
      color: #fff;
      padding: 10px 20px;
    }

    .navbar h1 {
      margin: 0;
      font-size: 20px;
    }

    .login-form {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .login-form input {
      padding: 5px;
      border: none;
      border-radius: 4px;
    }

    .login-form button {
      padding: 5px 10px;
      background-color: #00cc66;
      border: none;
      color: white;
      border-radius: 4px;
      cursor: pointer;
    }

    .container {
      padding: 20px;
    }

    .form-box {
      max-width: 500px;
      margin: 20px auto;
      background: #f7f7f7;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-top: 10px;
    }

    input[type="text"], select, textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button[type="submit"] {
      margin-top: 15px;
      background-color: #004080;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <h1>📡 SSC IT Help System</h1>
    <form method="POST" class="login-form">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>

  <div class="container">
    <div class="form-box">
      <h2>🆘 Request IT Assistance</h2>
      <form action="./staff/submit_request.php" method="POST">
        <label>PC Number:</label>
        <input type="text" name="pc_number" required>

        <label>Department:</label>
        <select name="department" required>
          <option value="">-- Select Department --</option>
          <option value="HR">HR</option>
          <option value="IT">IT</option>
          <option value="Finance">Finance</option>
          <option value="Registrar">Registrar</option>
          <option value="B2B">B2B</option>
        </select>

        <label>Issue (optional):</label>
        <textarea name="issue" rows="4" placeholder="Describe your problem..."></textarea>

        <button type="submit">Send Help Request</button>
      </form>
    </div>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
  </div>

</body>
</html>
