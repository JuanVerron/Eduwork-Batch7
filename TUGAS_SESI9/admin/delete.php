<?php
include 'auth.php';
include '../koneksi.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $queryGet = "SELECT image FROM product WHERE id = '$id'";
    $result   = mysqli_query($conn, $queryGet);
    $produk   = mysqli_fetch_assoc($result);

    if ($produk && $produk['image']) {
        $imagePath = '../image/' . $produk['image'];
        if (file_exists($imagePath)) unlink($imagePath);
    }

    mysqli_query($conn, "DELETE FROM product WHERE id = '$id'");
}

header("Location: index.php?success=delete");
exit;
?>
