<?php
require_once __DIR__ . '/../admin/config.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $inquiry_type = $_POST['inquiry_type'] ?? 'other';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_inquiries (full_name, email, phone, inquiry_type, subject, message) VALUES (:full_name, :email, :phone, :inquiry_type, :subject, :message)");
            $stmt->execute([
                ':full_name' => $full_name,
                ':email' => $email,
                ':phone' => $phone,
                ':inquiry_type' => $inquiry_type,
                ':subject' => $subject,
                ':message' => $message
            ]);
            $success = true;
        } catch (PDOException $e) {
            $error = 'Error submitting form. Please try again.';
        }
    }
}
?>

<style>
    .contact-hero { background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 73, 94, 0.85) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80') center/cover no-repeat; color: white; padding: 60px 20px; text-align: center; }
    .contact-hero h1 { font-size: 36px; margin-bottom: 10px; font-weight: 700; }
    .contact-hero p { font-size: 16px; opacity: 0.9; max-width: 600px; margin: 0 auto; }
    @media (max-width: 768px) {
        .contact-hero { padding: 30px 20px; }
        .contact-hero h1 { font-size: 28px; }
        .contact-hero p { font-size: 14px; }
    }
    @media (max-width: 480px) {
        .contact-hero { padding: 20px 15px; }
        .contact-hero h1 { font-size: 22px; margin-bottom: 8px; }
        .contact-hero p { font-size: 13px; }
    }
    .contact-container { max-width: 900px; margin: -40px auto 40px; padding: 0 20px; position: relative; z-index: 10; }
    .contact-form { background: white; padding: 50px; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    @media (max-width: 768px) {
        .contact-container { margin: -30px auto 30px; }
    }
    @media (max-width: 480px) {
        .contact-container { margin: -20px auto 20px; padding: 0 15px; }
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #2c3e50; font-size: 14px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 14px; transition: all 0.3s; }
    .form-group textarea { min-height: 150px; resize: vertical; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #5dd3e0; box-shadow: 0 0 0 3px rgba(93, 211, 224, 0.1); background: #f8fafc; }
    .required { color: #ff6b6b; }
    .alert { padding: 16px 20px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid; }
    .alert-success { background: #f0fdf4; color: #166534; border-left-color: #51cf66; }
    .alert-error { background: #fef2f2; color: #991b1b; border-left-color: #ff6b6b; }
    .btn-submit { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 14px 30px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; width: 100%; transition: all 0.3s; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(44, 62, 80, 0.3); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; gap: 15px; }
        .contact-form { padding: 25px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-size: 13px; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea { font-size: 13px; padding: 10px 12px; }
        .btn-submit { font-size: 14px; padding: 12px 25px; }
    }
    @media (max-width: 480px) {
        .contact-form { padding: 15px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { font-size: 12px; margin-bottom: 4px; }
        .form-group input, .form-group select, .form-group textarea { font-size: 12px; padding: 8px 10px; }
        .btn-submit { font-size: 13px; padding: 10px 15px; }
        .form-group textarea { min-height: 100px; }
    }
    .success-message { text-align: center; }
    .success-message h2 { color: #51cf66; margin-bottom: 10px; font-size: 28px; }
    .success-message p { color: #64748b; margin-bottom: 20px; }
    .success-message a { display: inline-block; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600; }
</style>

<div class="contact-hero">
    <h1>Get in Touch</h1>
    <p>Have questions about our recruitment services? We're here to help. Send us a message and we'll respond within 24 hours.</p>
</div>

<div class="contact-container">
    <div class="contact-form">
        <?php if ($success): ?>
            <div class="alert alert-success">
                <div class="success-message">
                    <h2>Thank You!</h2>
                    <p>Your inquiry has been received. We'll get back to you shortly.</p>
                    <a href="../../radiantforcehumanresources.php">Back to Home</a>
                </div>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone">
                    </div>
                    <div class="form-group">
                        <label>Inquiry Type</label>
                        <select name="inquiry_type">
                            <option value="job_inquiry">Job Inquiry</option>
                            <option value="service_inquiry">Service Inquiry</option>
                            <option value="partnership">Partnership</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Subject <span class="required">*</span></label>
                    <input type="text" name="subject" required>
                </div>

                <div class="form-group">
                    <label>Message <span class="required">*</span></label>
                    <textarea name="message" required></textarea>
                </div>

                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        <?php endif; ?>
    </div>
</div>
