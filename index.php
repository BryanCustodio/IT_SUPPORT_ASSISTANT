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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SSC IT Help System</title>
  <link rel="stylesheet" href="./assets/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #f3f6fb;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #003366;
      color: #fff;
      padding: 15px 30px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
      padding: 8px 10px;
      border: none;
      border-radius: 4px;
      font-size: 14px;
    }

    .login-form button {
      background-color: #00b386;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .login-form button:hover {
      background-color: #009970;
    }

    .container {
      display: flex;
      justify-content: center;
      padding: 40px 20px;
    }

    .form-box {
      background-color: white;
      width: 100%;
      max-width: 600px;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .form-box h2 {
      margin-bottom: 20px;
      color: #003366;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
      color: #333;
    }

    input[type="text"], select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
      transition: border 0.3s ease;
    }

    input[type="text"]:focus,
    select:focus,
    textarea:focus {
      border-color: #007acc;
      outline: none;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    button[type="submit"] {
      margin-top: 20px;
      background-color: #003366;
      color: white;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #005599;
    }

    .error {
      color: red;
      margin-top: 15px;
      text-align: center;
    }

    @media (max-width: 600px) {
      .navbar {
        flex-direction: column;
        gap: 10px;
      }

      .login-form {
        flex-direction: column;
        width: 100%;
      }

      .login-form input,
      .login-form button {
        width: 100%;
      }
    }

    .marquee-container {
  position: fixed;
  bottom: 0;
  width: 100%;
  background-color: #003366;
  overflow: hidden;
  height: 40px;
  display: flex;
  align-items: center;
}

.marquee {
  display: inline-block;
  white-space: nowrap;
  padding-left: 100%;
  animation: scroll-left 15s linear infinite;
  color: #fff;
  font-size: 16px;
  font-weight: 600;
}

@keyframes scroll-left {
  0% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(-100%);
  }
}

  </style>
</head>
<body>

  <div class="navbar">
    <h1>🛠️ SSC IT Help Desk</h1>
    <form method="POST" class="login-form">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>

  <div class="container">
    <div class="form-box">
      <h2>📨 Request IT Assistance</h2>
      <form action="./staff/submit_request.php" method="POST">
        <label for="pc_number">💻 PC Number</label>
        <input type="text" name="pc_number" id="pc_number" required>

        <label for="department">🏢 Department</label>
        <select name="department" id="department" required>
          <option value="">-- Select Department --</option>
          <option value="HR">HR</option>
          <option value="IT">IT</option>
          <option value="Finance">Finance</option>
          <option value="Registrar">Registrar</option>
          <option value="B2B">B2B</option>
        </select>

        <label for="issue">📝 Issue Description (optional)</label>
        <textarea name="issue" id="issue" placeholder="Describe the problem you're experiencing..."></textarea>

        <button type="submit">📩 Send Help Request</button>
      </form>
      <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
  </div>


  <div class="marquee-container">
    <div class="marquee">
      🗣️ Hindi ka na mapapaubos kakasigaw dahil may system na!
    </div>
</div>

</body>
</html>
