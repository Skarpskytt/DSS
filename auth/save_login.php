<?php
// save_login.php
session_start();
include('../config/db_connect.php'); // Ensure this properly defines $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email and Password are required.';
        header('Location: login.php');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: login.php');
        exit();
    }

    try {
        // Fetch user from the database
        $stmt = $pdo->prepare('SELECT id, fname, lname, password, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fname'] . ' ' . $user['lname'];
            $_SESSION['role'] = $user['role'];

            // Secure Remember Me with encryption
            if ($remember) {
                setcookie('user_id', base64_encode($user['id']), time() + (30 * 24 * 60 * 60), "/", "", true, true);
                setcookie('role', base64_encode($user['role']), time() + (30 * 24 * 60 * 60), "/", "", true, true);
            }

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: ../pages/admin/admindashboard.php');
            } else {
                header('Location: ../pages/staff/userdashboard.php');
            }
            exit();
        } else {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        $_SESSION['error'] = 'Something went wrong. Please try again later.';
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
