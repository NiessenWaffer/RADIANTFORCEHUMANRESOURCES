<?php
// About page content
?>

<style>
    .page-hero {
        background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 73, 94, 0.85) 100%), url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=1920&q=80') center/cover no-repeat;
        color: white;
        padding: 80px 20px;
        text-align: center;
    }
    .page-hero h1 {
        font-size: 48px;
        margin-bottom: 15px;
        font-weight: 700;
    }
    .page-hero p {
        font-size: 18px;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }
    @media (max-width: 768px) {
        .page-hero { padding: 40px 20px; }
        .page-hero h1 { font-size: 36px; }
        .page-hero p { font-size: 15px; }
    }
    .page-content { max-width: 1200px; margin: 0 auto; padding: 60px 20px; }
    @media (max-width: 768px) {
        .page-content { padding: 40px 20px; }
    }
    .content-section { margin-bottom: 60px; }
    .content-section h2 { font-size: 32px; color: #2c3e50; margin-bottom: 20px; font-weight: 700; }
    .content-section p { font-size: 16px; line-height: 1.8; color: #475569; margin-bottom: 15px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin: 40px 0; }
    .stat-card { text-align: center; padding: 30px; background: #f8fafc; border-radius: 8px; }
    .stat-number { font-size: 36px; font-weight: 700; color: #5dd3e0; }
    .stat-label { font-size: 14px; color: #64748b; margin-top: 10px; }
    .timeline { position: relative; padding: 20px 0; }
    .timeline-item { display: flex; gap: 20px; margin-bottom: 30px; }
    .timeline-dot { width: 12px; height: 12px; background: #5dd3e0; border-radius: 50%; margin-top: 8px; flex-shrink: 0; }
    .timeline-content h3 { color: #2c3e50; font-weight: 600; margin-bottom: 5px; }
    .timeline-content p { color: #64748b; font-size: 14px; }
</style>

<div class="page-hero">
    <h1>About Radiant Force HR</h1>
    <p>Your trusted partner in recruitment and HR consulting excellence</p>
</div>

<div class="page-content">
    <div class="content-section">
        <h2>Who We Are</h2>
        <p>Radiant Force Human Resources is a premier recruitment and HR consulting firm in the Philippines, dedicated to bridging the gap between exceptional talent and forward-thinking organizations. Founded on the principles of integrity, excellence, and partnership, we have established ourselves as a trusted advisor to businesses across diverse industries.</p>
        <p>Our deep understanding of the Philippine job market, combined with our commitment to personalized service, enables us to deliver recruitment solutions that drive sustainable business growth. We believe that the right talent can transform organizations, and the right opportunity can transform lives.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">500+</div>
            <div class="stat-label">Successful Placements</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">95%</div>
            <div class="stat-label">Client Satisfaction Rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">10+</div>
            <div class="stat-label">Industries Served</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">15+</div>
            <div class="stat-label">Years of Experience</div>
        </div>
    </div>

    <div class="content-section">
        <h2>Our Mission</h2>
        <p>To empower organizations with the right talent and guide professionals toward fulfilling careers through strategic recruitment, comprehensive HR solutions, and unwavering commitment to excellence.</p>
    </div>

    <div class="content-section">
        <h2>Our Vision</h2>
        <p>To be the Philippines' most trusted HR partner, recognized for transforming businesses through exceptional talent acquisition and innovative workforce solutions that shape the future of work.</p>
    </div>

    <div class="content-section">
        <h2>Our Core Values</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>Integrity</h3>
                    <p>We conduct all business with honesty, transparency, and ethical practices. Trust is the foundation of every relationship we build.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>Excellence</h3>
                    <p>We strive for the highest standards in everything we do, from candidate screening to client service delivery.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>Partnership</h3>
                    <p>We view our clients and candidates as partners, working collaboratively to achieve mutual success and growth.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>Innovation</h3>
                    <p>We embrace new technologies and methodologies to continuously improve our recruitment strategies and services.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>Dedication</h3>
                    <p>We are committed to creating meaningful connections that last beyond individual placements.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h2>Our Commitment</h2>
        <p><strong>To Our Clients:</strong> We deliver quality candidates who align with your organizational culture and business objectives. Our rigorous screening process, market insights, and dedicated support ensure successful placements that contribute to your long-term success.</p>
        <p><strong>To Our Candidates:</strong> We provide confidential, professional guidance throughout your career journey. From resume optimization to interview preparation and salary negotiation, we advocate for your best interests while matching you with opportunities that align with your goals.</p>
    </div>
</div>
