<?php
session_start();
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE name = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;
    echo "OK";
} else {
    echo "ERROR";
}
?>
