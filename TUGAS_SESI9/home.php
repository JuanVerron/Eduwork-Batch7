<?php
$pageTitle = "Home";
if (session_status() === PHP_SESSION_NONE) session_start();
include 'components/template.php';
include 'components/navbar.php';
include 'koneksi.php';

$search = trim($_GET['search'] ?? '');
$categoryFilter = trim($_GET['category'] ?? '');
$sortPrice = $_GET['sort_price'] ?? 'desc';
$allowedSort = ['asc', 'desc'];

if (!in_array($sortPrice, $allowedSort, true)) {
    $sortPrice = 'desc';
}

$pendingTransactions = [];
if (isset($_SESSION['user_id'])) {
    $userId = (int) $_SESSION['user_id'];
    $queryPending = "SELECT id, total, status, created_at
                     FROM transaction
                     WHERE user_id = '$userId' AND status = 'menunggu pembayaran'
                     ORDER BY created_at DESC";
    $resultPending = mysqli_query($conn, $queryPending);

    while ($rowPending = mysqli_fetch_assoc($resultPending)) {
        $pendingTransactions[] = $rowPending;
    }
}

$categoryOptions = [];
$resultCategories = mysqli_query($conn, "SELECT DISTINCT category FROM product WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
while ($rowCategory = mysqli_fetch_assoc($resultCategories)) {
    $categoryOptions[] = $rowCategory['category'];
}

$conditions = [];
if ($search !== '') {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(nama LIKE '%$searchEscaped%' OR deskripsi LIKE '%$searchEscaped%' OR category LIKE '%$searchEscaped%')";
}

if ($categoryFilter !== '') {
    $categoryEscaped = mysqli_real_escape_string($conn, $categoryFilter);
    $conditions[] = "category = '$categoryEscaped'";
}

$query = "SELECT * FROM product";
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}
$query .= " ORDER BY price " . strtoupper($sortPrice) . ", id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-grid me-2 text-primary"></i>Produk Kami</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'cart'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>Produk berhasil ditambahkan ke keranjang!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($pendingTransactions)): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="bi bi-receipt-cutoff me-2 text-warning"></i>Transaksi Belum Selesai</h5>
                        <p class="text-muted mb-0">Kamu masih punya transaksi yang menunggu pembayaran. Kamu bisa lanjutkan kapan saja dari sini.</p>
                    </div>
                    <span class="badge bg-warning text-dark fs-6"><?= count($pendingTransactions) ?> transaksi pending</span>
                </div>

                <div class="row g-3">
                    <?php foreach ($pendingTransactions as $pending): ?>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="fw-semibold">Transaksi #<?= $pending['id'] ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($pending['created_at']) ?></div>
                                    </div>
                                    <span class="badge bg-warning text-dark"><?= ucfirst($pending['status']) ?></span>
                                </div>
                                <div class="fw-bold text-success mb-3">Rp <?= number_format($pending['total'], 0, ',', '.') ?></div>
                                <a href="transaction_status.php?id=<?= $pending['id'] ?>" class="btn btn-warning w-100 fw-semibold">
                                    <i class="bi bi-credit-card me-1"></i>Lanjutkan Pembayaran
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="home.php" method="GET" id="productFilterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fw-semibold">Cari Produk</label>
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari nama, deskripsi, atau kategori..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="category" id="categoryFilter" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categoryOptions as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>" <?= $categoryFilter === $category ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-2">
                        <label class="form-label fw-semibold">Urutkan Harga</label>
                        <select name="sort_price" id="sortPriceFilter" class="form-select">
                            <option value="asc" <?= $sortPrice === 'asc' ? 'selected' : '' ?>>Termurah</option>
                            <option value="desc" <?= $sortPrice === 'desc' ? 'selected' : '' ?>>Termahal</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-2 d-grid">
                        <button type="submit" class="btn btn-dark fw-semibold" id="filterSubmitButton">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <?php if ($search !== '' || $categoryFilter !== '' || $sortPrice !== 'desc'): ?>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3 pt-3 border-top">
                    <small class="text-muted">Menampilkan hasil sesuai filter yang dipilih.</small>
                    <a href="home.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($produk = mysqli_fetch_assoc($result)): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0">
                        <?php if ($produk['image']): ?>
                            <img src="image/<?= htmlspecialchars($produk['image']) ?>"
                                 class="card-img-top"
                                 style="height:200px; object-fit:cover;"
                                 alt="<?= htmlspecialchars($produk['nama']) ?>">
                        <?php else: ?>
                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
                                <i class="bi bi-image text-white" style="font-size:3rem;"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary mb-2" style="width:fit-content;">
                                <?= htmlspecialchars($produk['category']) ?>
                            </span>
                            <h5 class="card-title fw-semibold"><?= htmlspecialchars($produk['nama']) ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars($produk['deskripsi']) ?></p>
                            <p class="fw-bold text-success fs-5 mt-2">Rp <?= number_format($produk['price'], 0, ',', '.') ?></p>

                            <!-- Stock indicator -->
                            <?php if ($produk['stock'] > 10): ?>
                                <span class="badge bg-success mb-2" style="width:fit-content;">
                                    <i class="bi bi-check-circle me-1"></i>Stok: <?= $produk['stock'] ?>
                                </span>
                            <?php elseif ($produk['stock'] > 0): ?>
                                <span class="badge bg-warning text-dark mb-2" style="width:fit-content;">
                                    <i class="bi bi-exclamation-circle me-1"></i>Sisa: <?= $produk['stock'] ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger mb-2" style="width:fit-content;">
                                    <i class="bi bi-x-circle me-1"></i>Stok Habis
                                </span>
                            <?php endif; ?>

                            <!-- Form tambah ke cart -->
                            <?php if ($produk['stock'] > 0): ?>
                                <form action="tambah_cart.php" method="POST">
                                    <input type="hidden" name="product_id"    value="<?= $produk['id'] ?>">
                                    <input type="hidden" name="product_nama"  value="<?= htmlspecialchars($produk['nama']) ?>">
                                    <input type="hidden" name="product_price" value="<?= $produk['price'] ?>">
                                    <input type="hidden" name="product_image" value="<?= $produk['image'] ?>">
                                    <input type="hidden" name="product_stock" value="<?= $produk['stock'] ?>">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" name="qty" value="1" min="1" max="<?= $produk['stock'] ?>" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="bi bi-x-circle me-1"></i>Stok Habis
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>Belum ada produk.
                    <a href="admin/create.php">Tambah produk sekarang</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer><p class="mb-0">&copy; <?= date('Y') ?> TokoKu — Bootcamp Eduwork Sesi 9</p></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const productFilterForm = document.getElementById('productFilterForm');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortPriceFilter = document.getElementById('sortPriceFilter');
    let filterTimeout;

    function submitProductFilter() {
        if (!productFilterForm) return;
        productFilterForm.submit();
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(submitProductFilter, 400);
        });
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', submitProductFilter);
    }

    if (sortPriceFilter) {
        sortPriceFilter.addEventListener('change', submitProductFilter);
    }
</script>
</div></body></html>
