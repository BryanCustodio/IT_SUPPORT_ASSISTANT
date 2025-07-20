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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IT Dashboard</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f4f7fc;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background-color: #003366;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar h2 {
      margin: 0;
      font-size: 22px;
    }

    .navbar a {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
    }

    .container {
      max-width: 95%;
      margin: 30px auto;
      background: #fff;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border-radius: 10px;
    }

    table.dataTable thead th {
      background-color: #003366;
      color: white;
    }

    .dataTables_wrapper .dataTables_length select {
      padding: 5px;
      border-radius: 4px;
    }

    form {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    select[name="status"] {
      padding: 3px 5px;
    }

    button[type="submit"] {
      padding: 4px 8px;
      background: #003366;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #0055a5;
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
      z-index: 999;
    }

    .marquee {
      display: inline-block;
      white-space: nowrap;
      padding-left: 100%;
      animation: scroll-left 20s linear infinite;
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
    <h2>🖥️ IT Support Dashboard</h2>
    <div>
      Welcome, <?= $_SESSION['it_user']['full_name']; ?> |
      <a href="../auth/logout.php">Logout</a>
    </div>
  </div>

  <div class="container">
    <table id="requestTable" class="display responsive nowrap" width="100%">
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
        <!-- Filled by JS -->
      </tbody>
    </table>
  </div>

  <!-- 🔊 Alert Sound -->
  <audio id="alertSound" src="../assets/sound/alert.mp3" preload="auto"></audio>

  <!-- 🔁 Marquee Text -->
  <div class="marquee-container">
    <div class="marquee">🗣️ Hindi ka na mapapaubos kakasigaw dahil may system na!</div>
  </div>

  <script>
    let lastCount = 0;

    function playSound() {
      const audio = document.getElementById("alertSound");
      audio.play().catch(() => {});
    }

    function fetchRequests() {
      $.ajax({
        url: './fetch_requests.php',
        method: 'GET',
        success: function(data) {
          const requests = JSON.parse(data);
          const table = $('#requestTable').DataTable();
          table.clear();

          if (requests.length > lastCount && lastCount !== 0) {
            playSound();
          }

          lastCount = requests.length;

          if (requests.length === 0) {
            table.row.add([
              '', '', '', 'No help requests found.', '', '', ''
            ]).draw();
          } else {
            requests.forEach(row => {
              table.row.add([
                row.id,
                row.pc_number,
                row.department,
                row.issue,
                row.status,
                row.requested_at,
                `
                <form action='update_status.php' method='POST'>
                  <input type='hidden' name='id' value='${row.id}'>
                  <select name='status'>
                    <option value='Pending' ${row.status === 'Pending' ? 'selected' : ''}>Pending</option>
                    <option value='In Progress' ${row.status === 'In Progress' ? 'selected' : ''}>In Progress</option>
                    <option value='Resolved' ${row.status === 'Resolved' ? 'selected' : ''}>Resolved</option>
                  </select>
                  <button type='submit'>Update</button>
                </form>
                `
              ]).draw(false);
            });
          }
        }
      });
    }

    $(document).ready(function() {
      $('#requestTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 15, 30, 50, 100],
        order: [[0, 'desc']],
        language: {
          emptyTable: "Loading help requests..."
        }
      });

      fetchRequests();
      setInterval(fetchRequests, 5000);
    });
  </script>
</body>
</html>
