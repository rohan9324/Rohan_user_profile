<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Check duplicate email
        $check = $conn->prepare("SELECT id FROM user WHERE email=?");
        if (!$check) {
            die("SQL Error (Check Email): " . $conn->error);  // shows actual error
        }
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "<script>alert('Email already registered');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO user (name,email,password) VALUES (?,?,?)");
            if (!$stmt) {
                die("SQL Error (Insert User): " . $conn->error);  // shows actual error
            }
            $stmt->bind_param("sss", $name, $email, $hashed);
            if ($stmt->execute()) {
                echo "<script>alert('Registration Successful'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Registration Failed');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card shadow-sm p-3" style="width: 300px;">
    <h4 class="text-center mb-3">Register</h4>
    <form method="POST">
        <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <input type="password" name="cpassword" class="form-control mb-3" placeholder="Confirm Password" required>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <a href="login.php" class="d-block text-center mt-2">Login</a>
</div>
<script src="js/script.js"></script>

</body>
</html>