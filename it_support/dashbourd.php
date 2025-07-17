<?php
session_start();
if (!isset($_SESSION['it_user'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>IT Dashboard</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <h2>🖥️ IT Support Dashboard</h2>
  <p>Welcome, <?= $_SESSION['it_user']['full_name']; ?> | <a href="../auth/logout.php">Logout</a></p>

  <table border="1" cellpadding="5">
    <tr>
      <th>ID</th>
      <th>PC Number</th>
      <th>Department</th>
      <th>Issue</th>
      <th>Status</th>
      <th>Requested At</th>
      <th>Action</th>
    </tr>

    <?php
    $res = $conn->query("SELECT * FROM help_requests ORDER BY requested_at DESC");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['pc_number']}</td>
                <td>{$row['department']}</td>
                <td>{$row['issue']}</td>
                <td>{$row['status']}</td>
                <td>{$row['requested_at']}</td>
                <td>
                    <form action='update_status.php' method='POST'>
                      <input type='hidden' name='id' value='{$row['id']}'>
                      <select name='status'>
                        <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                        <option value='In Progress' " . ($row['status'] == 'In Progress' ? 'selected' : '') . ">In Progress</option>
                        <option value='Resolved' " . ($row['status'] == 'Resolved' ? 'selected' : '') . ">Resolved</option>
                      </select>
                      <button type='submit'>Update</button>
                    </form>
                </td>
              </tr>";
    }
    ?>
  </table>
</body>
</html>
