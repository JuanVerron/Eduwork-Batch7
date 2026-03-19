<?php include 'connect.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tugas Sesi 8</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$urutan   = isset($_GET['urutan'])   ? $_GET['urutan']   : '';

$query = "SELECT * FROM products";

if ($kategori != '') {
    $kategori_aman = mysqli_real_escape_string($conn, $kategori);
    $query .= " WHERE category = '$kategori_aman'";
}

if ($urutan == 'termurah') {
    $query .= " ORDER BY price ASC";
} elseif ($urutan == 'termahal') {
    $query .= " ORDER BY price DESC";
}

$result = mysqli_query($conn, $query);
?>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Toko Online Sesi 8</a>
    </div>
</nav>

<div class="container pb-5">
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="fw-semibold text-muted small">Kategori:</span>
                <?php
                $daftar_kategori = ['Elektronik', 'Fashion', 'Makanan', 'Buku'];
                $base = '?urutan=' . $urutan;
                ?>
                <a href="<?= $base ?>" 
                   class="btn btn-sm <?= $kategori == '' ? 'btn-dark' : 'btn-outline-dark' ?>">
                   Semua
                </a>
                <?php foreach ($daftar_kategori as $kat) : ?>
                    <a href="<?= $base ?>&kategori=<?= $kat ?>" 
                       class="btn btn-sm <?= $kategori == $kat ? 'btn-dark' : 'btn-outline-dark' ?>">
                       <?= $kat ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="vr d-none d-md-block"></div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="fw-semibold text-muted small">Urutkan:</span>
                <?php $base_kat = '?kategori=' . $kategori; ?>
                <a href="<?= $base_kat ?>" 
                   class="btn btn-sm <?= $urutan == '' ? 'btn-dark' : 'btn-outline-dark' ?>">
                   Default
                </a>
                <a href="<?= $base_kat ?>&urutan=termurah" 
                   class="btn btn-sm <?= $urutan == 'termurah' ? 'btn-success' : 'btn-outline-success' ?>">
                   Termurah
                </a>
                <a href="<?= $base_kat ?>&urutan=termahal" 
                   class="btn btn-sm <?= $urutan == 'termahal' ? 'btn-danger' : 'btn-outline-danger' ?>">
                   Termahal
                </a>
            </div>

        </div>
    </div>

    <p class="text-muted small mb-3">
        Menampilkan <strong><?= mysqli_num_rows($result) ?></strong> produk
        <?= $kategori != '' ? "dalam kategori <strong>$kategori</strong>" : '' ?>
    </p>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while ($produk = mysqli_fetch_assoc($result)) : ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="https://picsum.photos/400/250?random=<?= $produk['id'] ?>"
                         class="card-img-top" alt="<?= $produk['name'] ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary"><?= $produk['category'] ?></span>
                        </div>
                        <h6 class="card-title fw-bold"><?= $produk['name'] ?></h6>
                        <p class="card-text text-muted small flex-grow-1"><?= $produk['description'] ?></p>
                        <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-success fs-6">
                                Rp <?= number_format($produk['price'], 0, ',', '.') ?>
                            </span>
                            <button class="btn btn-dark btn-sm">+ Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

</body>
</html>