<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Harus login dulu
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'home.php';
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['product_id'];
    $nama  = $_POST['product_nama'];
    $price = $_POST['product_price'];
    $image = $_POST['product_image'];
    $stock = (int) $_POST['product_stock'];
    $qty   = (int) $_POST['qty'];

    // Pastikan qty tidak melebihi stock
    if ($qty > $stock) $qty = $stock;
    if ($qty < 1) $qty = 1;

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (isset($_SESSION['cart'][$id])) {
        $newQty = $_SESSION['cart'][$id]['qty'] + $qty;
        $_SESSION['cart'][$id]['qty'] = min($newQty, $stock);
    } else {
        $_SESSION['cart'][$id] = [
            'id'    => $id,
            'nama'  => $nama,
            'price' => $price,
            'image' => $image,
            'stock' => $stock,
            'qty'   => $qty
        ];
    }
}

header("Location: home.php?success=cart");
exit;
?>
