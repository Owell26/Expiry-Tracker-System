<?php include 'includes/header.php'; ?>

<div class="container d-flex flex-column justify-content-center flex-grow-1 py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-5">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <div class="col-12">
                        <div class="card-body p-5">
                            <div class="text-center mb-5">
                                <div class="bg-info bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4 text-info">
                                    <i class="bi bi-shield-lock-fill fs-1"></i>
                                </div>
                                <h2 class="fw-bold text-dark">Welcome Back</h2>
                                <p class="text-muted">Sign in to manage your inventory alerts</p>
                            </div>
                            
                            <form action="controller/AuthController.php" method="POST">
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-uppercase text-secondary">Username Or Email</label>
                                    <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                        <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-person"></i></span>
                                        <input type="text" name="username" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-uppercase text-secondary">Password</label>
                                    <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                        <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label small" for="rememberMe">Remember me</label>
                                    </div>
                                    <a href="#" class="text-info text-decoration-none small fw-semibold">Forgot password?</a>
                                </div>
                                <button type="submit" name="login_btn" class="btn btn-dark w-100 py-3 fw-bold shadow-sm mb-4">
                                    Log In <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </form>
                            
                            <div class="text-center text-secondary small">
                                Don't have an account? <a href="register.php" class="text-info text-decoration-none fw-semibold">Register now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5 text-secondary">
                <p class="small">Secure login powered by <strong>ExpiryGuard SSL</strong></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
