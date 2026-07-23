<?php
require_once __DIR__ . '/includes/header.php';
requireStudent();

$student_id = $_SESSION['student_id'];
$student = get_student_details($student_id);
$internships = get_student_internships($student_id);
$stats = get_student_stats($student_id);

// Create activities list
$activities = [];
foreach ($internships as $intern) {
    $logs = get_progress_logs($intern['id']);
    foreach ($logs as $log) {
        $activities[] = [
            'type' => 'status',
            'company' => $intern['company_name'],
            'role' => $intern['role'],
            'status' => $log['status'],
            'notes' => $log['notes'],
            'date' => $log['updated_at']
        ];
    }
    if ($intern['certificate_path'] !== null) {
        $activities[] = [
            'type' => 'certificate',
            'company' => $intern['company_name'],
            'role' => $intern['role'],
            'status' => $intern['certificate_status'],
            'notes' => 'Uploaded certificate for review.',
            'date' => $intern['created_at'] // approximation
        ];
    }
}
// Sort activities by date desc
usort($activities, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
$recent_activities = array_slice($activities, 0, 5);
?>

<div class="container py-4">
    <!-- Welcome Header Card -->
    <div class="glass-card p-4 p-md-5 mb-4 text-white" style="background: var(--gradient-primary); border: none;">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h1 class="display-5 fw-extrabold display-font">Welcome back, <?= htmlspecialchars($student['full_name']) ?>!</h1>
                <p class="lead mb-0 opacity-75">Department of <?= htmlspecialchars($student['department']) ?> | Roll No: <?= htmlspecialchars($student['roll_no']) ?></p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="add-internship.php" class="btn btn-light text-primary fw-bold py-2 px-4 rounded-3 shadow">
                    <i class="fa-solid fa-plus-circle me-1"></i> Add Internship
                </a>
            </div>
        </div>
    </div>

    <!-- Widgets Area -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-blue">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Total Applications</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $stats['total'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-folder-open"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-cyan">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Ongoing Internships</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $stats['ongoing'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-spinner fa-spin-pulse"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-green">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Completed Internships</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $stats['completed'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-award"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-card widget-orange">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold opacity-75">Pending Certifications</h6>
                        <h2 class="display-font fw-extrabold mb-0"><?= $stats['pending_cert'] ?></h2>
                    </div>
                    <div class="fs-1"><i class="fa-solid fa-file-signature"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Chart.js Visualization -->
        <div class="col-lg-6">
            <div class="glass-card p-4 h-100 bg-white">
                <h4 class="display-font text-dark mb-4"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Internship Status Distribution</h4>
                <div class="position-relative" style="height: 300px;">
                    <canvas id="statusDoughnutChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities log -->
        <div class="col-lg-6">
            <div class="glass-card p-4 h-100 bg-white">
                <h4 class="display-font text-dark mb-4"><i class="fa-solid fa-history me-2 text-cyan"></i>Recent Activity Logs</h4>
                <?php if (empty($recent_activities)): ?>
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center py-5">
                        <i class="fa-solid fa-clock-rotate-left text-muted display-4 mb-2"></i>
                        <p class="text-muted">No activity logged yet. Start by adding an internship.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_activities as $act): ?>
                            <div class="list-group-item px-0 py-3 border-bottom border-light">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="fw-bold text-dark mb-1">
                                        <?php if ($act['type'] === 'status'): ?>
                                            Status Update: <?= htmlspecialchars($act['company']) ?>
                                        <?php else: ?>
                                            Certificate Uploaded: <?= htmlspecialchars($act['company']) ?>
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-muted"><?= date('M d, Y', strtotime($act['date'])) ?></small>
                                </div>
                                <p class="text-secondary small mb-1">Role: <?= htmlspecialchars($act['role']) ?></p>
                                <div class="d-flex align-items-center">
                                    <?php if ($act['type'] === 'status'): ?>
                                        <span class="status-badge badge-<?= strtolower($act['status']) ?> me-2"><?= $act['status'] ?></span>
                                    <?php else: ?>
                                        <span class="status-badge badge-<?= strtolower($act['status']) ?> me-2">Cert: <?= $act['status'] ?></span>
                                    <?php endif; ?>
                                    <span class="text-muted small"><?= htmlspecialchars($act['notes']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Internships List table -->
    <div class="glass-card p-4 bg-white">
        <div class="row align-items-center g-3 mb-4">
            <div class="col-md-4">
                <h4 class="display-font text-dark mb-0"><i class="fa-solid fa-briefcase me-2 text-primary"></i>My Registered Internships</h4>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-0" id="searchInternship" placeholder="Search by company or role...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Statuses</option>
                    <option value="Applied">Applied</option>
                    <option value="Shortlisted">Shortlisted</option>
                    <option value="Interview">Interview</option>
                    <option value="Selected">Selected</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
        </div>

        <?php if (empty($internships)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-open text-muted display-1 mb-3"></i>
                <h5 class="text-dark display-font">No Internships Registered</h5>
                <p class="text-secondary small mb-4">Submit your active or completed internships to start tracking progress.</p>
                <a href="add-internship.php" class="btn btn-gradient px-4"><i class="fa-solid fa-plus-circle me-1"></i> Register Internship Now</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Duration</th>
                            <th>Mentor</th>
                            <th>Stipend</th>
                            <th>Status</th>
                            <th>Certificate</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($internships as $i): ?>
                            <tr class="internship-row" data-status="<?= $i['status'] ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded bg-primary bg-opacity-10 text-primary me-3 fw-bold small" style="width: 40px; height:40px; display:flex; justify-content:center; align-items:center;">
                                            <?= substr(htmlspecialchars($i['company_name']), 0, 2) ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark company-name"><?= htmlspecialchars($i['company_name']) ?></span><br>
                                            <small class="text-muted"><i class="fa-solid fa-map-marker-alt me-1"></i><?= htmlspecialchars($i['location']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark internship-role"><?= htmlspecialchars($i['role']) ?></span><br>
                                    <small class="text-muted"><?= date('M d, Y', strtotime($i['start_date'])) ?> to <?= date('M d, Y', strtotime($i['end_date'])) ?></small>
                                </td>
                                <td><?= htmlspecialchars($i['duration']) ?></td>
                                <td><?= htmlspecialchars($i['mentor_name']) ?></td>
                                <td><?= htmlspecialchars($i['stipend']) ?></td>
                                <td>
                                    <span class="status-badge badge-<?= strtolower($i['status']) ?>"><?= $i['status'] ?></span>
                                </td>
                                <td>
                                    <?php if ($i['certificate_path'] === null): ?>
                                        <span class="text-muted small"><i class="fa-solid fa-circle-minus me-1"></i>Not Uploaded</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-<?= strtolower($i['certificate_status']) ?>">Cert: <?= $i['certificate_status'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow glass-card mt-2">
                                            <li><a class="dropdown-item py-2" href="progress.php?id=<?= $i['id'] ?>"><i class="fa-solid fa-route me-2 text-primary"></i> Track Timeline</a></li>
                                            <li><a class="dropdown-item py-2" href="upload-certificate.php?id=<?= $i['id'] ?>"><i class="fa-solid fa-file-arrow-up me-2 text-cyan"></i> Certificate Module</a></li>
                                            <li><a class="dropdown-item py-2" href="add-internship.php?edit_id=<?= $i['id'] ?>"><i class="fa-solid fa-edit me-2 text-warning"></i> Edit Details</a></li>
                                        </ul>
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
    // Initialize Status Breakdown Chart
    document.addEventListener('DOMContentLoaded', function() {
        const statsData = {
            applied: <?= $stats['applied'] ?>,
            shortlisted: <?= $stats['shortlisted'] ?>,
            interview: <?= $stats['interview'] ?>,
            selected: <?= $stats['selected'] ?>,
            ongoing: <?= $stats['ongoing'] ?>,
            completed: <?= $stats['completed'] ?>
        };
        initStatusChart('statusDoughnutChart', statsData);
    });
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
