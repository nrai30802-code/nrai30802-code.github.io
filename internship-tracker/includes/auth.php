<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if Student is logged in
 */
function isStudentLoggedIn() {
    return isset($_SESSION['student_id']);
}

/**
 * Check if Admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

/**
 * Require Student session, otherwise redirect to Login
 */
function requireStudent() {
    if (!isStudentLoggedIn()) {
        $_SESSION['error_flash'] = "Please log in as a student to access this page.";
        header("Location: login.php");
        exit;
    }
}

/**
 * Require Admin session, otherwise redirect to Admin Login
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        $_SESSION['error_flash'] = "Please log in as an administrator to access this page.";
        header("Location: admin-login.php");
        exit;
    }
}

/**
 * Redirect if already logged in
 */
function redirectIfLoggedIn() {
    if (isStudentLoggedIn()) {
        header("Location: dashboard.php");
        exit;
    }
    if (isAdminLoggedIn()) {
        header("Location: admin-dashboard.php");
        exit;
    }
}

/**
 * Get flash message and clear it from session
 */
function getFlashMessage($type) {
    $key = $type . '_flash';
    if (isset($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}

/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION[$type . '_flash'] = $message;
}
?>
