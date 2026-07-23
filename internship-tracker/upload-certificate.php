<?php
require_once __DIR__ . '/includes/header.php';
requireStudent();

$student_id = $_SESSION['student_id'];

if (!isset($_GET['id'])) {
    setFlashMessage('error', 'Select a valid internship record for certificate upload.');
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

// File Upload Processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['certificate'])) {
    $file = $_FILES['certificate'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $file_type = $file['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $max_size = 5 * 1024 * 1024; // 5 MB
            if ($file['size'] <= $max_size) {
                
                // Establish folder structure
                $upload_dir = __DIR__ . '/uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'cert_' . $student_id . '_' . $id . '_' . time() . '.' . $ext;
                $dest_path = $upload_dir . $filename;
                $db_path = 'uploads/' . $filename;

                if (move_uploaded_file($file['tmp_name'], $dest_path)) {
                    // Update Database Path
                    if (upload_internship_certificate($id, $db_path)) {
                        setFlashMessage('success', 'Certificate uploaded successfully. Waiting for admin approval.');
                        header("Location: upload-certificate.php?id=" . $id);
                        exit;
                    } else {
                        setFlashMessage('error', 'Failed to update database record.');
                    }
                } else {
                    setFlashMessage('error', 'Failed to save uploaded file on server.');
                }
            } else {
                setFlashMessage('error', 'File size exceeds maximum limit of 5MB.');
            }
        } else {
            setFlashMessage('error', 'Invalid file type. Only PDF, JPG, JPEG, and PNG are allowed.');
        }
    } else {
        setFlashMessage('error', 'Upload error code: ' . $file['error']);
    }
}
?>

<div class="container py-4 flex-grow-1">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="progress.php?id=<?= $id ?>" class="text-decoration-none">Progress Tracker</a></li>
            <li class="breadcrumb-item active" aria-current="page">Certificate Module</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Form to upload certificate -->
        <div class="col-lg-6">
            <div class="glass-card p-4 p-md-5 bg-white h-100">
                <h2 class="display-font text-dark mb-3"><i class="fa-solid fa-file-arrow-up text-primary me-2"></i>Upload Certificate</h2>
                <p class="text-secondary small mb-4">
                    Submit your certificate of completion or placement offer letter. Admins will verify it against internship details. Size must be under 5MB.
                </p>

                <!-- Status Panel -->
                <?php if ($internship['certificate_path'] !== null): ?>
                    <div class="alert glass-card p-3 mb-4 d-flex align-items-center justify-content-between border-0 shadow-sm
                        <?php 
                            if ($internship['certificate_status'] == 'Approved') echo 'bg-success bg-opacity-10 text-success';
                            elseif ($internship['certificate_status'] == 'Rejected') echo 'bg-danger bg-opacity-10 text-danger';
                            else echo 'bg-warning bg-opacity-10 text-warning';
                        ?>">
                        <div>
                            <span class="small fw-semibold d-block">Approval Status:</span>
                            <span class="fw-bold display-font text-uppercase"><?= $internship['certificate_status'] ?></span>
                        </div>
                        <span class="status-badge badge-<?= strtolower($internship['certificate_status']) ?>"><?= $internship['certificate_status'] ?></span>
                    </div>

                    <?php if ($internship['certificate_feedback']): ?>
                        <div class="card bg-light border-0 p-3 mb-4 rounded-3 small">
                            <span class="fw-bold text-dark"><i class="fa-solid fa-message text-primary me-1"></i> Admin Feedback:</span>
                            <p class="text-secondary mb-0 mt-1"><?= htmlspecialchars($internship['certificate_feedback']) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 mb-4">
                        <a href="<?= htmlspecialchars($internship['certificate_path']) ?>" download class="btn btn-gradient w-100 py-2">
                            <i class="fa-solid fa-download me-2"></i>Download Uploaded File
                        </a>
                    </div>
                <?php endif; ?>

                <!-- File upload drag zone -->
                <form action="upload-certificate.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                    <input type="file" id="certificateFile" name="certificate" class="d-none" accept=".pdf, .png, .jpg, .jpeg" required>
                    
                    <div class="upload-dropzone mb-4" id="uploadDropzone">
                        <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                        <h5 class="fw-bold text-dark display-font">Drag & Drop File Here</h5>
                        <p class="text-secondary small mb-0">or click to browse from folders</p>
                        <small class="text-muted d-block mt-2">Supported: PDF, JPG, PNG (Max 5MB)</small>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted small fw-semibold text-center" id="previewText">No file selected.</p>
                    </div>

                    <button type="submit" class="btn btn-glass w-100 py-3 text-primary border-primary border-opacity-25 bg-primary bg-opacity-10 hover-primary">
                        <i class="fa-solid fa-save me-2"></i>Submit for Verification
                    </button>
                </form>
            </div>
        </div>

        <!-- Live Preview window -->
        <div class="col-lg-6">
            <div class="glass-card p-4 bg-white h-100 d-flex flex-column">
                <h4 class="display-font text-dark mb-3"><i class="fa-solid fa-eye text-cyan me-2"></i>Certificate Preview</h4>
                <div class="preview-container flex-grow-1" id="previewContainer">
                    <?php if ($internship['certificate_path'] !== null): ?>
                        <?php 
                            $ext = pathinfo($internship['certificate_path'], PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg'])):
                        ?>
                            <img src="<?= htmlspecialchars($internship['certificate_path']) ?>" alt="Uploaded Certificate Preview">
                        <?php else: ?>
                            <iframe src="<?= htmlspecialchars($internship['certificate_path']) ?>#toolbar=0" width="100%" height="100%"></iframe>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <i class="fa-regular fa-file-pdf text-muted display-1 mb-3"></i>
                            <h5 class="text-dark display-font">No Certificate File</h5>
                            <p class="text-secondary small mb-0">Upload a certificate to generate instant interactive preview.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
