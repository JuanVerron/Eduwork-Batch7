<?php
$pageTitle = "Login";
if (session_status() === PHP_SESSION_NONE) session_start();
include 'koneksi.php';

// Kalau sudah login, redirect ke home
if (isset($_SESSION['user_id'])) {
    $redirectLoggedIn = ($_SESSION['user_email'] ?? '') === 'admin@admin.com' ? 'admin/index.php' : 'home.php';
    header("Location: $redirectLoggedIn");
    exit;
}

$step  = $_POST['step']  ?? 'email';   // step: email → profile → done
$error = '';
$email = $_POST['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // STEP 1: Cek email
    if ($step === 'email') {
        $email = trim(mysqli_real_escape_string($conn, $_POST['email']));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format email tidak valid.";
            $step  = 'email';
        } else {
            // Cek apakah email sudah ada di database
            $query = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $query);
            $user   = mysqli_fetch_assoc($result);

            if ($user) {
                // Email ditemukan → langsung login
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                $defaultRedirect = $user['email'] === 'admin@admin.com' ? 'admin/index.php' : 'home.php';
                $redirect = $_SESSION['redirect_after_login'] ?? $defaultRedirect;
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit;
            } else {
                // Email belum ada → lanjut ke step isi profil
                $step = 'profile';
            }
        }
    }

    // STEP 2: Isi profil (user baru)
    elseif ($step === 'profile') {
        $email   = trim(mysqli_real_escape_string($conn, $_POST['email']));
        $name    = trim(mysqli_real_escape_string($conn, $_POST['name']));
        $phone   = trim(mysqli_real_escape_string($conn, $_POST['phone']));
        $address = trim(mysqli_real_escape_string($conn, $_POST['address']));

        if (!$name || !$phone || !$address) {
            $error = "Semua field harus diisi.";
            $step  = 'profile';
        } else {
            // Insert user baru
            $query = "INSERT INTO user (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
            mysqli_query($conn, $query);
            $newId = mysqli_insert_id($conn);

            $_SESSION['user_id']    = $newId;
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;

            $redirect = $_SESSION['redirect_after_login'] ?? 'home.php';
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit;
        }
    }
}

include 'components/template.php';
include 'components/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <?php if ($step === 'email'): ?>
                        <!-- STEP 1: Input Email -->
                        <h4 class="fw-bold mb-1"><i class="bi bi-person-circle me-2 text-primary"></i>Masuk</h4>
                        <p class="text-muted small mb-4">Masukkan email kamu untuk melanjutkan</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="step" value="email">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg"
                                       placeholder="contoh@email.com" required
                                       value="<?= htmlspecialchars($email) ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                Lanjutkan <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </form>

                    <?php elseif ($step === 'profile'): ?>
                        <!-- STEP 2: Isi Profil (user baru) -->
                        <h4 class="fw-bold mb-1"><i class="bi bi-person-plus me-2 text-success"></i>Lengkapi Profil</h4>
                        <p class="text-muted small mb-4">Email <strong><?= htmlspecialchars($email) ?></strong> belum terdaftar. Lengkapi data di bawah.</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="step"  value="profile">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" required
                                       placeholder="Nama kamu" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor HP</label>
                                <input type="text" name="phone" class="form-control" required
                                       placeholder="08xxxxxxxxxx" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="address" class="form-control" rows="3" required
                                          placeholder="Alamat lengkap"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                <i class="bi bi-check-circle me-1"></i>Daftar & Masuk
                            </button>
                        </form>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<footer><p class="mb-0">&copy; <?= date('Y') ?> TokoKu</p></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div></body></html>
