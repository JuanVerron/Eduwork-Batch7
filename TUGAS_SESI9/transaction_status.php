<?php
$pageTitle = "Status Transaksi";
if (session_status() === PHP_SESSION_NONE) session_start();
include 'components/template.php';
include 'components/navbar.php';
include 'koneksi.php';

$id = $_GET['id'] ?? null;

// Proses update status (Bayar / Batalkan)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action        = $_POST['action'];
    $transactionId = $_POST['transaction_id'];
    $newStatus     = ($action === 'bayar') ? 'lunas' : 'gagal';

    // Kalau batalkan, kembalikan stock
    if ($action === 'batal') {
        $queryItems = "SELECT * FROM transaction_product WHERE transaction_id = '$transactionId'";
        $itemResult = mysqli_query($conn, $queryItems);
        while ($item = mysqli_fetch_assoc($itemResult)) {
            $queryRestoreStock = "UPDATE product SET stock = stock + {$item['total_product']} WHERE id = '{$item['product_id']}'";
            mysqli_query($conn, $queryRestoreStock);
        }
    }

    $queryUpdate = "UPDATE transaction SET status = '$newStatus' WHERE id = '$transactionId'";
    mysqli_query($conn, $queryUpdate);

    header("Location: transaction_status.php?id=$transactionId");
    exit;
}

// Ambil data transaksi + info user
$queryTrx = "SELECT t.*, u.name, u.email, u.phone, u.address
              FROM transaction t
              JOIN user u ON t.user_id = u.id
              WHERE t.id = '$id'";
$result      = mysqli_query($conn, $queryTrx);
$transaction = mysqli_fetch_assoc($result);

// Ambil detail produk
$items = [];
if ($transaction) {
    $queryItems = "SELECT tp.*, p.nama, p.image
                   FROM transaction_product tp
                   JOIN product p ON tp.product_id = p.id
                   WHERE tp.transaction_id = '$id'";
    $itemResult = mysqli_query($conn, $queryItems);
    while ($row = mysqli_fetch_assoc($itemResult)) {
        $items[] = $row;
    }
}

$statusColor = [
    'menunggu pembayaran' => 'warning',
    'lunas'               => 'success',
    'gagal'               => 'danger'
];
$statusIcon = [
    'menunggu pembayaran' => 'clock',
    'lunas'               => 'check-circle-fill',
    'gagal'               => 'x-circle-fill'
];
?>

<div class="container py-5">
    <?php if ($transaction): ?>

        <div class="text-center mb-5">
            <?php $status = $transaction['status']; ?>
            <i class="bi bi-<?= $statusIcon[$status] ?> text-<?= $statusColor[$status] ?>" style="font-size:4rem;"></i>
            <h2 class="fw-bold mt-3">
                <?php if ($status === 'menunggu pembayaran'): ?>Pesanan Menunggu Pembayaran
                <?php elseif ($status === 'lunas'): ?>Pembayaran Berhasil! 🎉
                <?php else: ?>Transaksi Dibatalkan
                <?php endif; ?>
            </h2>
            <p class="text-muted">Nomor transaksi: <strong>#<?= $transaction['id'] ?></strong></p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-7">

                <!-- Info transaksi -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Detail Transaksi
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold" width="40%">Nama</td>
                                <td><?= htmlspecialchars($transaction['name']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email</td>
                                <td><?= htmlspecialchars($transaction['email']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">No. HP</td>
                                <td><?= htmlspecialchars($transaction['phone']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Alamat</td>
                                <td><?= htmlspecialchars($transaction['address']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status</td>
                                <td>
                                    <span class="badge bg-<?= $statusColor[$status] ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal</td>
                                <td><?= $transaction['created_at'] ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Total Bayar</td>
                                <td class="fw-bold text-success fs-5">
                                    Rp <?= number_format($transaction['total'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Produk yang dibeli -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-box-seam me-2"></i>Produk yang Dipesan
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nama']) ?></td>
                                    <td class="text-center"><?= $item['total_product'] ?></td>
                                    <td class="text-center">Rp <?= number_format($item['total_price'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tombol Bayar / Batalkan (hanya muncul kalau status masih menunggu) -->
                <?php if ($status === 'menunggu pembayaran'): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Konfirmasi Pembayaran</h6>
                        <div class="d-flex gap-3">
                            <!-- Tombol Bayar -->
                            <form action="transaction_status.php?id=<?= $id ?>" method="POST" class="flex-grow-1">
                                <input type="hidden" name="action"         value="bayar">
                                <input type="hidden" name="transaction_id" value="<?= $id ?>">
                                <button type="submit" class="btn btn-success w-100 fw-bold"
                                        onclick="return confirm('Konfirmasi pembayaran sebesar Rp <?= number_format($transaction['total'], 0, ',', '.') ?>?')">
                                    <i class="bi bi-check-circle me-1"></i>Bayar Sekarang
                                </button>
                            </form>

                            <!-- Tombol Batalkan -->
                            <form action="transaction_status.php?id=<?= $id ?>" method="POST" class="flex-grow-1">
                                <input type="hidden" name="action"         value="batal">
                                <input type="hidden" name="transaction_id" value="<?= $id ?>">
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold"
                                        onclick="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                    <i class="bi bi-x-circle me-1"></i>Batalkan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="text-center">
                    <a href="home.php" class="btn btn-primary">
                        <i class="bi bi-shop me-1"></i>Kembali Belanja
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-exclamation-circle text-warning" style="font-size:4rem;"></i>
            <h4 class="mt-3">Transaksi tidak ditemukan</h4>
            <a href="home.php" class="btn btn-primary mt-3">Kembali ke Home</a>
        </div>
    <?php endif; ?>
</div>

<footer><p class="mb-0">&copy; <?= date('Y') ?> TokoKu — Bootcamp Eduwork Sesi 9</p></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div></body></html>
