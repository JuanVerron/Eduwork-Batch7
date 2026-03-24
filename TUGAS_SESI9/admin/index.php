<?php
$pageTitle = "Admin - Kelola Produk";
include 'auth.php';
include '../components/template.php';
include '../components/navbar.php';
include '../koneksi.php';

$query  = "SELECT * FROM product";
$result = mysqli_query($conn, $query);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>Kelola Produk</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Produk
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <?php
        $messages = ['create' => 'Produk berhasil ditambahkan!', 'update' => 'Produk berhasil diperbarui!', 'delete' => 'Produk berhasil dihapus!'];
        $msg = $messages[$_GET['success']] ?? 'Operasi berhasil!';
        ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i><?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="tabelProduk" class="table table-hover align-middle" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="../image/<?= htmlspecialchars($row['image']) ?>"
                                     style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                            <?php else: ?>
                                <div class="bg-secondary d-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;border-radius:6px;">
                                    <i class="bi bi-image text-white"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nama']) ?></td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($row['category']) ?></span></td>
                        <td data-order="<?= (int)$row['price'] ?>">
                            Rp <?= number_format($row['price'], 0, ',', '.') ?>
                        </td>
                        <td data-order="<?= (int)$row['stock'] ?>">
                            <?php if ($row['stock'] > 10): ?>
                                <span class="badge bg-success"><?= $row['stock'] ?></span>
                            <?php elseif ($row['stock'] > 0): ?>
                                <span class="badge bg-warning text-dark"><?= $row['stock'] ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">Habis</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="delete.php?id=<?= $row['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer><p class="mb-0">&copy; <?= date('Y') ?> TokoKu — Bootcamp Eduwork Sesi 9</p></footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        const table = $('#tabelProduk').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            columnDefs: [
                { orderable: false, searchable: false, targets: [0, 2, 7] },
                { type: 'num',  targets: [1, 5, 6] },
                { orderable: true, targets: [1, 3, 4, 5, 6] }
            ],
            order: [[5, 'asc']]
        });

        table.on('order.dt search.dt draw.dt', function() {
            table.column(0, { search: 'applied', order: 'applied', page: 'current' }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>
</div></body></html>
