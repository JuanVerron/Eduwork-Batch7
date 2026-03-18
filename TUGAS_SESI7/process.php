<?php
$nama      = trim($_POST['nama']      ?? '');
$harga     = trim($_POST['harga']     ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$kategori  = trim($_POST['kategori']  ?? '');
$stok      = trim($_POST['stok']      ?? '');

$errors = [];
if (empty($nama))      $errors[] = "Nama produk wajib diisi.";
if (empty($harga))     $errors[] = "Harga wajib diisi.";
if (empty($deskripsi)) $errors[] = "Deskripsi wajib diisi.";
if (empty($kategori))  $errors[] = "Kategori wajib dipilih.";
if (empty($stok))      $errors[] = "Stok wajib diisi.";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Processing Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 bg-white p-5 rounded shadow-sm">

            <?php if (!empty($errors)): ?>

                <h2 class="fw-bold mb-4">⚠️ Validasi Gagal</h2>
                <div class="alert alert-danger">
                    <strong>Mohon perbaiki kesalahan berikut:</strong>
                    <ul class="mt-2 mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="index.php" class="btn btn-primary">← Kembali ke Form</a>

            <?php else: ?>

                <h2 class="fw-bold mb-4">Form Processing Result</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">Product Data</h6>
                        <p class="mb-2"><strong>Name:</strong> <?= htmlspecialchars($nama) ?></p>
                        <p class="mb-2"><strong>Price:</strong> Rp<?= number_format($harga, 0, ',', '.') ?></p>
                        <p class="mb-2"><strong>Description:</strong> <?= htmlspecialchars($deskripsi) ?></p>
                        <p class="mb-2"><strong>Category:</strong> <?= htmlspecialchars($kategori) ?></p>
                        <p class="mb-0"><strong>Stock:</strong> <?= htmlspecialchars($stok) ?></p>
                    </div>
                </div>
                <a href="index.php" class="btn btn-primary">Input Another Product</a>

            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>