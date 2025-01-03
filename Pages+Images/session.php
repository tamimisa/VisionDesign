<?php
    // Check if a session is not already active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['id']) || !isset($_SESSION['type'])) {
        // Allow access to login and signup pages
        if (basename($_SERVER['PHP_SELF']) !== 'Login.php' && basename($_SERVER['PHP_SELF']) !== 'Signup.php') {
            // Redirect to home page (login page)
            header('Location: Login.php');
            exit();
        }
    }
?>
