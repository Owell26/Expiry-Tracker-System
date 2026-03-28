<?php 

include '../vendor/PHPMailer-master/MailConfig.php';

function saveUser($data) {
    global $conn;

    $user_id = uniqid();
    $first_name = mysqli_real_escape_string($conn, $data['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $data['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $data['last_name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = mysqli_real_escape_string($conn, $data['password']);
    $verification_code = rand(100000, 999999);

    $sql = "INSERT INTO users (user_id, first_name, middle_name, last_name, email, username, password, verification_code) 
            VALUES ('$user_id', '$first_name', '$middle_name', '$last_name', '$email', '$username', '$password', '$verification_code')";
    $sql_run = mysqli_query($conn, $sql);

    if ($sql_run) {
        // Send Verification Email
        $subject = "Verify your ExpiryGuard Account";
        $message = "
            <h2>Welcome to ExpiryGuard!</h2>
            <p>Thank you for registering. Your verification code is:</p>
            <h1 style='color: #0dcaf0;'>$verification_code</h1>
            <p>Please enter this code on the verification page to activate your account.</p>
        ";
        
        sendEmail($email, $subject, $message);

        session_start();
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Registered!',
            'message' => 'Please check your email for the verification code.'
        ];
        header("Location: ../verify.php");
        exit();
    } else {
        session_start();
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Something went wrong.'
        ];
        header("Location: ../register.php");
        exit();
    }
}

function verifyUser($input_code) {
    global $conn;

    // Get the most recent unverified user with this code
    $sql = "SELECT * FROM users WHERE verification_code = '$input_code' AND verification_status = 0 ORDER BY created_at DESC LIMIT 1";
    $sql_run = mysqli_query($conn, $sql);

    if (mysqli_num_rows($sql_run) > 0) {
        $row = mysqli_fetch_assoc($sql_run);
        $user_id = $row['user_id'];
        
        // Update status to 1 and set code to NULL
        $sql = "UPDATE users SET verification_status = 1, verification_code = NULL WHERE user_id = '$user_id'";
        $sql_run = mysqli_query($conn, $sql);

        if ($sql_run) {
            session_start();
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Account Verified',
                'message' => 'Your account is now active! You can log in.'
            ];
            header("Location: ../login.php");
            exit();
        }else{
            session_start();
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Verification failed. Please try again.'
            ];
            header("Location: ../verify.php");
            exit();
        }
    }else{
        session_start();
        $_SESSION['alert'] = [
            'type' => 'warning',
            'title' => 'Invalid Code',
            'message' => 'The code you entered is incorrect.'
        ];
        header("Location: ../verify.php");
        exit();
    }
}

function loginUser($username, $password) {
    global $conn;

    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username' LIMIT 1";
    $sql_run = mysqli_query($conn, $sql);

    if (mysqli_num_rows($sql_run) > 0) {
        $row = mysqli_fetch_assoc($sql_run);
        
        // Check if verified
        if ($row['verification_status'] == 0) {
            session_start();
            $_SESSION['alert'] = [
                'type' => 'warning',
                'title' => 'Not Verified',
                'message' => 'Please verify your account first.'
            ];
            header("Location: ../verify.php");
            exit();
        }
        
        if ($password === $row['password']) {
            session_start();
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = [
                'user_id' => $row['user_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
            ];
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Welcome!',
                'message' => 'Logged in successfully.'
            ];
            header("Location: ../index.php");
            exit();
        } else {
            session_start();
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Login Failed',
                'message' => 'Invalid Password'
            ];
            header("Location: ../login.php");
            exit();
        }
    } else {
        session_start();
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Login Failed',
            'message' => 'Invalid Username'
        ];
        header("Location: ../login.php");
        exit();
    }
}

function updateUserProfile($user_id, $data) {
    global $conn;

    $first_name = mysqli_real_escape_string($conn, $data['first_name']);
    $last_name = mysqli_real_escape_string($conn, $data['last_name']);
    $new_password = mysqli_real_escape_string($conn, $data['new_password']);

    if (!empty($new_password)) {
        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', password = '$new_password' WHERE user_id = '$user_id'";
    } else {
        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name' WHERE user_id = '$user_id'";
    }

    $sql_run = mysqli_query($conn, $sql);
    return $sql_run;
}
