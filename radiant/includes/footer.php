    <!-- Footer with Contact -->
    <footer id="contact">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-about">
                        <div class="footer-logo">
                            <img src="<?php echo $is_jobs_folder ? '../imagees/logo.png' : 'imagees/logo.png'; ?>" alt="Radiant Force HR Logo">
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
                        <form method="POST" action="<?php echo $is_jobs_folder ? '../admin/api/newsletter-subscribe.php' : '../admin/api/newsletter-subscribe.php'; ?>" style="display: flex; flex-direction: column; gap: 10px;">
                            <input type="hidden" name="add_subscriber" value="1">
                            <input type="email" name="email" placeholder="Your email" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <select name="subscription_type" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="all">All Updates</option>
                                <option value="jobs">Jobs Only</option>
                                <option value="news">News Only</option>
                            </select>
                            <button type="submit" style="padding: 10px; background: #1e3a5f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <div class="footer-links">
                        <a href="<?php echo $is_jobs_folder ? 'faqs.php' : '../jobs/faqs.php'; ?>">FAQs</a>
                        <span class="separator">|</span>
                        <a href="<?php echo $is_jobs_folder ? 'referral-program.php' : '../jobs/referral-program.php'; ?>">Referral Program</a>
                        <span class="separator">|</span>
                        <a href="<?php echo $is_jobs_folder ? 'testimonials.php' : '../jobs/testimonials.php'; ?>">Testimonials</a>
                    </div>
                    <div class="footer-copyright">
                        <p>&copy; 2025 Radiant Force Human Resources. All rights reserved.</p>
                        <p style="margin-top: 5px; font-size: 0.9em; opacity: 0.8;">Developer: Ronie R. Pactol</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo $is_jobs_folder ? '../javascrpt/script.js' : '../javascrpt/script.js'; ?>"></script>
</body>
</html>
