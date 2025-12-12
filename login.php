<?php
session_start();
include "db.php";

$email = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM korisnici WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['lozinka'])) {
    $_SESSION['user'] = $user;
    $_SESSION['user_id'] = $user['korisnik_id'];
    $_SESSION['is_admin'] = ($user['korisnik_id'] == 1) ? true : false;
    echo "OK";
} else {
    echo "ERROR";
}
?>
