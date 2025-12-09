<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "trgovina";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Greška pri spajanju: " . mysqli_connect_error());
}
?>
