<?php
session_start();
include "db.php";
header("Content-Type: application/json");

// Provjera je li korisnik admin (korisnik_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    http_response_code(403);
    echo json_encode(["error" => "Samo admini mogu pristupiti"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Dohvatanje svih proizvoda
if ($action === 'get_products') {
    $query = "SELECT * FROM proizvodi ORDER BY proizvod_id DESC";
    $result = mysqli_query($conn, $query);
    $products = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    
    echo json_encode($products);
}

// Dodavanje novog proizvoda
else if ($action === 'add_product') {
    $category_id = intval($_POST['category_id'] ?? 0);
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $image = $_POST['image'] ?? '';
    
    if (!$category_id || !$name || $price <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Morate ispuniti obavezna polja"]);
        exit;
    }
    
    $query = "INSERT INTO proizvodi (naziv, opis, cijena, kategorija_id, kolicina_na_skladistu, slika) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssdsds", $name, $description, $price, $category_id, $stock, $image);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["message" => "Proizvod dodan", "id" => mysqli_insert_id($conn)]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Greška pri dodavanju proizvoda"]);
    }
}

// Ažuriranje proizvoda
else if ($action === 'update_product') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    
    if (!$product_id || !$name || $price <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Morate ispuniti obavezna polja"]);
        exit;
    }
    
    $query = "UPDATE proizvodi SET naziv = ?, opis = ?, cijena = ?, kolicina_na_skladistu = ? WHERE proizvod_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssdii", $name, $description, $price, $stock, $product_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["message" => "Proizvod ažuriran"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Greška pri ažuriranju proizvoda"]);
    }
}

// Brisanje proizvoda
else if ($action === 'delete_product') {
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(["error" => "ID proizvoda je obavezan"]);
        exit;
    }
    
    $query = "DELETE FROM proizvodi WHERE proizvod_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["message" => "Proizvod obrisan"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Greška pri brisanju proizvoda"]);
    }
}

// Dohvatanje svih narudžbi
else if ($action === 'get_orders') {
    $query = "SELECT n.*, k.ime, k.prezime, k.email FROM narudzbe n 
              JOIN korisnici k ON n.korisnik_id = k.korisnik_id 
              ORDER BY n.datum_narudzbe DESC";
    $result = mysqli_query($conn, $query);
    $orders = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    
    echo json_encode($orders);
}

// Dohvatanje stavki narudžbe
else if ($action === 'get_order_items') {
    $order_id = intval($_GET['order_id'] ?? 0);
    
    if (!$order_id) {
        http_response_code(400);
        echo json_encode(["error" => "ID narudžbe je obavezan"]);
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

// Ažuriranje statusa narudžbe
else if ($action === 'update_order_status') {
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    
    if (!$order_id || !$status) {
        http_response_code(400);
        echo json_encode(["error" => "Morate ispuniti sve podatke"]);
        exit;
    }
    
    $allowed_status = ['Na čekanju', 'U obradi', 'Poslano', 'Dostavljeno', 'Otkazano'];
    if (!in_array($status, $allowed_status)) {
        http_response_code(400);
        echo json_encode(["error" => "Neispravna narudžba"]);
        exit;
    }
    
    $query = "UPDATE narudzbe SET status = ? WHERE narudzba_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["message" => "Status ažuriran"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Greška pri ažuriranju statusa"]);
    }
}

else {
    http_response_code(400);
    echo json_encode(["error" => "Neznana akcija"]);
}
?>
