<?php
$host = 'gateway01.ap-northeast-1.prod.aws.tidbcloud.com';
$port = 4000;
$username = '1SgoSRb7Mhbqc51.root';
$password = 'lSkCuxVpJBEy4U8m';
$database = 'expiry_tracker_system';

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect($conn, $host, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully!";
?>