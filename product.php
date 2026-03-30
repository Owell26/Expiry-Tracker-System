<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['auth'])) {
    $_SESSION['alert'] = [
        'type' => 'warning',
        'title' => 'Access Denied',
        'message' => 'Please login to access this page.'
    ];
    header("Location: login.php");
    exit();
}

include 'includes/header.php'; ?>
<?php include 'config/dbconn.php'; ?>
<?php include 'models/ProductModel.php'; ?>

<?php
function calculateExpiry($expiry_date) {
    $today = new DateTime('today'); // Use today's date at 00:00:00
    $expiry = new DateTime($expiry_date);
    $interval = $today->diff($expiry);
    $days = (int)$interval->format("%r%a");
    
    if ($days < 0) {
        return ['status' => 'Expired', 'class' => 'bg-danger-subtle text-danger', 'days' => $days];
    } elseif ($days == 0) {
        return ['status' => 'Expires Today', 'class' => 'bg-danger-subtle text-danger fw-bold', 'days' => $days];
    } elseif ($days <= 7) {
        return ['status' => 'Expiring Soon', 'class' => 'bg-warning-subtle text-warning text-dark', 'days' => $days];
    } else {
        return ['status' => 'Good', 'class' => 'bg-success-subtle text-success', 'days' => $days];
    }
}
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Welcome Header -->
        <div class="row align-items-center mb-4">
            <div class="col-lg-6 col-7">
                <h1 class="display-5 fw-bold text-dark mb-2">Add Your <span class="text-info">Product</span></h1>
                <p class="lead text-secondary mb-0">Enter product details to track expiry dates.</p>
            </div>
            <div class="col-lg-6 col-5 text-end">
                <button class="btn btn-info px-4 py-2 fw-semibold d-lg-none shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-lg me-2"></i>Add
                </button>
            </div>
        </div>

        <!-- Add Product Form (Desktop Only) -->
        <div class="col-md-3 d-none d-lg-block">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-plus-circle-dotted me-2 text-info"></i>Add New Product
                    </h5>
                    <form action="controller/ProductController.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase">Product Name</label>
                            <input type="text" name="product_name" class="form-control form-control-lg bg-light border-0 shadow-none px-3" placeholder="Enter product name" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control form-control-lg bg-light border-0 shadow-none px-3" required>
                        </div>
                        <button type="submit" name="add_product_btn" class="btn btn-dark w-100 py-3 fw-bold shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i>Add to List
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <div class="col-lg-9 col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">Product Inventory</h5>
                    </div>
                    
                    <!-- Status Filter Tabs -->
                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-3 small mb-2" id="statusFilter">
                        <li class="nav-item">
                            <button class="nav-link active rounded-2 py-2 fw-semibold border-0" data-filter="">
                                <i class="bi bi-grid-fill me-1"></i> All
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-2 py-2 fw-semibold border-0" data-filter="Good">
                                <i class="bi bi-check-circle-fill me-1 text-success"></i> Good
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-2 py-2 fw-semibold border-0" data-filter="Expiring Soon">
                                <i class="bi bi-exclamation-circle-fill me-1 text-warning"></i> Soon
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-2 py-2 fw-semibold border-0" data-filter="Today">
                                <i class="bi bi-clock-fill me-1 text-danger"></i> Today
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-2 py-2 fw-semibold border-0" data-filter="Expired">
                                <i class="bi bi-x-circle-fill me-1 text-danger"></i> Expired
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="table-responsive">
                    <table id="myTable" class="table table-hover align-middle mb-0 p-3">
                        <thead class="bg-light border-0">
                            <tr>
                                <th class="ps-4 border-0 py-3 text-secondary small text-uppercase">Product Name</th>
                                <th class="border-0 py-3 text-secondary small text-uppercase">Expiry Date</th>
                                <th class="border-0 py-3 text-secondary small text-uppercase text-center">Remaining</th>
                                <th class="border-0 py-3 text-secondary small text-uppercase text-center">Status</th>
                                <th class="border-0 py-3 text-secondary small text-uppercase text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            <?php
                            $products = getAllProducts();
                            if (mysqli_num_rows($products) > 0) {
                                while ($row = mysqli_fetch_assoc($products)) {
                                    $expiryInfo = calculateExpiry($row['expiry_date']);
                                    ?>
                                    <tr class="border-0">
                                        <td class="ps-4 py-4">
                                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($row['product_name']) ?></h6>
                                        </td>
                                        <td><span class="fw-semibold"><?= date('M d, Y', strtotime($row['expiry_date'])) ?></span></td>
                                        <td class="text-center">
                                            <span class="badge bg-light <?= $expiryInfo['days'] < 0 ? 'text-danger' : ($expiryInfo['days'] <= 7 ? 'text-warning' : 'text-success') ?> fw-bold p-2 px-3">
                                                <?= $expiryInfo['days'] ?> Days
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill <?= $expiryInfo['class'] ?> px-3 py-2">
                                                <?= $expiryInfo['status'] ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <form action="controller/ProductController.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this?')">
                                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                                <button type="submit" name="delete_product_btn" class="btn btn-light btn-sm rounded-circle p-2 text-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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
    var table = $('#myTable').DataTable({
        "order": [[ 1, "asc" ]], // Sort by Expiry Date by default
        "pageLength": 10,
        "language": {
            "searchPlaceholder": "Search products...",
            "search": ""
        },
        "dom": "<'row mb-3 px-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row mt-3 px-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    });

    // Check for filter in URL and apply it after a small delay to ensure DataTable is ready
    const urlParams = new URLSearchParams(window.location.search);
    const filterParam = urlParams.get('filter');
    if (filterParam) {
        setTimeout(function() {
            $('#statusFilter button[data-filter="' + filterParam + '"]').trigger('click');
        }, 100);
    }
    
    // Status Filter Tabs Logic
    $('#statusFilter button').on('click', function() {
        var filterValue = $(this).attr('data-filter');
        
        // Update UI
        $('#statusFilter button').removeClass('active');
        $(this).addClass('active');
        
        // Filter table (column index 3 is Status)
        if(filterValue === "Today") {
            // Special case for "Expires Today"
            table.column(3).search("Expires Today").draw();
        } else {
            table.column(3).search(filterValue).draw();
        }
    });
    
    // Custom styling for DataTables elements to match the theme
    $('.dataTables_filter input').addClass('form-control form-control-sm border-0 bg-light shadow-none ps-3 px-3');
    $('.dataTables_length select').addClass('form-select form-select-sm border-0 bg-light shadow-none px-3');
});
</script>
