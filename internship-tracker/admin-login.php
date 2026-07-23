<?php
require_once __DIR__ . '/includes/header.php';

// Redirect if already logged in
redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($email) && !empty($password)) {
        $admin = admin_login($email, $password);
        if ($admin) {
            // Set admin sessions
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['full_name'];
            
            setFlashMessage('success', 'Logged in as Administrator. Welcome back, ' . $admin['full_name'] . '.');
            header("Location: admin-dashboard.php");
            exit;
        } else {
            setFlashMessage('error', 'Invalid admin email or password. Please try again.');
        }
    } else {
        setFlashMessage('error', 'Please enter both admin email and password.');
    }
}
?>

<!-- Admin Login Section -->
<section class="py-5 flex-grow-1 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="glass-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-dark bg-opacity-10 text-dark mb-3">
                            <i class="fa-solid fa-user-tie fs-3"></i>
                        </div>
                        <h2 class="display-font text-dark fw-bold">Admin Portal</h2>
                        <p class="text-secondary small">System Administration & verification desks</p>
                    </div>

                    <form action="admin-login.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Admin Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="admin@tracker.com" required>
                                <div class="invalid-feedback">Please enter a valid administrator email.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Admin Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3 mb-3 bg-dark border-0">
                            <i class="fa-solid fa-lock-open me-2"></i>Login as Admin
                        </button>
                        
                        <div class="text-center">
                            <p class="text-muted small mb-0"><a href="index.php" class="text-primary text-decoration-none">Back to Homepage</a></p>
                        </div>
                    </form>
                </div>
                
                <!-- Demo login helper card -->
                <div class="glass-card mt-3 p-3 bg-light border-0 text-center small text-secondary">
                    <span class="fw-bold"><i class="fa-solid fa-circle-info text-dark me-1"></i> Demo Credentials:</span><br>
                    Email: <code>admin@tracker.com</code> &nbsp;|&nbsp; Password: <code>admin123</code>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
