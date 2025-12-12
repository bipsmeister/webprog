<?php
include "db.php";

$ime = $_POST['ime'];
$prezime = $_POST['prezime'];
$email = $_POST['email'];
$password = $_POST['password'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO korisnici (ime, prezime, email, lozinka) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssss", $ime, $prezime, $email, $hashed);

if (mysqli_stmt_execute($stmt)) {
    echo "OK";
} else {
    echo "ERROR";
}
?>
