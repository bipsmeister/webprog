<?php
include "db.php";
header("Content-Type: application/json");

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$category_id = intval($_POST['category_id'] ?? $_GET['category_id'] ?? 0);

// Dohvatanje proizvoda po kategoriji
if ($action === '' || $action === 'get_by_category') {
    if ($category_id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Kategorija je obavezna"]);
        exit;
    }

    $query = "SELECT * FROM proizvodi WHERE kategorija_id = ? AND kolicina_na_skladistu > 0 ORDER BY proizvod_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    echo json_encode($products);
}

// Dohvatanje svih proizvoda
else if ($action === 'get_all') {
    $query = "SELECT * FROM proizvodi WHERE kolicina_na_skladistu > 0 ORDER BY proizvod_id DESC";
    $result = mysqli_query($conn, $query);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    echo json_encode($products);
}

// Dohvatanje jednog proizvoda
else if ($action === 'get_single') {
    $product_id = intval($_GET['product_id'] ?? $_POST['product_id'] ?? 0);
    
    if ($product_id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "ID proizvoda je obavezan"]);
        exit;
    }

    $query = "SELECT * FROM proizvodi WHERE proizvod_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        http_response_code(404);
        echo json_encode(["error" => "Proizvod nije pronađen"]);
        exit;
    }

    echo json_encode($product);
}

// Dohvatanje svih kategorija
else if ($action === 'get_categories') {
    $query = "SELECT * FROM kategorije ORDER BY naziv";
    $result = mysqli_query($conn, $query);

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Mapiramo id na kategorija_id za kompatibilnost s JS kodom
        $row['id'] = $row['kategorija_id'];
        $categories[] = $row;
    }

    echo json_encode($categories);
}

else {
    http_response_code(400);
    echo json_encode(["error" => "Neznana akcija"]);
}
?>
