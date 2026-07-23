<?php
require_once __DIR__ . '/includes/header.php';
?>

<!-- About Overview Section -->
<section class="py-5">
    <div class="container py-lg-4">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-6">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-3">📖 About Platform</span>
                <h2 class="display-4 text-dark display-font mb-4">Digitizing Career Path Verification</h2>
                <p class="text-secondary mb-3">
                    The <strong>Digital Internship Tracking System (DITS)</strong> is designed to replace paper-based log books and manual confirmation procedures. It allows educational institutes, departments, and students to coordinate internship opportunities under one unified platform.
                </p>
                <p class="text-secondary mb-4">
                    Students can record their progress at every stage of the lifecycle, keep detailed records of mentor assignments, stipends, and roles, and upload final reports. Meanwhile, college administrators gain bird's-eye views of placements, approve certifications, and compile performance metrics.
                </p>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                            <span class="fw-semibold text-dark">Real-time Timeline</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                            <span class="fw-semibold text-dark">Secure Verification</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                            <span class="fw-semibold text-dark">CSV Analytics Export</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                            <span class="fw-semibold text-dark">Mobile Responsive</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="glass-card p-5 bg-gradient-primary text-white position-relative" style="background: var(--gradient-primary); border: none;">
                    <div class="position-absolute top-0 end-0 p-4 opacity-10">
                        <i class="fa-solid fa-quote-right display-1"></i>
                    </div>
                    <h3 class="display-font mb-3">Empowering Growth</h3>
                    <p class="lead mb-4 opacity-75">
                        "The best way to predict the future is to create it. We give students the structural tools to record, trace, and validate their professional stepping stones."
                    </p>
                    <hr class="border-white opacity-25 mb-4">
                    <div class="d-flex align-items-center">
                        <div class="ms-1">
                            <p class="fw-bold mb-0">MCA Program Administration</p>
                            <small class="opacity-50">Department of Computer Applications</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Interactive Workflow Step cards -->
        <div class="mt-5 pt-lg-4">
            <div class="text-center max-w-600 mx-auto mb-5">
                <h3 class="display-font text-dark h2">How DITS Works</h3>
                <p class="text-secondary">A straightforward, structured path from user registration to administrative certification.</p>
            </div>
            
            <div class="row g-4">
                <!-- Step 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card p-4 h-100">
                        <div class="fs-5 fw-bold text-primary mb-3">01 / Register & Profile</div>
                        <h5 class="text-dark display-font mb-2">Create Student Account</h5>
                        <p class="text-secondary small mb-0">
                            Register with your academic credentials, departments, roll numbers, and contact numbers. Update your dashboard details anytime.
                        </p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card p-4 h-100">
                        <div class="fs-5 fw-bold text-cyan mb-3">02 / Internship Setup</div>
                        <h5 class="text-dark display-font mb-2">Add Internship Record</h5>
                        <p class="text-secondary small mb-0">
                            Enter organization details, role titles, mentor contacts, location, duration, and stipend information to begin.
                        </p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card p-4 h-100">
                        <div class="fs-5 fw-bold text-emerald mb-3">03 / State Tracking</div>
                        <h5 class="text-dark display-font mb-2">Update Stage Logs</h5>
                        <p class="text-secondary small mb-0">
                            Trace applications sequentially (Applied ➔ Shortlisted ➔ Interview ➔ Selected ➔ Ongoing ➔ Completed) on a responsive timeline.
                        </p>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card p-4 h-100">
                        <div class="fs-5 fw-bold text-warning mb-3">04 / Proof Submission</div>
                        <h5 class="text-dark display-font mb-2">Upload Certificates</h5>
                        <p class="text-secondary small mb-0">
                            Upload your completion letters in PDF/image format. View live document previews and download links.
                        </p>
                    </div>
                </div>
                <!-- Step 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card p-4 h-100">
                        <div class="fs-5 fw-bold text-indigo mb-3">05 / Admin Review</div>
                        <h5 class="text-dark display-font mb-2">Verification & Feedback</h5>
                        <p class="text-secondary small mb-0">
                            System administrators verify files, approve states, write assessment reviews, and notify students instantly.
                        </p>
                    </div>
                </div>
                <!-- Step 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card p-4 h-100">
                        <div class="fs-5 fw-bold text-danger mb-3">06 / Analytics Export</div>
                        <h5 class="text-dark display-font mb-2">CSV Data Generation</h5>
                        <p class="text-secondary small mb-0">
                            Create visual report dashboards, search student profiles, filter categories, and download CSV spreadsheet reports.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
