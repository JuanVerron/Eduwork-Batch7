<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['qty'];
    }
}

$isAdmin  = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
$prefix   = $isAdmin ? '../' : '';
$loggedIn = isset($_SESSION['user_id']);
$canAccessAdmin = isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@admin.com';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="<?= $prefix ?>home.php">
            <i class="bi bi-shop me-2"></i>TokoKu
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $prefix ?>home.php">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                </li>
                <?php if ($canAccessAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $prefix ?>admin/index.php">
                            <i class="bi bi-speedometer2 me-1"></i>Admin
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <!-- Cart -->
                <a href="<?= $prefix ?>cart.php" class="btn btn-outline-light position-relative">
                    <i class="bi bi-cart3 me-1"></i>Cart
                    <?php if ($cartCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cartCount ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Login / User info -->
                <?php if ($loggedIn): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small"><?= htmlspecialchars($_SESSION['user_email']) ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= $prefix ?>logout.php">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= $prefix ?>login.php" class="btn btn-primary">
                        <i class="bi bi-person me-1"></i>Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
