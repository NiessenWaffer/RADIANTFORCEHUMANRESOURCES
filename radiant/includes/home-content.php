<?php
// PWA Meta Tags
$pwa_meta = '
    <link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Radiant HR">
    <link rel="apple-touch-icon" href="imagees/logo.png">
    <meta name="description" content="Radiant Force HR - Recruitment and HR Consulting Services in the Philippines. Connect with top talent and grow your business.">
    <meta name="theme-color" content="#2c3e50">
';
?>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <h1>Recruitment and HR Consulting Services Firm in the Philippines</h1>
        <p>Connecting exceptional talent with leading organizations through strategic recruitment and comprehensive HR solutions.</p>
        <div class="hero-cta">
            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>jobs/cities.php" class="btn-hero btn-hero-primary">Find Your Dream Job</a>
            <a href="#services" class="btn-hero btn-hero-secondary">Our Services</a>
        </div>
    </div>
</section>

<!-- Core Services Section -->
<section id="services" class="services">
    <div class="container">
        <div class="section-header">
            <div class="section-label">What We Offer</div>
            <h2>Our Services</h2>
            <p class="section-subtitle">Comprehensive recruitment and HR solutions tailored to your career goals and organizational needs.</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="service-card-content">
                    <h3>Recruitment</h3>
                    <p>Comprehensive hiring solutions for rank-and-file, middle management, and specialized positions across all industries.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                </div>
                <div class="service-card-content">
                    <h3>Executive Search</h3>
                    <p>Targeted search and placement for senior management, C-level executives, and niche leadership roles.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>
                <div class="service-card-content">
                    <h3>HR Consulting</h3>
                    <p>Strategic HR services including salary benchmarking, organizational development, and customized training programs.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="service-card-content">
                    <h3>Staff Leasing</h3>
                    <p>Flexible workforce solutions with staff augmentation and comprehensive payroll management services.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section id="jobs" class="cta">
    <div class="container">
        <div class="cta-content">
            <h2>Looking for new opportunities?</h2>
            <p>Our dedicated consultants will help match you with your ideal employer. Take the next step in your career journey with confidence.</p>
            <div class="cta-features">
                <div class="cta-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Free Career Consultation</span>
                </div>
                <div class="cta-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Confidential Process</span>
                </div>
                <div class="cta-feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Expert Guidance</span>
                </div>
            </div>
            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>jobs/cities.php" class="btn-primary">Apply Now</a>
        </div>
    </div>
</section>

<!-- Key Selling Points -->
<section class="selling-points">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Our Competitive Edge</div>
            <h2>Why Choose Radiant Force HR</h2>
            <p class="section-subtitle">We combine industry expertise with personalized service, innovative technology, and a proven track record to deliver exceptional recruitment results.</p>
        </div>
        <div class="points-grid">
            <div class="point">
                <div class="icon-small">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <div class="point-content">
                    <h3>Proven Track Record</h3>
                    <p>Over 500 successful placements across diverse industries with a 95% client satisfaction rate, demonstrating our consistent ability to match the right talent with the right opportunities.</p>
                </div>
            </div>

            <div class="point">
                <div class="icon-small">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="point-content">
                    <h3>Personalized Approach</h3>
                    <p>Every client and candidate receives tailored attention. We take time to understand your unique needs, culture, and goals to ensure perfect alignment in every placement.</p>
                </div>
            </div>

            <div class="point">
                <div class="icon-small">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div class="point-content">
                    <h3>Industry Expertise & Deep Network</h3>
                    <p>Specialized knowledge across 10+ industries with an extensive talent network built over years of relationship building and market intelligence gathering.</p>
                </div>
            </div>

            <div class="point">
                <div class="icon-small">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="point-content">
                    <h3>Transparent & Efficient Process</h3>
                    <p>Clear communication at every stage with streamlined workflows and regular updates. Our reliable process ensures you're always informed and confident in our progress.</p>
                </div>
            </div>

            <div class="point">
                <div class="icon-small">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </div>
                <div class="point-content">
                    <h3>Strong Lasting Relationships</h3>
                    <p>We build genuine partnerships with both clients and candidates, fostering trust and loyalty that extends beyond individual placements to create long-term value.</p>
                </div>
            </div>

            <div class="point">
                <div class="icon-small">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </div>
                <div class="point-content">
                    <h3>Quality & Long-Term Partnership</h3>
                    <p>Rigorous screening, thorough reference checks, and cultural fit assessment ensure quality placements. We're committed to your success beyond the hire.</p>
                </div>
            </div>

            <div class="point">
                <div class="icon-small">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <div class="point-content">
                    <h3>Technology & Data-Driven Strategies</h3>
                    <p>Leveraging advanced recruitment technology, market analytics, and data-driven insights to identify, assess, and secure the best talent efficiently and effectively.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Us -->
