<?php
// A central place for all authentication and permission logic.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks if any user is logged in.
 * @return bool
 */
function is_user_logged_in() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

/**
 * Checks if the logged-in user is an admin.
 * @return bool
 */
function is_user_admin() {
    return is_user_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] == 1;
}

/**
 * Gatekeeper for admin-only pages. If the user is not an admin, it stops execution.
 */
function require_admin() {
    if (!is_user_admin()) {
        // You can redirect to an access denied page or the main login
        header('Location: /admin/index.php?error=denied');
        exit;
    }
}