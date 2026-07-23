<?php
require_once __DIR__ . '/includes/header.php';

$success_msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $success_msg = "Thank you, " . htmlspecialchars($name) . ". Your message has been received! Our support team will respond shortly.";
    } else {
        setFlashMessage('error', 'Please fill in all the required form fields.');
    }
}
?>

<!-- Contact Section -->
<section class="py-5">
    <div class="container py-lg-4">
        <div class="text-center max-w-600 mx-auto mb-5 fade-in-up">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-3">✉️ Get in Touch</span>
            <h2 class="display-4 text-dark display-font mb-3">Contact Support</h2>
            <p class="text-secondary">Have questions or need assistance with your credentials? Reach out to the placement department.</p>
        </div>

        <?php if ($success_msg): ?>
            <div class="alert alert-success glass-card border-success border-opacity-25 mb-4 p-4 fade-in-up" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fa-solid fa-envelope-circle-check text-success fs-3 me-3"></i>
                    <div>
                        <h5 class="alert-heading fw-bold display-font">Message Sent Successfully!</h5>
                        <p class="mb-0 small text-muted"><?= $success_msg ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="glass-card p-4 p-md-5">
                    <h3 class="display-font text-dark mb-4">Send a Message</h3>
                    
                    <form action="contact.php" method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Assistance with Certificate Upload" required>
                                <div class="invalid-feedback">Please provide a subject title.</div>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold">Your Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your query here..." required></textarea>
                                <div class="invalid-feedback">Please write your message.</div>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gradient w-100 py-3">
                                    <i class="fa-regular fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Sidebar details -->
            <div class="col-lg-5">
                <div class="glass-card p-4 p-md-5 bg-white h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="display-font text-dark mb-4">Placement Cell</h3>
                        <p class="text-secondary small mb-4">
                            For urgent inquiries regarding database enrollment, profile updates, or password resets, you can visit the department coordinator office during working hours (9:00 AM - 5:00 PM).
                        </p>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="fa-solid fa-location-dot fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 display-font text-dark">Office Address</h6>
                                    <p class="text-secondary small mb-0">NIET Campus, Block-A, Placement Cell, Greater Noida, UP - 201306</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-3">
                                <div class="p-2 rounded-3 bg-cyan bg-opacity-10 text-cyan me-3">
                                    <i class="fa-solid fa-phone fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 display-font text-dark">Phone Lines</h6>
                                    <p class="text-secondary small mb-0">+91 (120) 2320 001<br>+91 98765 43210</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="p-2 rounded-3 bg-emerald bg-opacity-10 text-emerald me-3">
                                    <i class="fa-solid fa-envelope fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 display-font text-dark">Support Email</h6>
                                    <p class="text-secondary small mb-0">placements@niet.edu.in<br>support@dits-tracker.edu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-top pt-4">
                        <h6 class="fw-bold text-dark display-font mb-2">Connect Digitally</h6>
                        <div class="social-icons">
                            <a href="#" class="btn btn-outline-dark border-secondary border-opacity-25 rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width:36px; height:36px;"><i class="fa-brands fa-linkedin-in text-primary"></i></a>
                            <a href="#" class="btn btn-outline-dark border-secondary border-opacity-25 rounded-circle p-2 d-inline-flex align-items-center justify-content-center ms-2" style="width:36px; height:36px;"><i class="fa-brands fa-github text-dark"></i></a>
                            <a href="#" class="btn btn-outline-dark border-secondary border-opacity-25 rounded-circle p-2 d-inline-flex align-items-center justify-content-center ms-2" style="width:36px; height:36px;"><i class="fa-brands fa-twitter text-info"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
