<?php
include "db.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed);

if (mysqli_stmt_execute($stmt)) {
    echo "OK";
} else {
    echo "ERROR";
}
?>
