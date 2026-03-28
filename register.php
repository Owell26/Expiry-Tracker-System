<?php include 'includes/header.php'; ?>

<div class="container d-flex flex-column justify-content-center flex-grow-1 py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-6">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <div class="col-12">
                        <div class="card-body p-5">
                            <div class="text-center mb-5">
                                <div class="bg-info bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4 text-info">
                                    <i class="bi bi-person-plus-fill fs-1"></i>
                                </div>
                                <h2 class="fw-bold text-dark">Create Account</h2>
                                <p class="text-muted">Join ExpiryGuard to start tracking your inventory</p>
                            </div>
                            
                            <form action="controller/AuthController.php" method="POST">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-secondary">First Name</label>
                                        <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                            <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-person"></i></span>
                                            <input type="text" name="first_name" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-secondary">Middle Name</label>
                                        <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                            <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-person"></i></span>
                                            <input type="text" name="middle_name" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-secondary">Last Name</label>
                                        <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                            <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-person"></i></span>
                                            <input type="text" name="last_name" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-uppercase text-secondary">Email Address</label>
                                    <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                        <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-uppercase text-secondary">Username</label>
                                    <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                        <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-at"></i></span>
                                        <input type="text" name="username" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-secondary">Password</label>
                                        <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                            <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-lock"></i></span>
                                            <input type="password" name="password" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-secondary">Confirm Password</label>
                                        <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-light border-0">
                                            <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-shield-lock"></i></span>
                                            <input type="password" name="confirm_password" class="form-control bg-transparent border-0 shadow-none ps-0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label small text-secondary" for="terms">
                                            I agree to the <a href="#" class="text-info text-decoration-none fw-semibold">Terms of Service</a> and <a href="#" class="text-info text-decoration-none fw-semibold">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" name="register" class="btn btn-dark w-100 py-3 fw-bold shadow-sm mb-4">
                                    Create Account <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </form>
                            
                            <div class="text-center text-secondary small">
                                Already have an account? <a href="login.php" class="text-info text-decoration-none fw-semibold">Sign in here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5 text-secondary">
                <p class="small">Join over <strong>5,000+</strong> users tracking their freshness daily.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
