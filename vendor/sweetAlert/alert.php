<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    $type = $alert['type'] ?? 'info';
    $title = $alert['title'] ?? '';
    $message = $alert['message'] ?? '';
    $redirect = $alert['redirect'] ?? null;
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: '<?= $type ?>',
            title: '<?= $title ?>',
            text: '<?= $message ?>',
            confirmButtonColor: '#0dcaf0',
            confirmButtonText: 'OK'
        }).then((result) => {
            <?php if ($redirect) { ?>
            if (result.isConfirmed) {
                window.location.href = '<?= $redirect ?>';
            }
            <?php } ?>
        });
    </script>
<?php
    unset($_SESSION['alert']);
}
?>