<?php
require_once __DIR__ . '/includes/header.php';
requireAdmin();

// Fetch Data for Admin
$all_internships = admin_get_all_internships();
$all_students = admin_get_all_students();

// Calculate Admin Stats
$admin_stats = [
    'total_students' => count($all_students),
    'total_internships' => count($all_internships),
    'ongoing_internships' => 0,
    'completed_internships' => 0,
    'pending_certificates' => 0,
    'applied' => 0,
    'shortlisted' => 0,
    'interview' => 0,
    'selected' => 0
];

foreach ($all_internships as $i) {
    if ($i['status'] === 'Ongoing') {
        $admin_stats['ongoing_internships']++;
    } elseif ($i['status'] === 'Completed') {
        $admin_stats['completed_internships']++;
    }
    
    switch ($i['status']) {
        case 'Applied': $admin_stats['applied']++; break;
        case 'Shortlisted': $admin_stats['shortlisted']++; break;
        case 'Interview': $admin_stats['interview']++; break;
        case 'Selected': $admin_stats['selected']++; break;
        case 'Ongoing': $admin_stats['ongoing_internships']++; break;
        case 'Completed': $admin_stats['completed_internships']++; break;
    }
    
    if ($i['certificate_path'] !== null && $i['certificate_status'] === 'Pending') {
        $admin_stats['pending_certificates']++;
    }
}

// Handle Certificate Review POST Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_certificate'])) {
    $internship_id = (int)$_POST['internship_id'];
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';

    if (in_array($status, ['Approved', 'Rejected'])) {
        if (admin_review_certificate($internship_id, $status, $feedback)) {
            setFlashMessage('success', 'Certificate verification status set to ' . strtoupper($status));
            header("Location: admin-dashboard.php");
            exit;
        } else {
            setFlashMessage('error', 'Failed to review certificate.');
        }
    } else {
        setFlashMessage('error', 'Invalid status selection for review.');
    }
}
?>

