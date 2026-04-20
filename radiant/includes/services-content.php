<?php
// Services page content
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
    .services-detailed { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin: 40px 0; }
    .service-detail-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 30px; transition: all 0.3s; }
    .service-detail-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.1); transform: translateY(-5px); }
    .service-detail-card .icon { font-size: 40px; margin-bottom: 15px; }
    .service-detail-card h3 { font-size: 20px; color: #2c3e50; margin-bottom: 15px; font-weight: 700; }
    .service-detail-card p { color: #64748b; line-height: 1.6; font-size: 14px; }
    .service-detail-card ul { margin-top: 15px; padding-left: 20px; }
    .service-detail-card li { color: #64748b; margin-bottom: 8px; font-size: 14px; }
    .content-section { margin-bottom: 60px; }
    .content-section h2 { font-size: 32px; color: #2c3e50; margin-bottom: 20px; font-weight: 700; }
    .content-section p { font-size: 16px; line-height: 1.8; color: #475569; margin-bottom: 15px; }
</style>

<div class="page-hero">
    <h1>Our Services</h1>
    <p>Comprehensive recruitment and HR solutions tailored to your needs</p>
</div>

<div class="page-content">
    <div class="content-section">
        <h2>What We Offer</h2>
        <p>At Radiant Force HR, we provide a comprehensive suite of recruitment and HR consulting services designed to meet the unique needs of organizations at every stage of growth. Our solutions are tailored, scalable, and results-driven.</p>
    </div>

    <div class="services-detailed">
        <div class="service-detail-card">
            <div class="icon">👥</div>
            <h3>Recruitment Services</h3>
            <p>Comprehensive hiring solutions for all organizational levels.</p>
            <ul>
                <li>Rank-and-file positions</li>
                <li>Middle management roles</li>
                <li>Specialized positions</li>
                <li>Multi-industry expertise</li>
                <li>Fast turnaround times</li>
            </ul>
        </div>

        <div class="service-detail-card">
            <div class="icon">🎯</div>
            <h3>Executive Search</h3>
            <p>Targeted search and placement for senior leadership roles.</p>
            <ul>
                <li>C-level executives</li>
                <li>Senior management</li>
                <li>Niche leadership roles</li>
                <li>Confidential searches</li>
                <li>Market intelligence</li>
            </ul>
        </div>

        <div class="service-detail-card">
            <div class="icon">📊</div>
            <h3>HR Consulting</h3>
            <p>Strategic HR services to strengthen your organization.</p>
            <ul>
                <li>Salary benchmarking</li>
                <li>Organizational development</li>
                <li>Training programs</li>
                <li>HR strategy</li>
                <li>Policy development</li>
            </ul>
        </div>

        <div class="service-detail-card">
            <div class="icon">🤝</div>
            <h3>Staff Leasing</h3>
            <p>Flexible workforce solutions for your business needs.</p>
            <ul>
                <li>Staff augmentation</li>
                <li>Temporary staffing</li>
                <li>Payroll management</li>
                <li>Flexible arrangements</li>
                <li>Cost-effective solutions</li>
            </ul>
        </div>

        <div class="service-detail-card">
            <div class="icon">📋</div>
            <h3>Resume Screening</h3>
            <p>Expert candidate evaluation and assessment.</p>
            <ul>
                <li>Skill assessment</li>
                <li>Experience verification</li>
                <li>Cultural fit analysis</li>
                <li>Reference checks</li>
                <li>Background verification</li>
            </ul>
        </div>

        <div class="service-detail-card">
            <div class="icon">💼</div>
            <h3>Career Consulting</h3>
            <p>Professional guidance for career advancement.</p>
            <ul>
                <li>Resume optimization</li>
                <li>Interview preparation</li>
                <li>Salary negotiation</li>
                <li>Career planning</li>
                <li>Professional development</li>
            </ul>
        </div>
    </div>

    <div class="content-section">
        <h2>Our Process</h2>
        <p>We follow a structured, transparent process to ensure the best outcomes for both clients and candidates:</p>
        <ol style="font-size: 16px; line-height: 1.8; color: #475569;">
            <li><strong>Discovery:</strong> We understand your needs, culture, and objectives through detailed consultations.</li>
            <li><strong>Sourcing:</strong> We leverage our extensive network and advanced tools to identify qualified candidates.</li>
            <li><strong>Screening:</strong> We conduct rigorous screening, interviews, and assessments to ensure quality matches.</li>
            <li><strong>Presentation:</strong> We present pre-qualified candidates with detailed profiles and recommendations.</li>
            <li><strong>Placement:</strong> We facilitate interviews, negotiations, and final placement.</li>
            <li><strong>Follow-up:</strong> We provide ongoing support to ensure successful integration and long-term satisfaction.</li>
        </ol>
    </div>

    <div class="content-section">
        <h2>Why Choose Our Services</h2>
        <ul style="font-size: 16px; line-height: 1.8; color: #475569;">
            <li>✓ Proven track record with 500+ successful placements</li>
            <li>✓ 95% client satisfaction rate</li>
            <li>✓ Industry expertise across 10+ sectors</li>
            <li>✓ Personalized approach to every engagement</li>
            <li>✓ Transparent and efficient processes</li>
            <li>✓ Dedicated account management</li>
            <li>✓ Advanced recruitment technology</li>
            <li>✓ Confidential and professional service</li>
        </ul>
    </div>
</div>
