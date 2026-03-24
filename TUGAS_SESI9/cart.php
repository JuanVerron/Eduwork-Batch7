<?php
$pageTitle = "Keranjang Belanja";
if (session_status() === PHP_SESSION_NONE) session_start();

// Harus login dulu
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'cart.php';
    header("Location: login.php");
    exit;
}

include 'components/template.php';
include 'components/navbar.php';
include 'koneksi.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2 text-primary"></i>Keranjang Belanja</h2>

    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <table class="table align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grandTotal = 0;
                                foreach ($_SESSION['cart'] as $item):
                                    $subtotal    = $item['price'] * $item['qty'];
                                    $grandTotal += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($item['image']): ?>
                                                <img src="image/<?= htmlspecialchars($item['image']) ?>"
                                                     style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                                            <?php else: ?>
                                                <div class="bg-secondary d-flex align-items-center justify-content-center"
                                                     style="width:60px;height:60px;border-radius:8px;">
                                                    <i class="bi bi-image text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                            <span class="fw-semibold"><?= htmlspecialchars($item['nama']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= $item['qty'] ?></td>
                                    <td class="text-center fw-bold text-success">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="hapus_cart.php?id=<?= $item['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Hapus produk ini dari keranjang?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="home.php" class="btn btn-outline-secondary mt-3">
                    <i class="bi bi-arrow-left me-1"></i>Lanjut Belanja
                </a>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

                        <!-- Info user yang login -->
                        <div class="alert alert-light border mb-3 small">
                            <i class="bi bi-person-check me-1 text-success"></i>
                            <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong><br>
                            <span class="text-muted"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item</span>
                            <span>
                                <?php
                                $totalItem = 0;
                                foreach ($_SESSION['cart'] as $item) $totalItem += $item['qty'];
                                echo $totalItem;
                                ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mb-4">
                            <span>Grand Total</span>
                            <span class="text-success">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
                        </div>

                        <form action="checkout.php" method="POST">
                            <input type="hidden" name="total" value="<?= $grandTotal ?>">
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                <i class="bi bi-bag-check me-1"></i>Checkout Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size:5rem;color:#dee2e6;"></i>
            <h4 class="mt-3 text-muted">Keranjang kamu masih kosong</h4>
            <a href="home.php" class="btn btn-primary mt-3">
                <i class="bi bi-shop me-1"></i>Mulai Belanja
            </a>
        </div>
    <?php endif; ?>
</div>

<footer><p class="mb-0">&copy; <?= date('Y') ?> TokoKu — Bootcamp Eduwork Sesi 9</p></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div></body></html>
