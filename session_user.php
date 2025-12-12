<?php
session_start();
header("Content-Type: application/json");

if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user['is_admin'] = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
    echo json_encode($user);
} else {
    echo json_encode(null);
}
?>
