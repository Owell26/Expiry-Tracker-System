<?php

function saveProduct($data) {
    global $conn;
    
    $user_id = $_SESSION['auth_user']['user_id'];
    $product_name = mysqli_real_escape_string($conn, $data['product_name']);
    $expiry_date = mysqli_real_escape_string($conn, $data['expiry_date']);
    
    $sql = "INSERT INTO products (user_id, product_name, expiry_date) VALUES ('$user_id', '$product_name', '$expiry_date')";
    $sql_run = mysqli_query($conn, $sql);
    
    return $sql_run;
}

function getAllProducts() {
    global $conn;
    
    $user_id = $_SESSION['auth_user']['user_id'];
    $sql = "SELECT * FROM products WHERE user_id = '$user_id' ORDER BY expiry_date ASC";
    $sql_run = mysqli_query($conn, $sql);
    
    return $sql_run;
}

function deleteProduct($id) {
    global $conn;
    
    $user_id = $_SESSION['auth_user']['user_id'];
    $sql = "DELETE FROM products WHERE product_id = '$id' AND user_id = '$user_id'";
    $sql_run = mysqli_query($conn, $sql);
    
    return $sql_run;
}
?>
