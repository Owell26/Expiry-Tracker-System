<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['auth'])) {
    $_SESSION['alert'] = [
        'type' => 'warning',
        'title' => 'Access Denied',
        'message' => 'Please login to access your profile.'
    ];
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
?>

<?php
    $user_id = $_SESSION['auth_user']['user_id'];
    $query = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
    $query_run = mysqli_query($conn, $query);
    $user_data = mysqli_fetch_assoc($query_run);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-dark text-white py-4 px-4 border-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded-circle border border-info border-2">
                            <i class="bi bi-person-fill text-info fs-2"></i>
                        </div>
                        <div class="ms-4">
                            <h3 class="fw-bold mb-0">My <span class="text-info">Profile</span></h3>
                            <p class="text-secondary small mb-0">Manage your personal information</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <form action="controller/AuthController.php" method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-secondary">First Name</label>
                                <input type="text" name="first_name" value="<?= htmlspecialchars($user_data['first_name']) ?>" class="form-control form-control-lg bg-light border-0 shadow-none px-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-secondary">Last Name</label>
                                <input type="text" name="last_name" value="<?= htmlspecialchars($user_data['last_name']) ?>" class="form-control form-control-lg bg-light border-0 shadow-none px-3" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Username</label>
                            <input type="text" value="<?= htmlspecialchars($user_data['username']) ?>" class="form-control form-control-lg bg-light border-0 shadow-none px-3 text-muted" disabled>
                            <div class="form-text small">Username cannot be changed.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Email Address</label>
                            <input type="email" value="<?= htmlspecialchars($user_data['email']) ?>" class="form-control form-control-lg bg-light border-0 shadow-none px-3 text-muted" disabled>
                            <div class="form-text small">Email cannot be changed.</div>
                        </div>

                        <hr class="my-4 opacity-10">

                        <h6 class="fw-bold mb-3 text-dark">Change Password</h6>
                        <p class="text-secondary small mb-4">Leave blank if you don't want to change your password.</p>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 pe-0"><i class="bi bi-lock text-secondary"></i></span>
                                <input type="password" name="new_password" class="form-control form-control-lg bg-light border-0 shadow-none px-3" placeholder="Enter new password">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 pe-0"><i class="bi bi-shield-lock text-secondary"></i></span>
                                <input type="password" name="confirm_password" class="form-control form-control-lg bg-light border-0 shadow-none px-3" placeholder="Confirm new password">
                            </div>
                        </div>

                        <button type="submit" name="update_profile_btn" class="btn btn-dark btn-lg w-100 py-3 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
