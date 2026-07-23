<?php
require_once __DIR__ . '/includes/header.php';
requireStudent();

$student_id = $_SESSION['student_id'];
$student = get_student_details($student_id);
$stats = get_student_stats($student_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    
    // Avatar upload (optional)
    $profile_pic_path = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (in_array($file['type'], $allowed_types)) {
            $upload_dir = __DIR__ . '/uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $student_id . '_' . time() . '.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
                $profile_pic_path = 'uploads/' . $filename;
            }
        }
    }

    if (!empty($full_name) && !empty($department)) {
        if (update_student_profile($student_id, $full_name, $phone, $department, $profile_pic_path)) {
            // Update session name
            $_SESSION['student_name'] = $full_name;
            setFlashMessage('success', 'Profile updated successfully.');
            header("Location: profile.php");
            exit;
        } else {
            setFlashMessage('error', 'Failed to update profile information.');
        }
    } else {
        setFlashMessage('error', 'Name and Department are required.');
    }
}
?>

<div class="container py-4 flex-grow-1">
    <div class="row g-4">
        <!-- Profile details edit card -->
        <div class="col-lg-7">
            <div class="glass-card p-4 p-md-5 bg-white">
                <h2 class="display-font text-dark mb-4"><i class="fa-solid fa-id-card-clip text-primary me-2"></i>My Placement Profile</h2>
                
                <form action="profile.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="d-flex flex-column flex-sm-row align-items-center gap-4 mb-4 pb-3 border-bottom border-light">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($student['profile_pic'] ?: 'assets/images/default-avatar.png') ?>" alt="Profile Picture" class="rounded-circle border border-primary border-opacity-25 object-fit-cover shadow" style="width: 100px; height: 100px;">
                        </div>
                        <div class="text-center text-sm-start">
                            <h4 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($student['full_name']) ?></h4>
                            <p class="text-secondary small mb-2"><?= htmlspecialchars($student['email']) ?></p>
                            <label for="avatarInput" class="btn btn-outline-primary btn-sm px-3 py-1 rounded-pill">
                                <i class="fa-solid fa-camera me-1"></i>Change Picture
                            </label>
                            <input type="file" id="avatarInput" name="avatar" class="d-none" accept=".png, .jpg, .jpeg">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="roll_no" class="form-label fw-semibold">Roll Number</label>
                            <input type="text" class="form-control bg-light text-muted" id="roll_no" value="<?= htmlspecialchars($student['roll_no']) ?>" readonly>
                            <small class="text-muted fs-xs">Roll number cannot be modified.</small>
                        </div>

                        <div class="col-md-6">
                            <label for="email_static" class="form-label fw-semibold">Registered Email</label>
                            <input type="text" class="form-control bg-light text-muted" id="email_static" value="<?= htmlspecialchars($student['email']) ?>" readonly>
                            <small class="text-muted fs-xs">Email coordinates cannot be modified.</small>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($student['phone']) ?>">
                        </div>

                        <div class="col-12">
                            <label for="department" class="form-label fw-semibold">Department / Faculty</label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="Computer Applications" <?= $student['department'] == 'Computer Applications' ? 'selected' : '' ?>>Computer Applications (MCA)</option>
                                <option value="Computer Science Engineering" <?= $student['department'] == 'Computer Science Engineering' ? 'selected' : '' ?>>Computer Science Engineering (B.Tech CSE)</option>
                                <option value="Information Technology" <?= $student['department'] == 'Information Technology' ? 'selected' : '' ?>>Information Technology (B.Tech IT)</option>
                                <option value="Data Science" <?= $student['department'] == 'Data Science' ? 'selected' : '' ?>>Data Science & AI</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-gradient w-100 py-3">
                                <i class="fa-solid fa-user-check me-2"></i>Update Profile Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar metrics card -->
        <div class="col-lg-5">
            <div class="glass-card p-4 p-md-5 bg-white h-100 d-flex flex-column justify-content-between">
                <div>
                    <h3 class="display-font text-dark mb-4"><i class="fa-solid fa-chart-line text-cyan me-2"></i>Placement Metrics</h3>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <span class="d-block small text-muted">Registered</span>
                                <h3 class="display-font fw-extrabold text-primary mb-0 mt-1"><?= $stats['total'] ?></h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <span class="d-block small text-muted">Ongoing</span>
                                <h3 class="display-font fw-extrabold text-cyan mb-0 mt-1"><?= $stats['ongoing'] ?></h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <span class="d-block small text-muted">Completed</span>
                                <h3 class="display-font fw-extrabold text-success mb-0 mt-1"><?= $stats['completed'] ?></h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <span class="d-block small text-muted">Cert Pending</span>
                                <h3 class="display-font fw-extrabold text-warning mb-0 mt-1"><?= $stats['pending_cert'] ?></h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-light border-0 p-3 mb-4 rounded-3 text-center small text-secondary">
                        <span class="fw-bold d-block mb-1 text-dark">Status Check:</span>
                        Your profile status is currently <span class="badge bg-success bg-opacity-10 text-success ms-1">Active</span>
                    </div>
                </div>

                <div class="border-top pt-4 text-center">
                    <p class="text-secondary small mb-0">Registered on: <?= date('M d, Y', strtotime($student['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
