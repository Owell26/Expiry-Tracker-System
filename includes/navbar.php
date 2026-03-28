<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center text-uppercase tracking-wider" href="index.php">
            <i class="bi bi-shield-check-fill text-info fs-3 me-2"></i>
            <span>Expiry<span class="text-info me-2">Guard</span></span> Powered By
            <span class="text-info ms-2">OVK</span>
        </a>
        <button class="navbar-toggler border-0 d-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-none d-lg-block" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item">
                    <a class="nav-link active px-3 rounded-pill" href="index.php">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="product.php">
                        <i class="bi bi-box-seam me-1"></i> Products
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link px-3 dropdown-toggle hide-arrow" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell me-1"></i> Alerts
                        <?php 
                        if (isset($_SESSION['auth_user'])) {
                            $user_id = $_SESSION['auth_user']['user_id'];
                            include 'config/dbconn.php';
                            $unread_sql = "SELECT notification_id FROM notifications WHERE user_id = '$user_id' AND status = 0";
                            $unread_res = mysqli_query($conn, $unread_sql);
                            $unread_count = mysqli_num_rows($unread_res);
                            if ($unread_count > 0) {
                                echo '<span class="badge rounded-pill bg-danger" style="font-size: 0.6rem;">' . $unread_count . '</span>';
                            }
                        }
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-2 py-0 overflow-hidden" aria-labelledby="notifDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                        <li class="bg-light p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <a href="#" class="text-info small text-decoration-none">Mark all as read</a>
                            </div>
                        </li>
                        <?php 
                        if (isset($_SESSION['auth_user'])) {
                            $notif_sql = "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 10";
                            $notif_res = mysqli_query($conn, $notif_sql);
                            
                            if (mysqli_num_rows($notif_res) > 0) {
                                while ($notif = mysqli_fetch_assoc($notif_res)) {
                                    $is_unread = $notif['status'] == 0;
                                    ?>
                                    <li>
                                        <a class="dropdown-item p-3 border-bottom <?= $is_unread ? 'bg-light' : '' ?>" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="bi <?= strpos($notif['title'], 'Expired') !== false ? 'bi-x-circle text-danger' : 'bi-exclamation-circle text-warning' ?> fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="mb-0 small fw-bold text-dark"><?= htmlspecialchars($notif['title']) ?></p>
                                                        <span class="text-muted" style="font-size: 0.7rem;"><?= date('M d', strtotime($notif['created_at'])) ?></span>
                                                    </div>
                                                    <p class="mb-0 small text-secondary text-truncate" style="max-width: 200px;"><?= htmlspecialchars($notif['message']) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <?php
                                }
                            } else {
                                echo '<li class="p-4 text-center text-muted small">No notifications yet.</li>';
                            }
                        } else {
                            echo '<li class="p-4 text-center text-muted small">Please login to see alerts.</li>';
                        }
                        ?>
                        <li>
                            <a class="dropdown-item text-center py-2 bg-light small fw-semibold text-secondary" href="#">View All Alerts</a>
                        </li>
                    </ul>
                </li>
                <div class="vr d-none d-lg-block mx-2 text-secondary"></div>
                <?php if (isset($_SESSION['auth'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['auth_user']['first_name'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li>
                                <a class="dropdown-item py-2" href="profile.php">
                                    <i class="bi bi-person-badge me-2 text-info"></i> My Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider opacity-10"></li>
                            <li>
                                <form action="controller/AuthController.php" method="POST">
                                    <button type="submit" name="logout_btn" class="dropdown-item py-2">
                                        <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-info rounded-pill px-4 fw-semibold" href="login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation Bar -->
<div class="mobile-bottom-nav d-lg-none bg-dark border-top border-secondary border-opacity-25 fixed-bottom py-2 shadow-lg">
    <div class="container">
        <div class="row g-0 text-center align-items-center">
            <div class="col">
                <a href="index.php" class="nav-link text-secondary <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-info active' : '' ?>">
                    <i class="bi bi-speedometer2 fs-4"></i>
                    <small class="d-block" style="font-size: 0.65rem;">Home</small>
                </a>
            </div>
            <div class="col">
                <a href="product.php" class="nav-link text-secondary <?= basename($_SERVER['PHP_SELF']) == 'product.php' ? 'text-info active' : '' ?>">
                    <i class="bi bi-box-seam fs-4"></i>
                    <small class="d-block" style="font-size: 0.65rem;">Products</small>
                </a>
            </div>
            <div class="col">
                <div class="dropup">
                    <a href="#" class="nav-link text-secondary" id="mobileNotifDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="position-relative d-inline-block">
                            <i class="bi bi-bell fs-4"></i>
                            <?php if (isset($unread_count) && $unread_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-dark" style="font-size: 0.5rem; padding: 0.25em 0.4em;">
                                    <?= $unread_count ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <small class="d-block" style="font-size: 0.65rem;">Alerts</small>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-0 overflow-hidden mb-3" aria-labelledby="mobileNotifDrop" style="min-width: 280px; max-height: 350px; overflow-y: auto;">
                        <li class="bg-light p-3 border-bottom">
                            <h6 class="mb-0 fw-bold">Notifications</h6>
                        </li>
                        <?php 
                        if (isset($_SESSION['auth_user'])) {
                            mysqli_data_seek($notif_res, 0); // Reset result pointer
                            if (mysqli_num_rows($notif_res) > 0) {
                                while ($notif = mysqli_fetch_assoc($notif_res)) {
                                    $is_unread = $notif['status'] == 0;
                                    ?>
                                    <li>
                                        <a class="dropdown-item p-3 border-bottom <?= $is_unread ? 'bg-light' : '' ?>" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="bi <?= strpos($notif['title'], 'Expired') !== false ? 'bi-x-circle text-danger' : 'bi-exclamation-circle text-warning' ?> fs-5"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="mb-0 small fw-bold text-dark text-truncate" style="max-width: 180px;"><?= htmlspecialchars($notif['title']) ?></p>
                                                    <p class="mb-0 text-muted" style="font-size: 0.65rem;"><?= date('M d, H:i', strtotime($notif['created_at'])) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <?php
                                }
                            } else {
                                echo '<li class="p-4 text-center text-muted small">No notifications yet.</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col">
                <div class="dropup">
                    <a href="#" class="nav-link text-secondary <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'text-info active' : '' ?>" id="mobileProfileDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-4"></i>
                        <small class="d-block" style="font-size: 0.65rem;">Profile</small>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-0 mb-3" aria-labelledby="mobileProfileDrop">
                        <li>
                            <a class="dropdown-item py-3 border-bottom" href="profile.php">
                                <i class="bi bi-person-badge me-2 text-info"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <form action="controller/AuthController.php" method="POST">
                                <button type="submit" name="logout_btn" class="dropdown-item py-3 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mobile-bottom-nav .nav-link {
    transition: all 0.2s ease;
}
.mobile-bottom-nav .nav-link.active {
    color: #0dcaf0 !important;
}
body {
    padding-bottom: 70px !important; /* Spacing for the bottom nav */
}
@media (min-width: 992px) {
    body {
        padding-bottom: 0 !important;
    }
}
</style>

</main>

<!-- Add Product Modal (Mobile & Desktop) -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="addProductModalLabel">
                    <i class="bi bi-plus-circle-dotted me-2 text-info"></i>Add New Product
                </h5>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="controller/ProductController.php" method="POST" class="needs-validation">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase">Product Name</label>
                        <input type="text" name="product_name" class="form-control form-control-lg bg-light border-0 shadow-none px-3" placeholder="Enter product name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control form-control-lg bg-light border-0 shadow-none px-3" required>
                    </div>
                    <button type="submit" name="add_product_btn" class="btn btn-dark btn-lg w-100 py-3 fw-bold shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i>Add to List
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>