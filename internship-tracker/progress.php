<?php
require_once __DIR__ . '/includes/header.php';
requireStudent();

$student_id = $_SESSION['student_id'];

if (!isset($_GET['id'])) {
    setFlashMessage('error', 'Select a valid internship record to track.');
    header("Location: dashboard.php");
    exit;
}

$id = (int)$_GET['id'];
$internship = get_internship($id);

// Guard: verify ownership
if (!$internship || (int)$internship['student_id'] !== $student_id) {
    setFlashMessage('error', 'Internship record not found or access denied.');
    header("Location: dashboard.php");
    exit;
}

$progress_logs = get_progress_logs($id);

// Map stages to evaluate states (completed vs active vs future)
$stages = ['Applied', 'Shortlisted', 'Interview', 'Selected', 'Ongoing', 'Completed'];
$current_status = $internship['status'];
$current_index = array_search($current_status, $stages);

// Handle status updates from student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = isset($_POST['new_status']) ? trim($_POST['new_status']) : '';
    $notes = isset($_POST['status_notes']) ? trim($_POST['status_notes']) : '';

    if (in_array($new_status, $stages)) {
        if (update_internship_status($id, $new_status, $notes)) {
            setFlashMessage('success', 'Internship status updated to ' . $new_status);
            header("Location: progress.php?id=" . $id);
            exit;
        } else {
            setFlashMessage('error', 'Failed to update status, or status is unchanged.');
        }
    } else {
        setFlashMessage('error', 'Invalid status selection.');
    }
}
?>

<div class="container py-4 flex-grow-1">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Progress Tracker</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Timeline Tracking view -->
        <div class="col-lg-8">
            <div class="glass-card p-4 p-md-5 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="status-badge badge-<?= strtolower($internship['status']) ?> mb-2"><?= $internship['status'] ?></span>
                        <h2 class="display-font text-dark mb-1"><?= htmlspecialchars($internship['company_name']) ?></h2>
                        <h5 class="text-secondary mb-0"><?= htmlspecialchars($internship['role']) ?></h5>
                    </div>
                    <a href="upload-certificate.php?id=<?= $id ?>" class="btn btn-glass btn-sm">
                        <i class="fa-solid fa-file-arrow-up me-1"></i> Certificate Module
                    </a>
                </div>

                <hr class="border-light my-4">

                <!-- Timeline Steps Graphics -->
                <div class="timeline-steps">
                    <?php foreach ($stages as $index => $stage): 
                        $status_class = '';
                        $log_date = '';
                        
                        // Find matching log date for this status
                        foreach ($progress_logs as $log) {
                            if ($log['status'] === $stage) {
                                $log_date = date('M d, H:i', strtotime($log['updated_at']));
                            }
                        }

                        if ($index < $current_index) {
                            $status_class = 'completed';
                        } elseif ($index === $current_index) {
                            $status_class = 'active';
                        }
                    ?>
                        <div class="timeline-step <?= $status_class ?>">
                            <div class="timeline-step-icon">
                                <?php if ($status_class === 'completed'): ?>
                                    <i class="fa-solid fa-check"></i>
                                <?php else: ?>
                                    <?= $index + 1 ?>
                                <?php endif; ?>
                            </div>
                            <div class="timeline-step-label">
                                <?= $stage ?>
                                <?php if ($log_date): ?>
                                    <div class="timeline-step-date"><?= $log_date ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Detailed Stage Logs -->
            <div class="glass-card p-4 bg-white">
                <h4 class="display-font text-dark mb-4"><i class="fa-solid fa-receipt text-cyan me-2"></i>Stage History Logs</h4>
                <div class="position-relative border-start border-light ps-3 ms-2">
                    <?php foreach (array_reverse($progress_logs) as $log): ?>
                        <div class="mb-4 position-relative">
                            <!-- Bullet marker -->
                            <div class="position-absolute" style="left: -25px; top: 3px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary-light); border: 2px solid #ffffff;"></div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark"><?= htmlspecialchars($log['status']) ?></span>
                                <small class="text-muted"><?= date('M d, Y - H:i', strtotime($log['updated_at'])) ?></small>
                            </div>
                            <p class="text-secondary small mb-0"><?= htmlspecialchars($log['notes']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Info -->
        <div class="col-lg-4">
            <!-- Internship Summary Specs Card -->
            <div class="glass-card p-4 bg-white mb-4">
                <h4 class="display-font text-dark mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>Summary Sheet</h4>
                <table class="table table-borderless small mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted ps-0 py-2">Duration:</td>
                            <td class="text-dark fw-bold text-end py-2"><?= htmlspecialchars($internship['duration']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0 py-2">Timeline:</td>
                            <td class="text-dark fw-bold text-end py-2"><?= date('M d', strtotime($internship['start_date'])) ?> to <?= date('M d, Y', strtotime($internship['end_date'])) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0 py-2">Location:</td>
                            <td class="text-dark fw-bold text-end py-2"><?= htmlspecialchars($internship['location']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0 py-2">Stipend:</td>
                            <td class="text-dark fw-bold text-end py-2"><?= htmlspecialchars($internship['stipend']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0 py-2">Mentor Name:</td>
                            <td class="text-dark fw-bold text-end py-2"><?= htmlspecialchars($internship['mentor_name']) ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php if (!empty($internship['description'])): ?>
                    <hr class="border-light my-3">
                    <h6 class="fw-bold text-dark">Description:</h6>
                    <p class="text-secondary small mb-0"><?= nl2br(htmlspecialchars($internship['description'])) ?></p>
                <?php endif; ?>
            </div>

            <!-- Quick Update status form (Only if not already completed) -->
            <?php if ($current_status !== 'Completed'): ?>
                <div class="glass-card p-4 bg-white">
                    <h4 class="display-font text-dark mb-3"><i class="fa-solid fa-pen-nib text-warning me-2"></i>Log Status Update</h4>
                    
                    <form action="progress.php?id=<?= $id ?>" method="POST">
                        <input type="hidden" name="update_status" value="1">
                        
                        <div class="mb-3">
                            <label for="new_status" class="form-label small fw-semibold">Next Stage Status</label>
                            <select class="form-select" id="new_status" name="new_status">
                                <?php 
                                    // Students can select any stage after current one
                                    for ($idx = $current_index + 1; $idx < count($stages); $idx++):
                                ?>
                                    <option value="<?= $stages[$idx] ?>"><?= $stages[$idx] ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status_notes" class="form-label small fw-semibold">Activity Notes</label>
                            <textarea class="form-control small" id="status_notes" name="status_notes" rows="3" placeholder="e.g. Cleared technical test, started project documentation..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-gradient btn-sm w-100 py-2">
                            Update Status <i class="fa-solid fa-chevron-right ms-1"></i>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Completed Card -->
                <div class="glass-card p-4 bg-success bg-opacity-10 border-success border-opacity-25 text-center text-success">
                    <i class="fa-solid fa-circle-check display-4 mb-2"></i>
                    <h5 class="fw-bold display-font">Internship Completed</h5>
                    <p class="small mb-0">This internship has been successfully finished. Go to the Certificate Module to upload and download your verification certificate.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
