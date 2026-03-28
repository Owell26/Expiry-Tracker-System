<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/dbconn.php';
include '../models/ProductModel.php';

if (isset($_POST['add_product_btn'])) {
    $data = [
        'product_name' => $_POST['product_name'],
        'expiry_date' => $_POST['expiry_date']
    ];
    
    if (saveProduct($data)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Added!',
            'message' => 'Product added successfully.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Failed to add product.'
        ];
    }
    header("Location: ../product.php");
    exit();
}

if (isset($_POST['delete_product_btn'])) {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    
    if (deleteProduct($product_id)) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Deleted!',
            'message' => 'Product removed from list.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Failed to delete product.'
        ];
    }
    header("Location: ../product.php");
    exit();
}
?>
