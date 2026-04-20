<style>
    .faqs-hero { background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(52, 73, 94, 0.85) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80') center/cover no-repeat; color: white; padding: 80px 20px; text-align: center; }
    .faqs-hero h1 { font-size: 42px; margin-bottom: 15px; font-weight: 700; }
    .faqs-hero p { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto; }
    @media (max-width: 768px) {
        .faqs-hero { padding: 40px 20px; }
        .faqs-hero h1 { font-size: 32px; margin-bottom: 10px; }
        .faqs-hero p { font-size: 15px; }
    }
    @media (max-width: 480px) {
        .faqs-hero { padding: 25px 15px; }
        .faqs-hero h1 { font-size: 24px; margin-bottom: 8px; }
        .faqs-hero p { font-size: 13px; }
    }
    .faqs-container { max-width: 900px; margin: 0 auto; padding: 60px 20px; }
    @media (max-width: 768px) {
        .faqs-container { padding: 40px 20px; }
    }
    @media (max-width: 480px) {
        .faqs-container { padding: 25px 15px; }
    }
    .category-filter { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 40px; }
    .category-btn { padding: 10px 18px; border: 2px solid #e2e8f0; background: white; border-radius: 6px; cursor: pointer; transition: all 0.3s; color: #475569; font-weight: 500; font-size: 14px; }
    .category-btn:hover { border-color: #5dd3e0; color: #2c3e50; }
    .category-btn.active { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-color: #2c3e50; }
    .faq-item { background: white; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 15px; overflow: hidden; transition: all 0.3s; }
    .faq-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-color: #5dd3e0; }
    .faq-question { padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; transition: all 0.3s; }
    .faq-question:hover { background: #f0f9fb; }
    .faq-question h3 { margin: 0; color: #2c3e50; font-size: 16px; font-weight: 600; flex: 1; }
    .faq-toggle { color: #5dd3e0; font-size: 24px; transition: transform 0.3s; margin-left: 15px; }
    .faq-item.active .faq-toggle { transform: rotate(180deg); }
    .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .faq-item.active .faq-answer { max-height: 500px; }
    .faq-answer-content { padding: 20px; color: #475569; line-height: 1.7; font-size: 15px; }
    .faq-category { display: inline-block; background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 4px; font-size: 12px; margin-bottom: 10px; font-weight: 600; }
    .no-faqs { text-align: center; padding: 60px 20px; }
    .no-faqs h3 { color: #2c3e50; font-size: 24px; margin-bottom: 10px; }
    .no-faqs p { color: #94a3b8; }
    @media (max-width: 768px) {
        .category-filter { margin-bottom: 30px; gap: 8px; }
        .category-btn { padding: 8px 14px; font-size: 13px; }
        .faq-question { padding: 15px; }
        .faq-question h3 { font-size: 15px; }
        .faq-toggle { font-size: 20px; margin-left: 10px; }
        .faq-answer-content { padding: 15px; font-size: 14px; }
    }
    @media (max-width: 480px) {
        .category-filter { margin-bottom: 20px; gap: 6px; }
        .category-btn { padding: 6px 12px; font-size: 12px; }
        .faq-question { padding: 12px; }
        .faq-question h3 { font-size: 14px; }
        .faq-toggle { font-size: 18px; margin-left: 8px; }
        .faq-answer-content { padding: 12px; font-size: 13px; }
        .faq-item { margin-bottom: 12px; }
    }
</style>

<div class="faqs-hero">
    <h1>Frequently Asked Questions</h1>
    <p>Find answers to common questions about our services and recruitment process</p>
</div>

<div class="faqs-container">
    <?php if (!empty($categories)): ?>
        <div class="category-filter">
            <a href="faqs.php" class="category-btn <?php echo empty($category) ? 'active' : ''; ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="?category=<?php echo urlencode($cat['category']); ?>" class="category-btn <?php echo $category === $cat['category'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat['category']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($faqs)): ?>
        <div class="no-faqs">
            <h3>No FAQs found</h3>
            <p>Check back soon for answers to common questions</p>
        </div>
    <?php else: ?>
        <?php foreach ($faqs as $faq): ?>
            <div class="faq-item">
                <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                    <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <?php if ($faq['category']): ?>
                            <div class="faq-category"><?php echo htmlspecialchars($faq['category']); ?></div>
                        <?php endif; ?>
                        <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
