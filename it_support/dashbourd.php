<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>

  <h2>🖥️ IT Support Dashboard</h2>
  <p>Welcome, <?= $_SESSION['it_user']['full_name']; ?> | <a href="../auth/logout.php">Logout</a></p>

  <!-- 🔊 Alert Sound -->
  <audio id="alertSound" src="../assets/sound/alert.mp3" preload="auto"></audio>

  <table id="requestTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>PC Number</th>
        <th>Department</th>
        <th>Issue</th>
        <th>Status</th>
        <th>Requested At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <!-- Data will be loaded by AJAX -->
    </tbody>
  </table>

  <script>
    let lastCount = 0;

    function playSound() {
      const audio = document.getElementById("alertSound");
      audio.play().catch((e) => {
        console.log("Autoplay prevented. User interaction required.");
      });
    }

    function fetchRequests() {
      $.ajax({
        url: './fetch_requests.php',
        method: 'GET',
        success: function(data) {
          const requests = JSON.parse(data);
          const tableBody = $('#requestTable tbody');
          tableBody.empty();

          console.log("Current requests count: ", requests.length); // Debugging line

          if (requests.length > lastCount) {
            if (lastCount !== 0) playSound(); // avoid playing on initial load
          }

          lastCount = requests.length;

          if (requests.length === 0) {
            tableBody.append('<tr><td colspan="7">No help requests found.</td></tr>');
          } else {
            requests.forEach(row => {
              const newRow = `
                <tr>
                  <td>${row.id}</td>
                  <td>${row.pc_number}</td>
                  <td>${row.department}</td>
                  <td>${row.issue}</td>
                  <td>${row.status}</td>
                  <td>${row.requested_at}</td>
                  <td>
                    <form action='update_status.php' method='POST'>
                      <input type='hidden' name='id' value='${row.id}'>
                      <select name='status'>
                        <option value='Pending' ${row.status == 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value='In Progress' ${row.status == 'In Progress' ? 'selected' : ''}>In Progress</option>
                        <option value='Resolved' ${row.status == 'Resolved' ? 'selected' : ''}>Resolved</option>
                      </select>
                      <button type='submit'>Update</button>
                    </form>
                  </td>
                </tr>
              `;
              tableBody.append(newRow);
            });
          }
        }
      });
    }

    // Initial fetch
    fetchRequests();

    // Fetch every 5 seconds
    setInterval(fetchRequests, 5000);
  </script>
</body>
</html>
