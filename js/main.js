document.addEventListener('DOMContentLoaded', () => {

    /* ==========================================================================
       1. PRELOADER
       ========================================================================== */
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 600); // Small delay for a smooth transition
        });
        
        // Safety fallback if load event doesn't fire fast enough
        setTimeout(() => {
            preloader.style.opacity = '0';
            preloader.style.visibility = 'hidden';
        }, 3000);
    }

    /* ==========================================================================
       2. LIGHT/DARK THEME SYSTEM
       ========================================================================== */
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // Check for saved theme, default to dark
    let savedTheme = localStorage.getItem('theme');
    
    // If there is no saved theme, or it was saved as light previously, default to dark
    if (!savedTheme || savedTheme === 'light') {
        savedTheme = 'dark';
        localStorage.setItem('theme', 'dark');
    }
    
    htmlElement.setAttribute('data-theme', savedTheme);

    // Toggle theme function
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Set attribute and persist
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Re-trigger animations where needed or update background styling
            triggerThemeTransitionGlow();
        });
    }

    // Optional visual glow trigger during transition
    function triggerThemeTransitionGlow() {
        document.body.style.transition = 'background-color 0.5s ease, color 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 500);
    }

    /* ==========================================================================
       3. NAVIGATION HEADER EFFECTS
       ========================================================================== */
    const header = document.getElementById('navbar-header');
    
    const handleHeaderScroll = () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };
    
    window.addEventListener('scroll', handleHeaderScroll);
    handleHeaderScroll(); // Trigger immediately in case page is refreshed while scrolled

    /* ==========================================================================
       4. MOBILE MENU INTERACTION
       ========================================================================== */
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');

    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking a nav link
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }

    /* ==========================================================================
       5. ACTIVE LINK HIGHLIGHTING ON SCROLL
       ========================================================================== */
    const sections = document.querySelectorAll('section[id]');
    
    const highlightActiveNavLink = () => {
        const scrollPosition = window.scrollY + 100; // Offset for header height

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    };

    window.addEventListener('scroll', highlightActiveNavLink);
    highlightActiveNavLink();

    /* ==========================================================================
       6. TYPED.JS CONFIGURATION
       ========================================================================== */
    const typedTarget = document.getElementById('typed-text');
    if (typedTarget && typeof Typed !== 'undefined') {
        new Typed('#typed-text', {
            strings: [
                'Python & SQL Querying.',
                'Interactive Dashboards.',
                'Data Cleaning & ETL.',
                'Data-Driven Decision Making.',
                'Visual Analytics storytelling.'
            ],
            typeSpeed: 60,
            backSpeed: 40,
            backDelay: 1800,
            loop: true,
            showCursor: true,
            cursorChar: '|'
        });
    }

    /* ==========================================================================
       7. AOS (ANIMATE ON SCROLL) INITIALIZATION
       ========================================================================== */
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            mirror: false,
            anchorPlacement: 'top-bottom',
            offset: 80
        });
    }

    /* ==========================================================================
       8. SCROLL TO TOP BUTTON
       ========================================================================== */
    const scrollTopBtn = document.getElementById('scroll-top');
    
    if (scrollTopBtn) {
        const handleScrollTopVisibility = () => {
            if (window.scrollY > 400) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        };

        window.addEventListener('scroll', handleScrollTopVisibility);
        handleScrollTopVisibility();

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /* ==========================================================================
       9. CONTACT FORM INTERACTION & VALIDATION
       ========================================================================== */
    const contactForm = document.getElementById('contact-form');
    const formStatusMsg = document.getElementById('form-status-msg');

    if (contactForm && formStatusMsg) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('form-name').value.trim();
            const email = document.getElementById('form-email').value.trim();
            const subject = document.getElementById('form-subject').value.trim();
            const message = document.getElementById('form-message').value.trim();
            const submitBtn = document.getElementById('btn-submit-contact');
            
            // Validate basic values
            if (!name || !email || !subject || !message) {
                showStatus('Please fill in all required fields.', 'error');
                return;
            }

            // Simple email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showStatus('Please enter a valid email address.', 'error');
                return;
            }

            // Mock success state transition
            if (submitBtn) {
                const origBtnHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="btn-text">Sending...</span> <i class="fa-solid fa-circle-notch fa-spin btn-icon"></i>`;
                
                // Simulate network latency (1.5 seconds)
                setTimeout(() => {
                    showStatus(`Thank you, ${name}! Your message has been sent successfully.`, 'success');
                    contactForm.reset();
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origBtnHtml;
                    
                    // Clear success message after 5 seconds
                    setTimeout(() => {
                        formStatusMsg.className = 'form-status-msg';
                        formStatusMsg.style.display = 'none';
                        formStatusMsg.textContent = '';
                    }, 5000);
                }, 1500);
            }
        });
    }

    function showStatus(text, type) {
        formStatusMsg.textContent = text;
        formStatusMsg.className = `form-status-msg ${type}`;
        formStatusMsg.style.display = 'block';
    }
});
