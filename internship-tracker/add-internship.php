<?php
require_once __DIR__ . '/includes/header.php';
requireStudent();

$student_id = $_SESSION['student_id'];
$edit_mode = false;
$internship = null;

// Edit Mode Check
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $intern = get_internship($edit_id);
    // Security check: ensure student owns this internship
    if ($intern && (int)$intern['student_id'] === $student_id) {
        $edit_mode = true;
        $internship = $intern;
    } else {
        setFlashMessage('error', 'Internship record not found or access denied.');
        header("Location: dashboard.php");
        exit;
    }
}

// Form Submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $duration = isset($_POST['duration']) ? trim($_POST['duration']) : '';
    $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'Applied';
    $mentor_name = isset($_POST['mentor_name']) ? trim($_POST['mentor_name']) : '';
    $stipend = isset($_POST['stipend']) ? trim($_POST['stipend']) : 'Unpaid';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    
    if (!empty($company_name) && !empty($role) && !empty($duration) && !empty($start_date) && !empty($end_date) && !empty($mentor_name) && !empty($location)) {
        if ($edit_mode) {
            // Update Internship details
            global $db, $is_mock;
            $success = false;
            
            // Check status change to log progress
            $status_changed = ($internship['status'] !== $status);
            
            if ($is_mock) {
                if (isset($_SESSION['mock_internships'][$edit_id])) {
                    $_SESSION['mock_internships'][$edit_id]['company_name'] = $company_name;
                    $_SESSION['mock_internships'][$edit_id]['role'] = $role;
                    $_SESSION['mock_internships'][$edit_id]['duration'] = $duration;
                    $_SESSION['mock_internships'][$edit_id]['start_date'] = $start_date;
                    $_SESSION['mock_internships'][$edit_id]['end_date'] = $end_date;
                    $_SESSION['mock_internships'][$edit_id]['mentor_name'] = $mentor_name;
                    $_SESSION['mock_internships'][$edit_id]['stipend'] = $stipend;
                    $_SESSION['mock_internships'][$edit_id]['location'] = $location;
                    $_SESSION['mock_internships'][$edit_id]['description'] = $description;
                    
                    if ($status_changed) {
                        $_SESSION['mock_internships'][$edit_id]['status'] = $status;
                        $_SESSION['mock_progress_logs'][$edit_id][] = [
                            'status' => $status,
                            'notes' => 'Status updated through edit panel.',
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        add_notification($student_id, "Updated status of internship at $company_name to $status.");
                    }
                    $success = true;
                }
            } else {
                $stmt = $db->prepare("UPDATE internships SET company_name = ?, role = ?, duration = ?, start_date = ?, end_date = ?, mentor_name = ?, stipend = ?, location = ?, description = ? WHERE id = ?");
                $success = $stmt->execute([$company_name, $role, $duration, $start_date, $end_date, $mentor_name, $stipend, $location, $description, $edit_id]);
                
                if ($status_changed) {
                    update_internship_status($edit_id, $status, 'Status updated through edit panel.');
                }
            }
            
            if ($success) {
                setFlashMessage('success', 'Internship details updated successfully.');
                header("Location: dashboard.php");
                exit;
            } else {
                setFlashMessage('error', 'Failed to update internship details.');
            }
        } else {
            // Add New Internship
            $new_id = add_internship($student_id, $company_name, $role, $duration, $start_date, $end_date, $status, $mentor_name, $stipend, $location, $description);
            if ($new_id) {
                setFlashMessage('success', 'New internship registered successfully.');
                header("Location: dashboard.php");
                exit;
            } else {
                setFlashMessage('error', 'Failed to register internship. Please verify details.');
            }
        }
    } else {
        setFlashMessage('error', 'Please fill in all the required form fields.');
    }
}
?>

<div class="container py-4 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Navigation Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $edit_mode ? 'Edit Details' : 'Add Internship' ?></li>
                </ol>
            </nav>

            <div class="glass-card p-4 p-md-5 bg-white">
                <h2 class="display-font text-dark mb-4">
                    <i class="fa-solid <?= $edit_mode ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?> me-2"></i>
                    <?= $edit_mode ? 'Edit Internship Record' : 'Register New Internship' ?>
                </h2>
                
                <form action="add-internship.php<?= $edit_mode ? '?edit_id=' . $internship['id'] : '' ?>" method="POST" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="e.g. Google" value="<?= $edit_mode ? htmlspecialchars($internship['company_name']) : '' ?>" required>
                            <div class="invalid-feedback">Company name is required.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label fw-semibold">Internship Role <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="role" name="role" placeholder="e.g. Software Engineer Intern" value="<?= $edit_mode ? htmlspecialchars($internship['role']) : '' ?>" required>
                            <div class="invalid-feedback">Internship role is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="duration" class="form-label fw-semibold">Duration <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g. 3 Months" value="<?= $edit_mode ? htmlspecialchars($internship['duration']) : '' ?>" required>
                            <div class="invalid-feedback">Duration is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="start_date" class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $edit_mode ? $internship['start_date'] : '' ?>" required>
                            <div class="invalid-feedback">Start date is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="end_date" class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $edit_mode ? $internship['end_date'] : '' ?>" required>
                            <div class="invalid-feedback">End date is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Current Placement Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Applied" <?= $edit_mode && $internship['status'] == 'Applied' ? 'selected' : '' ?>>Applied</option>
                                <option value="Shortlisted" <?= $edit_mode && $internship['status'] == 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                                <option value="Interview" <?= $edit_mode && $internship['status'] == 'Interview' ? 'selected' : '' ?>>Interview</option>
                                <option value="Selected" <?= $edit_mode && $internship['status'] == 'Selected' ? 'selected' : '' ?>>Selected</option>
                                <option value="Ongoing" <?= $edit_mode && $internship['status'] == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                <option value="Completed" <?= $edit_mode && $internship['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="mentor_name" class="form-label fw-semibold">Mentor / Supervisor Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mentor_name" name="mentor_name" placeholder="Mr. Amit Sharma" value="<?= $edit_mode ? htmlspecialchars($internship['mentor_name']) : '' ?>" required>
                            <div class="invalid-feedback">Mentor name is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="stipend" class="form-label fw-semibold">Stipend Amount</label>
                            <input type="text" class="form-control" id="stipend" name="stipend" placeholder="e.g. $50,000 / Month or Unpaid" value="<?= $edit_mode ? htmlspecialchars($internship['stipend']) : '' ?>">
                        </div>

                        <div class="col-12">
                            <label for="location" class="form-label fw-semibold">Location / Office Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="location" name="location" placeholder="e.g. Noida, Delhi (Hybrid) or Remote" value="<?= $edit_mode ? htmlspecialchars($internship['location']) : '' ?>" required>
                            <div class="invalid-feedback">Placement location is required.</div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Internship Description / Task Scope</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Briefly describe your tasks, technologies used, and responsibilities..."><?= $edit_mode ? htmlspecialchars($internship['description']) : '' ?></textarea>
                        </div>

                        <div class="col-12 mt-4 d-flex gap-3">
                            <button type="submit" class="btn btn-gradient px-4 py-3 flex-grow-1">
                                <i class="fa-solid fa-save me-2"></i><?= $edit_mode ? 'Save Changes' : 'Register Internship' ?>
                            </button>
                            <a href="dashboard.php" class="btn btn-glass px-4 py-3 text-decoration-none text-center">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