<section id="about" class="about">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Who We Are</div>
            <h2>About Radiant Force Human Resources</h2>
        </div>
        <div class="about-content">
            <div class="about-intro">
                <p class="about-lead">Radiant Force Human Resources is a premier recruitment and HR consulting firm in the Philippines, dedicated to bridging the gap between exceptional talent and forward-thinking organizations.</p>
                <p>Founded on the principles of integrity, excellence, and partnership, we have established ourselves as a trusted advisor to businesses across diverse industries. Our deep understanding of the Philippine job market, combined with our commitment to personalized service, enables us to deliver recruitment solutions that drive sustainable business growth.</p>
            </div>

            <div class="about-grid">
                <div class="about-card">
                    <div class="about-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5"></path>
                            <path d="M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To empower organizations with the right talent and guide professionals toward fulfilling careers through strategic recruitment, comprehensive HR solutions, and unwavering commitment to excellence.</p>
                </div>

                <div class="about-card">
                    <div class="about-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                        </svg>
                    </div>
                    <h3>Our Values</h3>
                    <p>Integrity in every interaction, excellence in service delivery, partnership with our clients, innovation in recruitment strategies, and dedication to creating meaningful connections that last.</p>
                </div>

                <div class="about-card">
                    <div class="about-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                    <h3>Our Vision</h3>
                    <p>To be the Philippines' most trusted HR partner, recognized for transforming businesses through exceptional talent acquisition and innovative workforce solutions that shape the future of work.</p>
                </div>
            </div>

            <div class="about-commitment">
                <h3>Our Commitment</h3>
                <div class="commitment-grid">
                    <div class="commitment-item">
                        <h4>To Our Clients</h4>
                        <p>We deliver quality candidates who align with your organizational culture and business objectives. Our rigorous screening process, market insights, and dedicated support ensure successful placements that contribute to your long-term success.</p>
                    </div>
                    <div class="commitment-item">
                        <h4>To Our Candidates</h4>
                        <p>We provide confidential, professional guidance throughout your career journey. From resume optimization to interview preparation and salary negotiation, we advocate for your best interests while matching you with opportunities that align with your goals.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Meet Our Team</div>
            <h2>Leadership & Consultants</h2>
            <p class="section-subtitle">Our experienced team of HR professionals is dedicated to connecting talent with opportunity.</p>
        </div>
        <div class="team-grid">
            <div class="team-member">
                <div class="team-photo">
                    <img src="https://via.placeholder.com/300x300/1e3a5f/ffffff?text=Photo" alt="Team Member">
                    <div class="team-overlay">
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Email">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-info">
                    <h3>Wilmer Pena</h3>
                    <p class="team-role">Supervisor</p>
                    <p class="team-bio">With over 15 years in recruitment and HR consulting, Maria leads our strategic vision and client partnerships across diverse industries.</p>
                </div>
            </div>

            <div class="team-member">
                <div class="team-photo">
                    <img src="https://via.placeholder.com/300x300/1e3a5f/ffffff?text=Photo" alt="Team Member">
                    <div class="team-overlay">
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Email">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-info">
                    <h3>Daday De Luna</h3>
                    <p class="team-role">Manager</p>
                    <p class="team-bio">Specializing in executive search and middle management placements, Carlos brings 12 years of expertise in matching top talent with leading organizations.</p>
                </div>
            </div>

            <div class="team-member">
                <div class="team-photo">
                    <img src="https://via.placeholder.com/300x300/1e3a5f/ffffff?text=Photo" alt="Team Member">
                    <div class="team-overlay">
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Email">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-info">
                    <h3>Jean</h3>
                    <p class="team-role">HR Officer</p>
                    <p class="team-bio">Jennifer specializes in organizational development, training programs, and HR strategy, helping businesses build stronger workforce capabilities.</p>
                </div>
            </div>

            <div class="team-member">
                <div class="team-photo">
                    <img src="https://via.placeholder.com/300x300/1e3a5f/ffffff?text=Photo" alt="Team Member">
                    <div class="team-overlay">
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Email">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-info">
                    <h3>Glen</h3>
                    <p class="team-role">Boss</p>
                    <p class="team-bio">Focused on IT, engineering, and technical roles, Michael leverages his industry knowledge to connect specialized talent with innovative companies.</p>
                </div>
            </div>
        </div>
    </div>
</section>
