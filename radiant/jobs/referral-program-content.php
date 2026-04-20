<div class="referral-hero" style="background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 73, 94, 0.85) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80') center/cover no-repeat; color: white; padding: 80px 20px; text-align: center;">
    <h1 style="font-size: 42px; margin-bottom: 15px; font-weight: 700;">Employee Referral Program</h1>
    <p style="font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto;">Know someone perfect for our team? Refer them and earn rewards!</p>
</div>

<div style="max-width: 900px; margin: -40px auto 40px; padding: 0 20px; position: relative; z-index: 10;">
    <div style="background: white; padding: 50px; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
        <?php if ($success): ?>
            <div style="text-align: center; padding: 40px 20px;">
                <h2 style="color: #51cf66; margin-bottom: 10px; font-size: 28px;">Thank You!</h2>
                <p style="color: #64748b; margin-bottom: 20px;">Your referral has been submitted successfully. We'll review the candidate and get back to you soon.</p>
                <a href="../../radiantforcehumanresources.php" style="display: inline-block; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600;">Back to Home</a>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <div style="padding: 16px 20px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #ff6b6b; background: #fef2f2; color: #991b1b;"><?php echo $error; ?></div>
            <?php endif; ?>

            <h2 style="color: #2c3e50; margin-bottom: 30px; font-size: 24px;">Submit a Referral</h2>

            <form method="POST">
                <h3 style="color: #2c3e50; margin-bottom: 20px; font-size: 16px; font-weight: 600;">Your Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #2c3e50; font-size: 14px;">Your Name <span style="color: #ff6b6b;">*</span></label>
                        <input type="text" name="referrer_name" required style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #2c3e50; font-size: 14px;">Your Email <span style="color: #ff6b6b;">*</span></label>
                        <input type="email" name="referrer_email" required style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <h3 style="color: #2c3e50; margin-bottom: 20px; margin-top: 30px; font-size: 16px; font-weight: 600;">Candidate Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #2c3e50; font-size: 14px;">Candidate Name <span style="color: #ff6b6b;">*</span></label>
                        <input type="text" name="referred_candidate_name" required style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #2c3e50; font-size: 14px;">Candidate Email <span style="color: #ff6b6b;">*</span></label>
                        <input type="email" name="referred_candidate_email" required style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #2c3e50; font-size: 14px;">Position (Optional)</label>
                    <select name="position_id" style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px;">
                        <option value="">Select a Position</option>
                        <?php foreach ($positions as $pos): ?>
                            <option value="<?php echo $pos['id']; ?>"><?php echo htmlspecialchars($pos['position_title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 14px 30px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; width: 100%;">Submit Referral</button>
            </form>
        <?php endif; ?>
    </div>
</div>
