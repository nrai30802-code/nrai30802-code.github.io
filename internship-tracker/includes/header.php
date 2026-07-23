<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

$current_page = basename($_SERVER['PHP_SELF']);

// Check for active alerts or notifications
$unread_notifications = [];
if (isStudentLoggedIn()) {
    $all_notifs = get_student_notifications($_SESSION['student_id']);
    foreach ($all_notifs as $n) {
        if (!$n['is_read']) {
            $unread_notifications[] = $n;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Internship Tracking System</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Manage, submit, and track student internship progress easily. Upload certificates, view reports, and download completions.">
    <meta name="keywords" content="Internship Tracker, Student Dashboard, Internship Progress, Certificate Approval, Database PHP">
    <meta name="author" content="Digital Internship Tracking System Team">
    
    <!-- Fonts (Outfit & Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Chart.js (Loaded only if needed, or globally for convenience) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div class="loader-spinner"></div>
    </div>

    <!-- Responsive Translucent Header -->
    <nav class="navbar navbar-expand-lg navbar-light glass-navbar sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fa-solid fa-graduation-cap text-primary fs-3 me-2"></i>
                <span class="fw-bold fs-4 text-dark display-font">DITS</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    
                    <?php if (isStudentLoggedIn()): ?>
                        <!-- Student Navigation Links -->
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= $current_page == 'dashboard.php' ? 'active fw-bold text-primary' : '' ?>" href="dashboard.php">
                                <i class="fa-solid fa-chart-line me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= $current_page == 'add-internship.php' ? 'active fw-bold text-primary' : '' ?>" href="add-internship.php">
                                <i class="fa-solid fa-plus-circle me-1"></i> Add Internship
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= $current_page == 'profile.php' ? 'active fw-bold text-primary' : '' ?>" href="profile.php">
                                <i class="fa-solid fa-user-circle me-1"></i> Profile
                            </a>
                        </li>
                        
                        <!-- Notifications Bell -->
                        <li class="nav-item dropdown px-3">
                            <a class="nav-link notification-bell" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-bell fs-5"></i>
                                <?php if (count($unread_notifications) > 0): ?>
                                    <span class="notification-badge"><?= count($unread_notifications) ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end glass-card p-2 border-0 shadow mt-2" aria-labelledby="notifDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Notifications</span>
                                    <?php if (count($unread_notifications) > 0): ?>
                                        <a href="api/read-notifications.php" class="text-xs text-primary text-decoration-none">Mark all read</a>
                                    <?php endif; ?>
                                </div>
                                <?php if (empty($all_notifs)): ?>
                                    <li class="p-3 text-center text-muted">No notifications yet.</li>
                                <?php else: ?>
                                    <?php foreach ($all_notifs as $n): ?>
                                        <li class="p-2 border-bottom-0 <?= !$n['is_read'] ? 'bg-light' : '' ?> rounded mb-1">
                                            <div class="d-flex align-items-start px-2">
                                                <i class="fa-solid fa-info-circle text-primary mt-1 me-2"></i>
                                                <div>
                                                    <p class="mb-0 text-sm" style="font-size: 0.85rem;"><?= htmlspecialchars($n['message']) ?></p>
                                                    <small class="text-muted" style="font-size: 0.7rem;"><?= date('M d, H:i', strtotime($n['created_at'])) ?></small>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <!-- Student Logout -->
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-danger btn-sm border-0" href="api/logout.php">
                                <i class="fa-solid fa-sign-out-alt"></i> Logout
                            </a>
                        </li>

                    <?php elseif (isAdminLoggedIn()): ?>
                        <!-- Admin Navigation Links -->
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= $current_page == 'admin-dashboard.php' ? 'active fw-bold text-primary' : '' ?>" href="admin-dashboard.php">
                                <i class="fa-solid fa-lock-open me-1"></i> Admin Panel
                            </a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-danger btn-sm border-0" href="api/logout.php">
                                <i class="fa-solid fa-sign-out-alt"></i> Logout Admin
                            </a>
                        </li>

                    <?php else: ?>
                        <!-- Guest Navigation Links -->
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= $current_page == 'index.php' ? 'active text-primary' : '' ?>" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= $current_page == 'about.php' ? 'active text-primary' : '' ?>" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= $current_page == 'contact.php' ? 'active text-primary' : '' ?>" href="contact.php">Contact Us</a>
                        </li>
                        <li class="nav-item dropdown px-3">
                            <a class="nav-link dropdown-toggle" href="#" id="loginSelector" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Portals
                            </a>
                            <ul class="dropdown-menu border-0 shadow glass-card mt-2" aria-labelledby="loginSelector">
                                <li><a class="dropdown-item py-2" href="login.php"><i class="fa-solid fa-user me-2 text-primary"></i> Student Portal</a></li>
                                <li><a class="dropdown-item py-2" href="admin-login.php"><i class="fa-solid fa-user-tie me-2 text-primary"></i> Admin Portal</a></li>
                            </ul>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-gradient text-white btn-sm" href="register.php">Register Now</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Global Alert Area -->
    <div class="container mt-3">
        <?php if ($err = getFlashMessage('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show glass-card border-danger border-opacity-25" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2 text-danger"></i> <?= $err ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($success = getFlashMessage('success')): ?>
            <div class="alert alert-success alert-dismissible fade show glass-card border-success border-opacity-25" role="alert">
                <i class="fa-solid fa-circle-check me-2 text-success"></i> <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
