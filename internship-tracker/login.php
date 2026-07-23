<?php
require_once __DIR__ . '/includes/header.php';

// Redirect if already logged in
redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($email) && !empty($password)) {
        $user = student_login($email, $password);
        if ($user) {
            // Set student sessions
            $_SESSION['student_id'] = $user['id'];
            $_SESSION['student_email'] = $user['email'];
            $_SESSION['student_name'] = $user['full_name'];
            
            setFlashMessage('success', 'Logged in successfully! Welcome back, ' . $user['full_name'] . '.');
            header("Location: dashboard.php");
            exit;
        } else {
            setFlashMessage('error', 'Invalid email or password. Please try again.');
        }
    } else {
        setFlashMessage('error', 'Please enter both email and password.');
    }
}
?>

<!-- Login Section -->
<section class="py-5 flex-grow-1 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="glass-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                            <i class="fa-solid fa-user-graduate fs-3"></i>
                        </div>
                        <h2 class="display-font text-dark fw-bold">Student Portal</h2>
                        <p class="text-secondary small">Access your internship tracker dashboard</p>
                    </div>

                    <form action="login.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="student@tracker.com" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label fw-semibold mb-0">Password</label>
                                <a href="contact.php" class="text-xs text-primary text-decoration-none">Forgot Password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gradient w-100 py-3 mb-3">
                            <i class="fa-solid fa-sign-in-alt me-2"></i>Login
                        </button>
                        
                        <div class="text-center">
                            <p class="text-muted small mb-0">Don't have an account? <a href="register.php" class="text-primary fw-semibold text-decoration-none">Register here</a></p>
                        </div>
                    </form>
                </div>
                
                <!-- Demo login helper card -->
                <div class="glass-card mt-3 p-3 bg-light border-0 text-center small text-secondary">
                    <span class="fw-bold"><i class="fa-solid fa-circle-info text-primary me-1"></i> Demo Credentials:</span><br>
                    Email: <code>student@tracker.com</code> &nbsp;|&nbsp; Password: <code>student123</code>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
