<?php
session_start();
include "db.php";
header("Content-Type: application/json");

// Provjera je li korisnik prijavljen
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Morate biti prijavljeni"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Dohvatanje stavki u košarici
if ($action === 'get') {
    $query = "SELECT k.*, p.naziv, p.cijena, p.slika FROM kosarica k 
              JOIN proizvodi p ON k.proizvod_id = p.proizvod_id 
              WHERE k.korisnik_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $cart = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cart[] = $row;
    }
    
    echo json_encode($cart);
}

// Dodavanje proizvoda u košaricu
else if ($action === 'add') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($product_id <= 0 || $quantity <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Neispravni parametri"]);
        exit;
    }
    
    // Provjera postoji li proizvod
    $check_query = "SELECT proizvod_id FROM proizvodi WHERE proizvod_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $product_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Proizvod ne postoji"]);
        exit;
    }
    
    // Provjera postoji li već u košarici
    $exists_query = "SELECT id, kolicina FROM kosarica WHERE korisnik_id = ? AND proizvod_id = ?";
    $exists_stmt = mysqli_prepare($conn, $exists_query);
    mysqli_stmt_bind_param($exists_stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($exists_stmt);
    $exists_result = mysqli_stmt_get_result($exists_stmt);
    $existing = mysqli_fetch_assoc($exists_result);
    
    if ($existing) {
        // Ažuriranje količine
        $new_quantity = $existing['kolicina'] + $quantity;
        $update_query = "UPDATE kosarica SET kolicina = ? WHERE korisnik_id = ? AND proizvod_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "iii", $new_quantity, $user_id, $product_id);
        mysqli_stmt_execute($update_stmt);
    } else {
        // Dodavanje novog proizvoda
        $insert_query = "INSERT INTO kosarica (korisnik_id, proizvod_id, kolicina) VALUES (?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "iii", $user_id, $product_id, $quantity);
        mysqli_stmt_execute($insert_stmt);
    }
    
    echo json_encode(["message" => "Proizvod dodan u košaricu"]);
}

// Uklanjanje stavke iz košarice
else if ($action === 'remove') {
    $cart_id = intval($_POST['cart_id'] ?? 0);
    
    if ($cart_id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Neispravni ID"]);
        exit;
    }
    
    $delete_query = "DELETE FROM kosarica WHERE id = ? AND korisnik_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "ii", $cart_id, $user_id);
    mysqli_stmt_execute($delete_stmt);
    
    echo json_encode(["message" => "Stavka obrisana"]);
}

// Ažuriranje količine
else if ($action === 'update') {
    $cart_id = intval($_POST['cart_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($cart_id <= 0 || $quantity <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Neispravni parametri"]);
        exit;
    }
    
    $update_query = "UPDATE kosarica SET kolicina = ? WHERE id = ? AND korisnik_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "iii", $quantity, $cart_id, $user_id);
    mysqli_stmt_execute($update_stmt);
    
    echo json_encode(["message" => "Količina ažurirana"]);
}

else {
    http_response_code(400);
    echo json_encode(["error" => "Neznana akcija"]);
}
?>
