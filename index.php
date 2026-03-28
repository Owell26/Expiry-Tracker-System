<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not authenticated
if (!isset($_SESSION['auth'])) {
    $_SESSION['alert'] = [
        'type' => 'warning',
        'title' => 'Access Denied',
        'message' => 'Please login to access the dashboard.'
    ];
    header('Location: login.php');
    exit();
}

include 'includes/header.php';
?>

<div class="container py-5">
    <!-- Welcome Header -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 col-7">
            <h1 class="display-5 fw-bold text-dark mb-2">Track Your <span class="text-info">Freshness</span></h1>
            <p class="lead text-secondary mb-0">Monitor expiry dates and reduce waste efficiently.</p>
        </div>
        <div class="col-lg-6 col-5 text-end">
            <button class="btn btn-info px-4 py-2 fw-semibold d-lg-none shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg me-2"></i>Add
            </button>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <!-- Stats Cards -->
        <div class="col-md">
            <a href="product.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-3 border-start border-4 border-primary text-center">
                        <i class="bi bi-box2 text-primary fs-4 mb-2 d-block"></i>
                        <h6 class="text-secondary fw-normal small mb-1">Total</h6>
                        <h4 class="mb-0 fw-bold">
                            <?php
                            include 'config/dbconn.php';
                            $user_id = $_SESSION['auth_user']['user_id'];
                            $total_sql = "SELECT product_id FROM products WHERE user_id = '$user_id'";
                            $total_res = mysqli_query($conn, $total_sql);
                            echo mysqli_num_rows($total_res);
                            ?>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md">
            <a href="product.php?filter=Good" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-3 border-start border-4 border-success text-center">
                        <i class="bi bi-check-circle text-success fs-4 mb-2 d-block"></i>
                        <h6 class="text-secondary fw-normal small mb-1">Good</h6>
                        <h4 class="mb-0 fw-bold">
                            <?php
                            $good_sql = "SELECT product_id FROM products WHERE user_id = '$user_id' AND expiry_date > DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
                            $good_res = mysqli_query($conn, $good_sql);
                            echo mysqli_num_rows($good_res);
                            ?>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md">
            <a href="product.php?filter=Expiring Soon" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-3 border-start border-4 border-warning text-center">
                        <i class="bi bi-exclamation-triangle text-warning fs-4 mb-2 d-block"></i>
                        <h6 class="text-secondary fw-normal small mb-1">Soon</h6>
                        <h4 class="mb-0 fw-bold">
                            <?php
                            $soon_sql = "SELECT product_id FROM products WHERE user_id = '$user_id' AND expiry_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
                            $soon_res = mysqli_query($conn, $soon_sql);
                            echo mysqli_num_rows($soon_res);
                            ?>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md">
            <a href="product.php?filter=Today" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-3 border-start border-4 border-info text-center">
                        <i class="bi bi-clock-history text-info fs-4 mb-2 d-block"></i>
                        <h6 class="text-secondary fw-normal small mb-1">Today</h6>
                        <h4 class="mb-0 fw-bold">
                            <?php
                            $today_sql = "SELECT product_id FROM products WHERE user_id = '$user_id' AND expiry_date = CURDATE()";
                            $today_res = mysqli_query($conn, $today_sql);
                            echo mysqli_num_rows($today_res);
                            ?>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md">
            <a href="product.php?filter=Expired" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-3 border-start border-4 border-danger text-center">
                        <i class="bi bi-x-circle text-danger fs-4 mb-2 d-block"></i>
                        <h6 class="text-secondary fw-normal small mb-1">Expired</h6>
                        <h4 class="mb-0 fw-bold">
                            <?php
                            $expired_sql = "SELECT product_id FROM products WHERE user_id = '$user_id' AND expiry_date < CURDATE()";
                            $expired_res = mysqli_query($conn, $expired_sql);
                            echo mysqli_num_rows($expired_res);
                            ?>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Product Entry Form (Desktop Only) -->
        <div class="col-xl-3 d-none d-lg-block">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-plus-circle-dotted me-2 text-info"></i>Add Product
                    </h5>
                    <form class="needs-validation" action="controller/ProductController.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase">Product Name</label>
                            <input type="text" name="product_name" class="form-control form-control-lg bg-light border-0 shadow-none px-3" placeholder="Enter product name">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control form-control-lg bg-light border-0 shadow-none px-3">
                        </div>
                        <button type="submit" name="add_product_btn" class="btn btn-dark w-100 py-3 fw-bold shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i>Add to List
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <div class="col-xl-9 col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Recent Inventories</h5>
                </div>
                <div class="table-responsive">
                    <table id="recentTable" class="table table-hover align-middle mb-0 p-3">
                        <thead class="bg-light border-0">
                            <tr>
                                <th class="ps-4 border-0 py-3 text-secondary small text-uppercase">Product</th>
                                <th class="border-0 py-3 text-secondary small text-uppercase text-center">Expiry</th>
                                <th class="border-0 py-3 text-secondary small text-uppercase text-center">Remaining</th>
                                <th class="border-0 py-3 text-secondary small text-uppercase text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            <?php
                            $recent_sql = "SELECT * FROM products WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 5";
                            $recent_res = mysqli_query($conn, $recent_sql);
                            
                            if (!function_exists('getStatusInfo')) {
                                function getStatusInfo($expiry_date) {
                                    $today = new DateTime('today');
                                    $expiry = new DateTime($expiry_date);
                                    $interval = $today->diff($expiry);
                                    $days = (int)$interval->format("%r%a");
                                    
                                    if ($days < 0) return ['status' => 'Expired', 'class' => 'bg-danger-subtle text-danger', 'days' => $days];
                                    if ($days == 0) return ['status' => 'Today', 'class' => 'bg-danger-subtle text-danger fw-bold', 'days' => $days];
                                    if ($days <= 7) return ['status' => 'Soon', 'class' => 'bg-warning-subtle text-warning text-dark', 'days' => $days];
                                    return ['status' => 'Good', 'class' => 'bg-success-subtle text-success', 'days' => $days];
                                }
                            }

                            if (mysqli_num_rows($recent_res) > 0) {
                                while ($row = mysqli_fetch_assoc($recent_res)) {
                                    $status = getStatusInfo($row['expiry_date']);
                                    ?>
                                    <tr class="border-0">
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold"><?= htmlspecialchars($row['product_name']) ?></div>
                                        </td>
                                        <td class="text-center small"><?= date('M d, Y', strtotime($row['expiry_date'])) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-light <?= $status['days'] < 0 ? 'text-danger' : ($status['days'] <= 7 ? 'text-warning' : 'text-success') ?> fw-bold small">
                                                <?= $status['days'] ?> Days
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge rounded-pill <?= $status['class'] ?> px-2 py-1 small">
                                                <?= $status['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready( function () {
    $('#recentTable').DataTable({
        "order": [[ 1, "asc" ]], 
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50],
        "language": {
            "searchPlaceholder": "Search products...",
            "search": ""
        },
        "dom": "<'row mb-3 px-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row mt-3 px-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    });
    
    // Styling for DataTables elements
    $('.dataTables_filter input').addClass('form-control form-control-sm border-0 bg-light shadow-none ps-3 px-3');
    $('.dataTables_length select').addClass('form-select form-select-sm border-0 bg-light shadow-none px-3');
});
</script>
