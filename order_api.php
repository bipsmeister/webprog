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

// Kreiraj narudžbu
if ($action === 'create_order') {
    $delivery_address = $_POST['delivery_address'] ?? '';
    $delivery_city = $_POST['delivery_city'] ?? '';
    $delivery_zip = $_POST['delivery_zip'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if (!$delivery_address || !$delivery_city || !$delivery_zip || !$phone) {
        http_response_code(400);
        echo json_encode(["error" => "Morate ispuniti sve podatke"]);
        exit;
    }
    
    // Dohvatanje stavki iz košarice
    $cart_query = "SELECT k.proizvod_id, k.kolicina, p.cijena FROM kosarica k 
                   JOIN proizvodi p ON k.proizvod_id = p.proizvod_id 
                   WHERE k.korisnik_id = ?";
    $cart_stmt = mysqli_prepare($conn, $cart_query);
    mysqli_stmt_bind_param($cart_stmt, "i", $user_id);
    mysqli_stmt_execute($cart_stmt);
    $cart_result = mysqli_stmt_get_result($cart_stmt);
    
    $items = [];
    $total_price = 0;
    
    while ($row = mysqli_fetch_assoc($cart_result)) {
        $items[] = $row;
        $total_price += $row['cijena'] * $row['kolicina'];
    }
    
    if (empty($items)) {
        http_response_code(400);
        echo json_encode(["error" => "Košarica je prazna"]);
        exit;
    }
    
    // Kreiraj narudžbu
    $order_query = "INSERT INTO narudzbe (korisnik_id, ukupna_cijena) 
                    VALUES (?, ?)";
    $order_stmt = mysqli_prepare($conn, $order_query);
    mysqli_stmt_bind_param($order_stmt, "id", $user_id, $total_price);
    
    if (!mysqli_stmt_execute($order_stmt)) {
        http_response_code(500);
        echo json_encode(["error" => "Greška pri kreiranju narudžbe"]);
        exit;
    }
    
    $order_id = mysqli_insert_id($conn);
    
    // Dodaj stavke u narudžbu
    $item_query = "INSERT INTO stavke_narudzbe (narudzba_id, proizvod_id, kolicina, cijena_po_komadu) VALUES (?, ?, ?, ?)";
    $item_stmt = mysqli_prepare($conn, $item_query);
    
    foreach ($items as $item) {
        mysqli_stmt_bind_param($item_stmt, "iiii", $order_id, $item['proizvod_id'], $item['kolicina'], $item['cijena']);
        mysqli_stmt_execute($item_stmt);
    }
    
    // Obriši košaricu
    $delete_query = "DELETE FROM kosarica WHERE korisnik_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $user_id);
    mysqli_stmt_execute($delete_stmt);
    
    echo json_encode(["message" => "Narudžba kreirana", "order_id" => $order_id, "total_price" => $total_price]);
}

// Dohvati sve narudžbe korisnika
else if ($action === 'get_user_orders') {
    $query = "SELECT * FROM narudzbe WHERE korisnik_id = ? ORDER BY datum_narudzbe DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    
    echo json_encode($orders);
}

// Dohvati stavke narudžbe
else if ($action === 'get_order_items') {
    $order_id = intval($_GET['order_id'] ?? 0);
    
    if (!$order_id) {
        http_response_code(400);
        echo json_encode(["error" => "ID narudžbe je obavezan"]);
        exit;
    }
    
    // Provjeri je li narudžba korisnika
    $check_query = "SELECT narudzba_id FROM narudzbe WHERE narudzba_id = ? AND korisnik_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "ii", $order_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) === 0) {
        http_response_code(403);
        echo json_encode(["error" => "Pristup odbijen"]);
        exit;
    }
    
    $query = "SELECT sn.*, p.naziv FROM stavke_narudzbe sn 
              JOIN proizvodi p ON sn.proizvod_id = p.proizvod_id 
              WHERE sn.narudzba_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    
    echo json_encode($items);
}

else {
    http_response_code(400);
    echo json_encode(["error" => "Neznana akcija"]);
}
?>
