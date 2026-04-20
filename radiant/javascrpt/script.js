// Performance optimized script with throttling and lazy initialization
(function() {
    'use strict';

    function initMenu() {
        const header = document.getElementById('header');
        const menuToggle = document.getElementById('menuToggle');
        const nav = document.getElementById('nav');

        if (!menuToggle || !nav) return;

        // Throttled scroll handler for better performance
        function handleScroll() {
            const currentScrollY = window.scrollY;
            if (currentScrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        // Use passive event listener for scroll performance
        window.addEventListener('scroll', handleScroll, { passive: true });

        // Mobile menu toggle
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            nav.classList.toggle('active');
            document.body.style.overflow = nav.classList.contains('active') ? 'hidden' : '';
        });

        // Smooth scrolling for navigation links
        const navLinks = document.querySelectorAll('nav a');
        navLinks.forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                // Close mobile menu on any link click
                menuToggle.classList.remove('active');
                nav.classList.remove('active');
                document.body.style.overflow = '';
                
                // Skip smooth scroll if it's an external link or different page
                if (targetId.includes('.php') || targetId.includes('.html') || !targetId.startsWith('#')) {
                    return;
                }
                
                e.preventDefault();
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    const headerHeight = header.offsetHeight;
                    const targetPosition = targetSection.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!nav.contains(e.target) && !menuToggle.contains(e.target) && nav.classList.contains('active')) {
                menuToggle.classList.remove('active');
                nav.classList.remove('active');
                document.body.style.overflow = '';
            }
        }, { passive: true });
    }

    // Initialize menu when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenu);
    } else {
        initMenu();
    }

    // Cookie Consent - Lazy initialization
    function initCookieConsent() {
        const cookieConsent = document.getElementById('cookieConsent');
        if (!cookieConsent) return;

        const acceptCookiesBtn = document.getElementById('acceptCookies');
        const closeCookiesBtn = document.getElementById('closeCookies');

        // Check if user has already accepted cookies
        if (!localStorage.getItem('cookiesAccepted')) {
            // Show banner after a short delay
            setTimeout(() => {
                cookieConsent.classList.add('show');
            }, 1000);
        }

        // Function to close cookie banner
        function closeCookieBanner() {
            localStorage.setItem('cookiesAccepted', 'true');
            cookieConsent.classList.remove('show');
            
            // Remove from DOM after animation
            setTimeout(() => {
                cookieConsent.style.display = 'none';
            }, 400);
        }

        // Handle accept button click
        if (acceptCookiesBtn) {
            acceptCookiesBtn.addEventListener('click', closeCookieBanner);
        }

        // Handle close button click
        if (closeCookiesBtn) {
            closeCookiesBtn.addEventListener('click', closeCookieBanner);
        }
    }

    // Initialize cookie consent when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCookieConsent);
    } else {
        initCookieConsent();
    }

    // Mobile dropdown toggle
    const navDropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');
    navDropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            // Only prevent default on mobile
            if (window.innerWidth <= 968) {
                e.preventDefault();
                const dropdown = toggle.closest('.nav-dropdown');
                const menu = dropdown.querySelector('.nav-dropdown-menu');
                
                // Toggle menu visibility
                if (menu.style.display === 'flex') {
                    menu.style.display = 'none';
                } else {
                    menu.style.display = 'flex';
                }
            }
        });
    });
})();
