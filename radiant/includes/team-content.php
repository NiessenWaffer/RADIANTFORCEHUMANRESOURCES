<?php
// Team page content
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
    .team-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin: 40px 0; }
    .team-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; transition: all 0.3s; }
    .team-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.1); transform: translateY(-5px); }
    .team-card-image { width: 100%; height: 250px; background: linear-gradient(135deg, #2c3e50 0%, #5dd3e0 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 80px; }
    .team-card-content { padding: 25px; }
    .team-card-name { font-size: 20px; font-weight: 700; color: #2c3e50; margin-bottom: 5px; }
    .team-card-role { font-size: 14px; color: #5dd3e0; font-weight: 600; margin-bottom: 12px; }
    .team-card-bio { font-size: 14px; color: #64748b; line-height: 1.6; }
    .team-card-social { display: flex; gap: 10px; margin-top: 15px; }
    .team-card-social a { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #f0f9ff; color: #5dd3e0; border-radius: 50%; text-decoration: none; transition: all 0.3s; }
    .team-card-social a:hover { background: #5dd3e0; color: white; }
</style>

<div class="page-hero">
    <h1>Our Team</h1>
    <p>Meet the experienced professionals dedicated to your success</p>
</div>

<div class="page-content">
    <div class="content-section">
        <h2>Leadership & Consultants</h2>
        <p>Our team consists of experienced HR professionals with deep industry knowledge and a passion for connecting talent with opportunity. Each member brings unique expertise and a commitment to excellence.</p>
    </div>

    <div class="team-grid">
        <div class="team-card">
            <div class="team-card-image">👔</div>
            <div class="team-card-content">
                <div class="team-card-name">Wilmer Pena</div>
                <div class="team-card-role">Supervisor</div>
                <div class="team-card-bio">With over 15 years in recruitment and HR consulting, Wilmer leads our strategic vision and client partnerships across diverse industries. His expertise spans executive search, organizational development, and talent strategy.</div>
                <div class="team-card-social">
                    <a href="#" title="LinkedIn">in</a>
                    <a href="#" title="Email">✉</a>
                </div>
            </div>
        </div>

        <div class="team-card">
            <div class="team-card-image">💼</div>
            <div class="team-card-content">
                <div class="team-card-name">Daday De Luna</div>
                <div class="team-card-role">Manager</div>
                <div class="team-card-bio">Specializing in executive search and middle management placements, Daday brings 12 years of expertise in matching top talent with leading organizations. Known for building lasting client relationships.</div>
                <div class="team-card-social">
                    <a href="#" title="LinkedIn">in</a>
                    <a href="#" title="Email">✉</a>
                </div>
            </div>
        </div>

        <div class="team-card">
            <div class="team-card-image">👩‍💼</div>
            <div class="team-card-content">
                <div class="team-card-name">Jean</div>
                <div class="team-card-role">HR Officer</div>
                <div class="team-card-bio">Jean specializes in organizational development, training programs, and HR strategy. She helps businesses build stronger workforce capabilities and develop comprehensive HR solutions tailored to their needs.</div>
                <div class="team-card-social">
                    <a href="#" title="LinkedIn">in</a>
                    <a href="#" title="Email">✉</a>
                </div>
            </div>
        </div>

        <div class="team-card">
            <div class="team-card-image">🎯</div>
            <div class="team-card-content">
                <div class="team-card-name">Glen</div>
                <div class="team-card-role">Boss</div>
                <div class="team-card-bio">Focused on IT, engineering, and technical roles, Glen leverages his industry knowledge to connect specialized talent with innovative companies. His technical background ensures perfect candidate-company alignment.</div>
                <div class="team-card-social">
                    <a href="#" title="LinkedIn">in</a>
                    <a href="#" title="Email">✉</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h2>Why Our Team Matters</h2>
        <p>Our team's collective experience, industry knowledge, and dedication to excellence ensure that every placement is a success. We don't just fill positions—we build careers and transform organizations. Each team member is committed to understanding your unique needs and delivering personalized solutions that exceed expectations.</p>
    </div>

    <div class="content-section">
        <h2>Join Our Team</h2>
        <p>Are you passionate about recruitment and HR? We're always looking for talented professionals to join our growing team. If you're interested in making a difference in people's careers and helping organizations succeed, we'd love to hear from you.</p>
        <p><a href="jobs/contact-form.php" style="color: #5dd3e0; text-decoration: none; font-weight: 600;">Get in touch with us →</a></p>
    </div>
</div>
