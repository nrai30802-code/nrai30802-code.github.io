<?php
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="py-5 position-relative overflow-hidden">
    <div class="container py-lg-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 fade-in-up">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-3">🎓 Smart Career Management</span>
                <h1 class="display-3 fw-extrabold display-font text-dark mb-4 lh-sm">
                    Digital Internship <br>
                    <span class="gradient-text">Tracking System</span>
                </h1>
                <p class="lead text-secondary mb-4 fs-5">
                    A comprehensive portal for MCA & Tech students to manage, monitor, and verify internships. Seamlessly track applications from submission to final certificate approval.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="register.php" class="btn btn-gradient px-4 py-3"><i class="fa-solid fa-rocket me-2"></i>Get Started</a>
                    <a href="login.php" class="btn btn-glass px-4 py-3"><i class="fa-solid fa-user-graduate me-2"></i>Student Login</a>
                    <a href="admin-login.php" class="btn btn-outline-dark px-4 py-3 border-secondary border-opacity-50"><i class="fa-solid fa-user-tie me-2"></i>Admin Login</a>
                </div>
            </div>
            
            <div class="col-lg-6 text-center animate-float">
                <!-- Premium Career / Analytical SVG Illustration -->
                <svg class="illustration-svg" width="550" height="420" viewBox="0 0 550 420" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Soft Gradients -->
                    <circle cx="275" cy="210" r="160" fill="url(#circleGrad)" opacity="0.15"/>
                    <circle cx="420" cy="120" r="60" fill="url(#cyanGrad)" opacity="0.1"/>
                    
                    <!-- Dashboard Grid Grid/Laptop Abstract -->
                    <rect x="75" y="100" width="380" height="230" rx="16" fill="#ffffff" stroke="rgba(30, 64, 175, 0.12)" stroke-width="4"/>
                    <rect x="75" y="100" width="380" height="45" rx="16" fill="#f8fafc" />
                    <circle cx="105" cy="122" r="6" fill="#ff5f56"/>
                    <circle cx="125" cy="122" r="6" fill="#ffbd2e"/>
                    <circle cx="145" cy="122" r="6" fill="#27c93f"/>
                    
                    <!-- Sidebar Mock -->
                    <rect x="95" y="165" width="80" height="15" rx="4" fill="#e2e8f0"/>
                    <rect x="95" y="195" width="80" height="15" rx="4" fill="#e2e8f0"/>
                    <rect x="95" y="225" width="80" height="15" rx="4" fill="#e2e8f0"/>
                    <rect x="95" y="255" width="80" height="15" rx="4" fill="url(#blueGrad)"/>

                    <!-- Analytical Card mock -->
                    <rect x="200" y="165" width="230" height="135" rx="12" fill="#ffffff" stroke="rgba(6, 182, 212, 0.08)" stroke-width="2"/>
                    <!-- Chart Graphic in SVG -->
                    <path d="M 220 270 L 250 220 L 280 240 L 310 190 L 340 210 L 370 175 L 400 210" stroke="url(#blueGrad)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="370" cy="175" r="7" fill="#3b82f6" stroke="#ffffff" stroke-width="2"/>
                    
                    <!-- Floating Certificate badge -->
                    <g transform="translate(380, 240)" class="animate-float">
                        <rect width="110" height="110" rx="16" fill="#ffffff" filter="url(#shadowFilter)" stroke="rgba(30, 64, 175, 0.08)"/>
                        <circle cx="55" cy="45" r="22" fill="url(#cyanGrad)"/>
                        <i class="fa-solid fa-certificate"></i>
                        <path d="M 45 45 L 52 52 L 67 37" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <text x="55" y="85" text-anchor="middle" fill="#0f172a" font-family="'Outfit', sans-serif" font-weight="bold" font-size="11">VERIFIED</text>
                    </g>
                    
                    <!-- Abstract Timeline representation -->
                    <g transform="translate(50, 310)">
                        <rect width="260" height="60" rx="12" fill="#ffffff" filter="url(#shadowFilter)" stroke="rgba(0,0,0,0.03)"/>
                        <circle cx="40" cy="30" r="14" fill="#10b981"/>
                        <path d="M 35 30 L 39 34 L 47 26" stroke="#ffffff" stroke-width="2"/>
                        <line x1="54" y1="30" x2="116" y2="30" stroke="#10b981" stroke-width="3"/>
                        <circle cx="130" cy="30" r="14" fill="#3b82f6"/>
                        <path d="M 125 30 L 129 34 L 137 26" stroke="#ffffff" stroke-width="2"/>
                        <line x1="144" y1="30" x2="206" y2="30" stroke="#cbd5e1" stroke-width="3" stroke-dasharray="4"/>
                        <circle cx="220" cy="30" r="14" fill="#cbd5e1"/>
                    </g>

                    <!-- Definitions -->
                    <defs>
                        <linearGradient id="circleGrad" x1="115" y1="50" x2="435" y2="370" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#3b82f6"/>
                            <stop offset="1" stop-color="#06b6d4"/>
                        </linearGradient>
                        <linearGradient id="blueGrad" x1="200" y1="165" x2="400" y2="280" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#1e40af"/>
                            <stop offset="1" stop-color="#3b82f6"/>
                        </linearGradient>
                        <linearGradient id="cyanGrad" x1="0" y1="0" x2="50" y2="50" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#06b6d4"/>
                            <stop offset="1" stop-color="#67e8f9"/>
                        </linearGradient>
                        <filter id="shadowFilter" x="-10" y="-10" width="150" height="150" filterUnits="userSpaceOnUse">
                            <dropShadow dx="0" dy="6" stdDeviation="8" flood-color="#1f2687" flood-opacity="0.08"/>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section class="py-5 bg-white border-top border-bottom border-light">
    <div class="container py-lg-4">
        <div class="text-center max-w-600 mx-auto mb-5">
            <h2 class="h1 text-dark display-font mb-3">Key Features of the Platform</h2>
            <p class="text-secondary">Explore the interactive modules built specifically for managing student career tracking.</p>
        </div>
        
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="glass-card h-100 p-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-3 bg-primary bg-opacity-10 text-primary mb-4 fs-3">
                        <i class="fa-solid fa-route"></i>
                    </div>
                    <h4 class="display-font text-dark mb-3">Progress Timeline</h4>
                    <p class="text-secondary small mb-0">
                        Track your internships through stages: Applied, Shortlisted, Interview, Selected, Ongoing, and Completed.
                    </p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="glass-card h-100 p-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-3 bg-cyan bg-opacity-10 text-cyan mb-4 fs-3">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <h4 class="display-font text-dark mb-3">Certificate Uploads</h4>
                    <p class="text-secondary small mb-0">
                        Upload your completion certificates securely. Provide live previews and allow administrators to verify them.
                    </p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="glass-card h-100 p-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-3 bg-emerald bg-opacity-10 text-emerald mb-4 fs-3">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h4 class="display-font text-dark mb-3">Admin Dashboard</h4>
                    <p class="text-secondary small mb-0">
                        Manage, filter, and review applications instantly. Generate CSV reports and see visual charts of active students.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Board -->
<section class="py-5 bg-light">
    <div class="container py-lg-4 text-center">
        <div class="row g-4 justify-content-center">
            <div class="col-md-3 col-6">
                <h3 class="display-4 fw-extrabold text-primary display-font mb-2">120+</h3>
                <p class="text-secondary small text-uppercase fw-semibold tracking-wider">Registered Students</p>
            </div>
            <div class="col-md-3 col-6">
                <h3 class="display-4 fw-extrabold text-cyan display-font mb-2">80+</h3>
                <p class="text-secondary small text-uppercase fw-semibold tracking-wider">Active Internships</p>
            </div>
            <div class="col-md-3 col-6">
                <h3 class="display-4 fw-extrabold text-emerald display-font mb-2">95%</h3>
                <p class="text-secondary small text-uppercase fw-semibold tracking-wider">Approval Rate</p>
            </div>
            <div class="col-md-3 col-6">
                <h3 class="display-4 fw-extrabold text-indigo display-font mb-2">35+</h3>
                <p class="text-secondary small text-uppercase fw-semibold tracking-wider">Recruiting Partners</p>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
