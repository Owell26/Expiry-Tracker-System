<?php include 'includes/header.php'; ?>

<div class="container d-flex flex-column justify-content-center flex-grow-1 py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-5">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <div class="col-12">
                        <div class="card-body p-5 text-center">
                            <div class="mb-5">
                                <div class="bg-info bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4 text-info">
                                    <i class="bi bi-shield-lock-fill fs-1"></i>
                                </div>
                                <h2 class="fw-bold text-dark">Verify Your Account</h2>
                                <p class="text-muted">Please enter the 6-digit verification code sent to your email.</p>
                            </div>
                            
                            <form action="controller/AuthController.php" method="POST">
                                <div class="mb-4">
                                    <input type="text" name="verification_code" class="form-control form-control-lg text-center fw-bold fs-3 bg-light shadow-sm border border-2 py-3" maxlength="6" required>
                                </div>
                                
                                <button type="submit" name="verify_now" class="btn btn-dark btn-lg w-100 py-3 fw-bold shadow-sm mb-4">
                                    Verify Account <i class="bi bi-patch-check-fill ms-2"></i>
                                </button>
                            </form>
                            
                            <div class="text-center text-secondary small">
                                Didn't receive the code? <a href="#" class="text-info text-decoration-none fw-semibold">Resend Code</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function moveNext(input) {
    if (input.value.length === 1) {
        let next = input.nextElementSibling;
        if (next) next.focus();
    }
}
</script>

<?php include 'includes/footer.php'; ?>
