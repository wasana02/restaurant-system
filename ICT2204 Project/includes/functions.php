<?php
// ============================================================
// includes/functions.php — Reusable Helper Functions
// ============================================================

/**
 * Clean user input — prevents XSS attacks
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Redirect to another page
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Protect a page — redirect to login if not logged in
 * Use this at the top of dashboard.php
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect('auth/login.php');
    }
}

/**
 * If already logged in, skip login/register pages
 * Use this at the top of login.php and register.php
 */
function redirectIfLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        redirect('../dashboard.php');
    }
}

/**
 * Show a Bootstrap alert box
 */
function showAlert($message, $type = 'danger') {
    return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}
?>
