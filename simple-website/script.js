document.addEventListener('DOMContentLoaded', () => {
    
    // ==========================================
    // 1. Theme Toggle (Light / Dark)
    // ==========================================
    const themeToggle = document.getElementById('themeToggle');
    const htmlElement = document.documentElement;
    
    // Check saved theme or system preferences
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme) {
        htmlElement.setAttribute('data-theme', savedTheme);
    } else if (systemPrefersDark) {
        htmlElement.setAttribute('data-theme', 'dark');
    } else {
        htmlElement.setAttribute('data-theme', 'light');
    }

    themeToggle.addEventListener('click', () => {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Dynamic aesthetic transition effect
        themeToggle.style.transform = 'rotate(360deg)';
        setTimeout(() => {
            themeToggle.style.transform = 'none';
        }, 300);
    });

    // ==========================================
    // 2. Mobile Responsive Hamburger Menu
    // ==========================================
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navLinks = document.getElementById('navLinks');
    const navItems = document.querySelectorAll('.nav-links a');

    hamburgerBtn.addEventListener('click', () => {
        hamburgerBtn.classList.toggle('active');
        navLinks.classList.toggle('active');
    });

    // Close menu when clicking a link
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            hamburgerBtn.classList.remove('active');
            navLinks.classList.remove('active');
        });
    });

    // ==========================================
    // 3. Scroll Reveal Animation (Intersection Observer)
    // ==========================================
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // Unobserve once revealed to keep animations clean and persistent
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(element => {
        revealObserver.observe(element);
    });

    // ==========================================
    // 4. Contact Form Validation and Submission
    // ==========================================
    const contactForm = document.getElementById('contactForm');
    const formStatus = document.getElementById('formStatus');
    const btnSubmit = document.getElementById('btnSubmit');

    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const nameVal = document.getElementById('name').value.trim();
        const emailVal = document.getElementById('email').value.trim();
        const messageVal = document.getElementById('message').value.trim();
        
        // Basic feedback validation
        if (!nameVal || !emailVal || !messageVal) {
            showStatus('Please fill in all fields.', 'error');
            return;
        }

        // Show loading state
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = 'Sending... <i class="fa-solid fa-spinner fa-spin"></i>';

        // Simulate API call
        setTimeout(() => {
            showStatus('Thank you! Your message has been sent successfully.', 'success');
            contactForm.reset();
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = 'Send Message <i class="fa-solid fa-paper-plane"></i>';
        }, 1500);
    });

    function showStatus(message, type) {
        formStatus.textContent = message;
        formStatus.className = `form-status ${type}`;
        
        // Auto-hide error status after 5s, keep success visible longer
        if (type === 'error') {
            setTimeout(() => {
                formStatus.style.display = 'none';
            }, 5000);
        }
    }

    // ==========================================
    // 5. Active Link Tracking on Scroll
    // ==========================================
    const sections = document.querySelectorAll('section');
    
    window.addEventListener('scroll', () => {
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            // Detect which section occupies the major part of the screen
            if (window.scrollY >= (sectionTop - sectionHeight / 3)) {
                currentSection = section.getAttribute('id');
            }
        });

        navItems.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');
            }
        });
    });

});
