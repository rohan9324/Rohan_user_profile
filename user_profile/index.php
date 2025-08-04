<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card shadow-sm p-4 text-center" style="width: 300px;">
    <h4 class="mb-3">Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h4>
    <button onclick="window.location='task.php'" class="btn btn-success w-100 mb-2">Tasks</button>
    <button onclick="window.location='profile.php'" class="btn btn-info w-100 mb-2">Profile</button>
    <button onclick="window.location='logout.php'" class="btn btn-danger w-100 mb-2">Logout</button>
</div>
<script src="js/script.js"></script>

</body>
</html>