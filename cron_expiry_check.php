<?php
include 'config/dbconn.php';
include 'vendor/PHPMailer-master/MailConfig.php';

date_default_timezone_set('Asia/Manila');

$today_date = date('Y-m-d');

// Only process users who haven't been checked today
$users_sql = "SELECT * FROM users WHERE last_expiry_check IS NULL OR last_expiry_check < '$today_date' AND verification_status = 1";
$users_res = mysqli_query($conn, $users_sql);

while ($user = mysqli_fetch_assoc($users_res)) {
    $user_id = $user['user_id'];
    $user_email = $user['email'];
    $user_name = $user['first_name'];

    // 1. Check for products expiring in exactly 30 days for THIS user
    $warning_sql = "SELECT * FROM products WHERE user_id = '$user_id' AND expiry_date = DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
    $warning_res = mysqli_query($conn, $warning_sql);

    while ($product = mysqli_fetch_assoc($warning_res)) {
        $subject = "Expiry Warning: " . $product['product_name'];
        $message = "
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ffc107; border-radius: 10px;'>
                <h2 style='color: #856404;'>30-Day Expiry Notification</h2>
                <p>Hi " . htmlspecialchars($user_name) . ",</p>
                <p>Your product <strong>" . htmlspecialchars($product['product_name']) . "</strong> is set to expire in <strong>30 days</strong> (" . date('M d, Y', strtotime($product['expiry_date'])) . ").</p>
            </div>
        ";
        sendEmail($user_email, $subject, $message);

        // In-app Notification
        $notif_title = "30-Day Warning: " . $product['product_name'];
        $notif_msg = "Your product " . $product['product_name'] . " will expire on " . date('M d, Y', strtotime($product['expiry_date']));
        mysqli_query($conn, "INSERT INTO notifications (user_id, title, message) VALUES ('$user_id', '$notif_title', '$notif_msg')");
    }

    // 2. Check for products that expired TODAY for THIS user
    $expired_sql = "SELECT * FROM products WHERE user_id = '$user_id' AND expiry_date = CURDATE()";
    $expired_res = mysqli_query($conn, $expired_sql);

    while ($product = mysqli_fetch_assoc($expired_res)) {
        $subject = "Product Expired: " . $product['product_name'];
        $message = "
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #dc3545; border-radius: 10px;'>
                <h2 style='color: #721c24;'>Product Expired Today</h2>
                <p>Hi " . htmlspecialchars($user_name) . ",</p>
                <p>Your product <strong>" . htmlspecialchars($product['product_name']) . "</strong> has reached its expiry date today (<strong>" . date('M d, Y', strtotime($product['expiry_date'])) . "</strong>).</p>
            </div>
        ";
        sendEmail($user_email, $subject, $message);

        // In-app Notification
        $notif_title = "Product Expired: " . $product['product_name'];
        $notif_msg = "Your product " . $product['product_name'] . " has expired today (" . date('M d, Y', strtotime($product['expiry_date'])) . ")";
        mysqli_query($conn, "INSERT INTO notifications (user_id, title, message) VALUES ('$user_id', '$notif_title', '$notif_msg')");
    }

    // Update user's last check date
    mysqli_query($conn, "UPDATE users SET last_expiry_check = '$today_date' WHERE user_id = '$user_id'");
}

echo "Expiry check completed at " . date('Y-m-d H:i:s');
?>
