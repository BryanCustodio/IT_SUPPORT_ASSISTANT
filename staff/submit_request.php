<?php
include '../includes/db.php';

$pc_number = $_POST['pc_number'];
$department = $_POST['department'];
$issue = $_POST['issue'];

$sql = "INSERT INTO help_requests (pc_number, department, issue) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $pc_number, $department, $issue);
$stmt->execute();

echo "<script>alert('Request sent successfully!'); window.location.href='../index.php';</script>";
?>
