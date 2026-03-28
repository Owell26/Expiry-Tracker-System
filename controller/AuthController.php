<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/dbconn.php';
include '../models/UserModel.php';

if (isset($_POST['register'])) {
    $register_data = $_POST;
    
    if ($register_data['password'] !== $register_data['confirm_password']) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Oops!',
            'message' => 'Password does not match'
        ];
        header("Location: ../register.php");
        exit();
    }

    $email = mysqli_real_escape_string($conn, $register_data['email']);
    $username = mysqli_real_escape_string($conn, $register_data['username']);
    
    // Check if email or username already exists
    $sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username' LIMIT 1";
    $sql_run = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($sql_run) > 0) {
        $row = mysqli_fetch_assoc($sql_run);
        $error_msg = ($row['email'] == $email) ? 'Email already exists' : 'Username already exists';
        
        $_SESSION['alert'] = [
            'type' => 'warning',
            'title' => 'Wait!',
            'message' => $error_msg
        ];
        header("Location: ../register.php");
        exit();
    }
    
    saveUser($register_data); 
}


if (isset($_POST['verify_now'])) {
    $input_code = mysqli_real_escape_string($conn, $_POST['verification_code']);

    verifyUser($input_code);
}

if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    loginUser($username, $password);
}

if (isset($_POST['update_profile_btn'])) {
    $user_id = $_SESSION['auth_user']['user_id'];
    $profile_data = $_POST;

    // Password matching logic if user wants to change password
    if (!empty($profile_data['new_password'])) {
        if ($profile_data['new_password'] !== $profile_data['confirm_password']) {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Oops!',
                'message' => 'New passwords do not match'
            ];
            header("Location: ../profile.php");
            exit();
        }
    }

    $update_run = updateUserProfile($user_id, $profile_data);

    if ($update_run) {
        // Update session data
        $_SESSION['auth_user']['first_name'] = $profile_data['first_name'];
        $_SESSION['auth_user']['last_name'] = $profile_data['last_name'];

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Profile Updated',
            'message' => 'Your information has been updated successfully.'
        ];
        header("Location: ../profile.php");
        exit();
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Something went wrong while updating your profile.'
        ];
        header("Location: ../profile.php");
        exit();
    }
}

if (isset($_POST['logout_btn'])) {
    session_destroy();
    session_start();
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Logged Out',
        'message' => 'You have been successfully logged out.'
    ];
    header("Location: ../login.php");
    exit();
}
?>