<?php
// Determine the base path based on current directory
// Allow base_path to be set externally (for files outside radiant folder)
if (!isset($base_path)) {
    $is_jobs_folder = strpos($_SERVER['PHP_SELF'], '/jobs/') !== false;
    $is_pages_folder = strpos($_SERVER['PHP_SELF'], '/pages/') !== false;
    $base_path = ($is_jobs_folder || $is_pages_folder) ? '../' : '';
}

// Set default page title if not provided
if (!isset($page_title)) {
    $page_title = 'Radiant Force HR';
}

// Helper function for homepage URL (since it's in root, not radiant folder)
function getHomeUrl($base_path) {
    // Always return the root path to radiantforcehumanresources.php
    if ($base_path == '../') {
        return '../../radiantforcehumanresources.php';
    } else {
        return 'radiantforcehumanresources.php';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>design/styles.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>design/jobs.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>design/cities.css">
</head>
<body>
    <!-- Header / Navigation -->
    <header id="header">
        <div class="container">
            <a href="<?php echo getHomeUrl($base_path); ?>" class="logo">
                <img src="<?php echo $base_path; ?>imagees/logo.png" alt="Radiant Force HR Logo">
                <span class="logo-text">Radiant Force</span>
            </a>
            <nav id="nav">
                <a href="<?php echo getHomeUrl($base_path); ?>" class="nav-link">Home</a>
                <a href="<?php echo $base_path; ?>jobs/jobs.php" class="nav-link">Jobs</a>
                <a href="<?php echo $base_path; ?>jobs/blog.php" class="nav-link">Blog</a>
                <a href="<?php echo $base_path; ?>jobs/faqs.php" class="nav-link">FAQs</a>
                <a href="<?php echo $base_path; ?>jobs/referral-program.php" class="nav-link">Referral</a>
                <a href="<?php echo $base_path; ?>jobs/testimonials.php" class="nav-link">Testimonials</a>
                <a href="<?php echo $base_path; ?>jobs/contact-form.php" class="nav-link nav-cta">Contact</a>
            </nav>
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- PAGE CONTENT GOES HERE -->
    <?php
    // Include the page content
    if (isset($page_content_file)) {
        include $page_content_file;
    }
    ?>
    <!-- END PAGE CONTENT -->

    <!-- Footer with Contact -->
    <footer id="contact">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-about">
                        <div class="footer-logo">
                            <img src="<?php echo $base_path; ?>imagees/logo.png" alt="Radiant Force HR Logo">
                            <span>Radiant Force HR</span>
                        </div>
                        <p class="footer-tagline">Your Partner in Talent Excellence</p>
                        <p class="footer-description">Connecting exceptional talent with leading organizations through strategic recruitment and comprehensive HR solutions.</p>
                    </div>

                    <div class="footer-contact">
                        <h4>Contact Us</h4>
                        <div class="footer-contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <div>
                                <p>Ancar Motors Inc., 7th Floor Unit B<br>Azure Business Center, EDSA, Katipunan<br>Quezon City</p>
                            </div>
                        </div>
                        <div class="footer-contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <div>
                                <p><a href="tel:+6383726603">(8) 372-6603</a> | <a href="tel:+6387000193">8-7000-1932</a></p>
                            </div>
                        </div>
                        <div class="footer-contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <div>
                                <p><a href="mailto:radiantforcehumanresources@gmail.com">radiantforcehumanresources@gmail.com</a></p>
                            </div>
                        </div>
                        <div class="footer-contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                            <div>
                                <p><strong>Hours</strong><br>Mon - Sat: 8:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <div class="footer-newsletter">
                        <h4>Newsletter</h4>
                        <p style="font-size: 14px; color: #999; margin-bottom: 15px;">Subscribe to get job updates and HR insights</p>
                        <form method="POST" action="<?php echo $base_path; ?>admin/api/newsletter-subscribe.php" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                            <input type="hidden" name="add_subscriber" value="1">
                            <input type="hidden" name="subscription_type" value="all">
                            <input type="email" name="email" placeholder="Your email" required style="flex: 1; min-width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;">
                            <button type="submit" style="padding: 10px 20px; background: #1e3a5f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 13px; white-space: nowrap;">Subscribe</button>
                        </form>
                    </div>

                    <div class="footer-map">
                        <h4>Find Us</h4>
                        <div class="footer-map-wrapper">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.3847234567!2d121.07789!3d14.6387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7f3b3b3b3b3%3A0x3b3b3b3b3b3b3b3b!2sAzure%20Business%20Center%2C%20EDSA%2C%20Katipunan%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1234567890"
                                width="100%" 
                                height="200" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <div class="footer-links">
                        <a href="<?php echo $base_path; ?>jobs/faqs.php">FAQs</a>
                        <span class="separator">|</span>
                        <a href="<?php echo $base_path; ?>jobs/referral-program.php">Referral Program</a>
                        <span class="separator">|</span>
                        <a href="<?php echo $base_path; ?>jobs/testimonials.php">Testimonials</a>
                    </div>
                    <div class="footer-copyright">
                        <p>&copy; 2025 Radiant Force Human Resources. All rights reserved.</p>
                        <p style="margin-top: 5px; font-size: 0.9em; opacity: 0.8;">Developer: Ronie R. Pactol</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo $base_path; ?>javascrpt/script.js"></script>
</body>
</html>