<div class="container py-4 flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-extrabold display-font text-dark mb-1">Admin Panel</h1>
            <p class="text-secondary mb-0">System Placement Records & Document Verification</p>
        </div>
        <button onclick="exportTableToCSV('adminMasterTable', 'internship_master_report.csv')" class="btn btn-gradient">
            <i class="fa-solid fa-file-csv me-2"></i>Export CSV Report
        </button>
    </div>

    <!-- Widgets Board -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-blue">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Students Registered</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $admin_stats['total_students'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-users"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-cyan">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Total Internships</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $admin_stats['total_internships'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-briefcase"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-green">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Ongoing Internships</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $admin_stats['ongoing_internships'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-spinner fa-spin-pulse"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-orange">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Pending Reviews</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $admin_stats['pending_certificates'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-circle-exclamation"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard Tab and Search Bar -->
    <div class="row g-4 mb-4">
        <!-- Analytics Status Distribution -->
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100 bg-white">
                <h4 class="display-font text-dark mb-4"><i class="fa-solid fa-chart-bar text-primary me-2"></i>Global Placement Analytics</h4>
                <div class="position-relative" style="height: 280px;">
                    <canvas id="adminStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Master search filtering parameters -->
        <div class="col-lg-7">
            <div class="glass-card p-4 h-100 bg-white">
                <h4 class="display-font text-dark mb-4"><i class="fa-solid fa-sliders text-cyan me-2"></i>Filters and Controls</h4>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="searchStudent" class="form-label fw-semibold small text-muted">Search Student / Roll Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control bg-light border-0" id="searchStudent" placeholder="Search by name or roll number...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="filterDept" class="form-label fw-semibold small text-muted">Filter by Department</label>
                        <select class="form-select bg-light border-0" id="filterDept">
                            <option value="">All Departments</option>
                            <option value="Computer Applications">Computer Applications (MCA)</option>
                            <option value="Computer Science Engineering">Computer Science (CSE)</option>
                            <option value="Information Technology">Information Technology (IT)</option>
                            <option value="Data Science">Data Science</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Quick Desk Actions</label>
                        <div class="d-flex gap-2">
                            <button onclick="document.getElementById('filterDept').value=''; document.getElementById('searchStudent').value=''; filterStudents();" class="btn btn-glass w-100 btn-sm text-primary py-2">
                                <i class="fa-solid fa-refresh me-1"></i>Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student and Internships List Tab panel -->
    <div class="glass-card p-4 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="display-font text-dark mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i>Student Registration Registry</h4>
            <span class="badge bg-light text-dark px-3 py-2 border">Total Registered Rows: <?= count($all_students) ?></span>
        </div>

        <?php if (empty($all_students)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-users-slash text-muted display-2 mb-3"></i>
                <h5 class="text-dark display-font">No Registered Students</h5>
                <p class="text-secondary small mb-0">No student accounts found in the database directory.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table custom-table" id="adminMasterTable">
                    <thead>
                        <tr>
                            <th>Student Details</th>
                            <th>Roll Number</th>
                            <th>Department</th>
                            <th>Contact</th>
                            <th>Total Registered</th>
                            <th>Date Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_students as $student): 
                            $student_internships = get_student_internships($student['id']);
                        ?>
                            <tr class="student-row" data-dept="<?= htmlspecialchars($student['department']) ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($student['profile_pic'] ?: 'assets/images/default-avatar.png') ?>" alt="Avatar" class="rounded-circle me-3 object-fit-cover shadow-sm" style="width: 36px; height: 36px;">
                                        <div>
                                            <span class="fw-bold text-dark student-name"><?= htmlspecialchars($student['full_name']) ?></span><br>
                                            <small class="text-muted"><?= htmlspecialchars($student['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-semibold text-dark student-roll"><?= htmlspecialchars($student['roll_no']) ?></span></td>
                                <td><?= htmlspecialchars($student['department']) ?></td>
                                <td><?= htmlspecialchars($student['phone'] ?: 'N/A') ?></td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-semibold">
                                        <?= count($student_internships) ?> Internships
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($student['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Certificate Approval List desk -->
    <div class="glass-card p-4 bg-white">
        <h4 class="display-font text-dark mb-4"><i class="fa-solid fa-stamp text-success me-2"></i>Pending Certificate Verification Desk</h4>
        
        <?php 
            $pending_certs_list = [];
            foreach ($all_internships as $i) {
                if ($i['certificate_path'] !== null && $i['certificate_status'] === 'Pending') {
                    $pending_certs_list[] = $i;
                }
            }
        ?>

        <?php if (empty($pending_certs_list)): ?>
            <div class="text-center py-5 bg-light rounded-3 border-dashed">
                <i class="fa-solid fa-clipboard-check text-muted display-4 mb-2"></i>
                <h5 class="text-dark display-font">All caught up!</h5>
                <p class="text-secondary small mb-0">No certificates currently waiting for approval.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Company & Role</th>
                            <th>Duration</th>
                            <th>Certificate</th>
                            <th class="text-end">Verification Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_certs_list as $i): ?>
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($i['student_name']) ?></span><br>
                                    <small class="text-muted">Roll: <?= htmlspecialchars($i['roll_no']) ?></small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($i['company_name']) ?></span><br>
                                    <small class="text-secondary"><?= htmlspecialchars($i['role']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($i['duration']) ?></td>
                                <td>
                                    <a href="<?= htmlspecialchars($i['certificate_path']) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-3">
                                        <i class="fa-solid fa-eye me-1"></i>View Document
                                    </a>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <button class="btn btn-success btn-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#verifyModal<?= $i['id'] ?>">
                                            <i class="fa-solid fa-check me-1"></i>Verify / Review
                                        </button>
                                    </div>

                                    <!-- Review Modal -->
                                    <div class="modal fade" id="verifyModal<?= $i['id'] ?>" tabindex="-1" aria-labelledby="verifyModalLabel<?= $i['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content glass-card p-2 border-0 bg-white">
                                                <div class="modal-header border-bottom-0">
                                                    <h5 class="modal-title fw-bold display-font" id="verifyModalLabel<?= $i['id'] ?>">Verify Certificate</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="admin-dashboard.php" method="POST">
                                                    <div class="modal-body text-start">
                                                        <input type="hidden" name="review_certificate" value="1">
                                                        <input type="hidden" name="internship_id" value="<?= $i['id'] ?>">
                                                        
                                                        <p class="small text-muted mb-3">Reviewing certificate of completion uploaded by <strong><?= htmlspecialchars($i['student_name']) ?></strong> for <strong><?= htmlspecialchars($i['company_name']) ?></strong>.</p>
                                                        
                                                        <div class="mb-3">
                                                            <label for="statusSelect<?= $i['id'] ?>" class="form-label fw-bold small text-dark">Verification Status</label>
                                                            <select class="form-select" id="statusSelect<?= $i['id'] ?>" name="status" required>
                                                                <option value="Approved" selected>Approve Certificate</option>
                                                                <option value="Rejected">Reject Certificate</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="feedbackArea<?= $i['id'] ?>" class="form-label fw-bold small text-dark">Assessor Feedback / Reasons</label>
                                                            <textarea class="form-control small" id="feedbackArea<?= $i['id'] ?>" name="feedback" rows="3" placeholder="Excellent performance / Document matches records..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 d-flex gap-2">
                                                        <button type="submit" class="btn btn-gradient btn-sm flex-grow-1 py-2">Submit Decision</button>
                                                        <button type="button" class="btn btn-light btn-sm py-2" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Initialize Admin Status Dashboard Chart
    document.addEventListener('DOMContentLoaded', function() {
        const adminStatsData = {
            applied: <?= $admin_stats['applied'] ?>,
            shortlisted: <?= $admin_stats['shortlisted'] ?>,
            interview: <?= $admin_stats['interview'] ?>,
            selected: <?= $admin_stats['selected'] ?>,
            ongoing: <?= $admin_stats['ongoing_internships'] ?>,
            completed: <?= $admin_stats['completed_internships'] ?>
        };
        initStatusChart('adminStatusChart', adminStatsData);
    });
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
