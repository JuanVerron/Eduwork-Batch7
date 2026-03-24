<?php
$pageTitle = "Edit Produk";
include 'auth.php';
include '../components/template.php';
include '../components/navbar.php';
include '../koneksi.php';

$error = '';
$id    = $_GET['id'] ?? null;

if (!$id) { header("Location: index.php"); exit; }

$query  = "SELECT * FROM product WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$produk = mysqli_fetch_assoc($result);

if (!$produk) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $price     = $_POST['price'];
    $category  = mysqli_real_escape_string($conn, $_POST['category']);
    $stock     = (int) $_POST['stock'];
    $imageName = $produk['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir  = '../image/';
        $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array(strtolower($ext), $allowedExt)) {
            $error = "Format gambar tidak didukung.";
        } else {
            if ($produk['image'] && file_exists($uploadDir . $produk['image'])) {
                unlink($uploadDir . $produk['image']);
            }
            $imageName = uniqid('produk_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        }
    }

    if (!$error) {
        $query = "UPDATE product SET
                      nama      = '$nama',
                      deskripsi = '$deskripsi',
                      price     = '$price',
                      category  = '$category',
                      stock     = '$stock',
                      image     = " . ($imageName ? "'$imageName'" : "NULL") . "
                  WHERE id = '$id'";

        if (mysqli_query($conn, $query)) {
            header("Location: index.php?success=update");
            exit;
        } else {
            $error = "Gagal memperbarui produk: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex align-items-center mb-4">
                <a href="index.php" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
                <h2 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Produk</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required
                                   value="<?= htmlspecialchars($produk['nama']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control" required value="<?= $produk['price'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" min="0" required value="<?= $produk['stock'] ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach (['Laptop','Handphone','Tablet','Audio','Aksesoris','Lainnya'] as $cat): ?>
                                    <option value="<?= $cat ?>" <?= $produk['category'] == $cat ? 'selected' : '' ?>>
                                        <?= $cat ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Gambar Produk</label>
                            <?php if ($produk['image']): ?>
                                <div class="mb-2">
                                    <img src="../image/<?= htmlspecialchars($produk['image']) ?>"
                                         style="height:100px;object-fit:cover;border-radius:8px;">
                                    <div class="form-text">Gambar saat ini. Upload baru untuk mengganti.</div>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-dark">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer><p class="mb-0">&copy; <?= date('Y') ?> TokoKu — Bootcamp Eduwork Sesi 9</p></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div></body></html>
