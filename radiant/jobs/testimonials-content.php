<div style="background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 73, 94, 0.85) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80') center/cover no-repeat; color: white; padding: 80px 20px; text-align: center;">
    <h1 style="font-size: 42px; margin-bottom: 15px; font-weight: 700;">Client Testimonials</h1>
    <p style="font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto;">Hear from our satisfied clients about their experience with Radiant Force HR</p>
</div>

<div style="max-width: 1200px; margin: 0 auto; padding: 60px 20px;">
    <?php if (empty($testimonials)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <h3 style="color: #2c3e50; font-size: 24px; margin-bottom: 10px;">No testimonials yet</h3>
            <p style="color: #94a3b8;">Check back soon to see what our clients have to say</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <?php foreach ($testimonials as $testimonial): ?>
                <div style="background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; transition: all 0.3s;">
                    <div style="display: flex; gap: 4px; margin-bottom: 15px;">
                        <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                            <span style="color: #ffa94d; font-size: 18px;">★</span>
                        <?php endfor; ?>
                        <?php for ($i = $testimonial['rating']; $i < 5; $i++): ?>
                            <span style="color: #e2e8f0; font-size: 18px;">★</span>
                        <?php endfor; ?>
                    </div>
                    <p style="color: #475569; font-size: 15px; line-height: 1.7; margin-bottom: 20px; font-style: italic;">"<?php echo htmlspecialchars($testimonial['testimonial_text']); ?>"</p>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #2c3e50 0%, #5dd3e0 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px;">
                            <?php echo strtoupper(substr($testimonial['client_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: #2c3e50; font-size: 16px; font-weight: 700;"><?php echo htmlspecialchars($testimonial['client_name']); ?></h4>
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;"><?php echo htmlspecialchars($testimonial['client_title'] ?? ''); ?><?php echo ($testimonial['client_title'] && $testimonial['client_company']) ? ' at ' : ''; ?><?php echo htmlspecialchars($testimonial['client_company'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
