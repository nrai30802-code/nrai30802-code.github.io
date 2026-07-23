<?php
require_once __DIR__ . '/includes/header.php';

// Redirect if already logged in
redirectIfLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $roll_no = isset($_POST['roll_no']) ? trim($_POST['roll_no']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (!empty($full_name) && !empty($email) && !empty($password) && !empty($roll_no) && !empty($department)) {
        $user = student_register($email, $password, $full_name, $roll_no, $department, $phone);
        if ($user) {
            // Auto login after registration
            $_SESSION['student_id'] = $user['id'];
            $_SESSION['student_email'] = $user['email'];
            $_SESSION['student_name'] = $user['full_name'];
            
            setFlashMessage('success', 'Registration completed successfully! Welcome, ' . $user['full_name'] . '.');
            header("Location: dashboard.php");
            exit;
        } else {
            setFlashMessage('error', 'Registration failed. Email or Roll Number already exists in the system.');
        }
    } else {
        setFlashMessage('error', 'Please fill in all required fields.');
    }
}
?>

<!-- Register Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="glass-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                            <i class="fa-solid fa-user-plus fs-3"></i>
                        </div>
                        <h2 class="display-font text-dark fw-bold">Student Registration</h2>
                        <p class="text-secondary small">Sign up to manage and track your internships</p>
                    </div>

                    <form action="register.php" method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Neha Rai" required>
                                    <div class="invalid-feedback">Please enter your full name.</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="neha@gmail.com" required>
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" minlength="6" required>
                                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="roll_no" class="form-label fw-semibold">Roll Number / Enrollment <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="roll_no" name="roll_no" placeholder="MCA-2024-089" required>
                                    <div class="invalid-feedback">Please enter your roll number.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91 9876543210">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="department" class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-building-columns"></i></span>
                                    <select class="form-select" id="department" name="department" required>
                                        <option value="" disabled selected>Select your department</option>
                                        <option value="Computer Applications">Computer Applications (MCA)</option>
                                        <option value="Computer Science Engineering">Computer Science Engineering (B.Tech CSE)</option>
                                        <option value="Information Technology">Information Technology (B.Tech IT)</option>
                                        <option value="Data Science">Data Science & AI</option>
                                    </select>
                                    <div class="invalid-feedback">Please select your department.</div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gradient w-100 py-3">
                                    <i class="fa-solid fa-user-plus me-2"></i>Register Account
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <p class="text-muted small mb-0">Already have an account? <a href="login.php" class="text-primary fw-semibold text-decoration-none">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
