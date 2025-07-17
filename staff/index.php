<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Request IT Help</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <h2>📢 Request IT Assistance</h2>
  <form action="submit_request.php" method="POST">
    <label>PC Number:</label>
    <input type="text" name="pc_number" required><br>

    <label>Department:</label>
    <select name="department" required>
      <option value="">-- Select Department --</option>
      <option value="HR">HR</option>
      <option value="IT">IT</option>
      <option value="Finance">Finance</option>
      <option value="Registrar">Registrar</option>
    </select><br>

    <label>Issue (optional):</label>
    <textarea name="issue" rows="4" placeholder="Describe your problem..."></textarea><br>

    <button type="submit">Send Help Request</button>
  </form>
</body>
</html>
