<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'koneksi.php';

// Harus login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

    $userId = $_SESSION['user_id'];
    $total  = $_POST['total'];

    // 1. Simpan transaksi
    $queryTrx = "INSERT INTO transaction (user_id, total, status) VALUES ('$userId', '$total', 'menunggu pembayaran')";
    mysqli_query($conn, $queryTrx);
    $transactionId = mysqli_insert_id($conn);

    // 2. Simpan detail produk + kurangi stock
    foreach ($_SESSION['cart'] as $item) {
        $productId  = $item['id'];
        $totalQty   = $item['qty'];
        $totalPrice = $item['price'] * $item['qty'];

        // Insert ke transaction_product
        $queryDetail = "INSERT INTO transaction_product (transaction_id, product_id, total_product, total_price)
                        VALUES ('$transactionId', '$productId', '$totalQty', '$totalPrice')";
        mysqli_query($conn, $queryDetail);

        // Kurangi stock produk
        $queryStock = "UPDATE product SET stock = stock - $totalQty WHERE id = '$productId' AND stock >= $totalQty";
        mysqli_query($conn, $queryStock);
    }

    // 3. Kosongkan cart
    unset($_SESSION['cart']);

    header("Location: transaction_status.php?id=$transactionId");
    exit;
}

header("Location: cart.php");
exit;
?>
