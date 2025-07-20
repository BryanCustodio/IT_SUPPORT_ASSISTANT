<?php
// session_start();
// include '../includes/db.php';

// if (!isset($_SESSION['it_user'])) {
//     header("Location: ../auth/login.php");
//     exit;
// }

// $res = $conn->query("SELECT * FROM help_requests ORDER BY requested_at DESC");
// $requests = [];

// while ($row = $res->fetch_assoc()) {
//     $requests[] = $row;
// }

// echo json_encode($requests);
?>
<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['it_user'])) {
    header("Location: ../index.php");
    exit;
}

$res = $conn->query("SELECT * FROM help_requests ORDER BY requested_at DESC");
$requests = [];

while ($row = $res->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);
?>
