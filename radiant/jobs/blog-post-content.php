<div style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 60px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <a href="blog.php" style="color: #5dd3e0; text-decoration: none; display: inline-block; margin-bottom: 15px; font-weight: 500; font-size: 14px;">← Back to Blog</a>
        <?php if ($post['category']): ?>
            <div style="display: inline-block; background: rgba(93, 211, 224, 0.2); color: #5dd3e0; padding: 6px 14px; border-radius: 20px; font-size: 12px; margin-bottom: 12px; font-weight: 600;"><?php echo htmlspecialchars($post['category']); ?></div>
        <?php endif; ?>
        <h1 style="color: white; font-size: 42px; margin: 12px 0; line-height: 1.3; font-weight: 700;"><?php echo htmlspecialchars($post['title']); ?></h1>
        <div style="display: flex; gap: 25px; color: rgba(255,255,255,0.8); font-size: 14px; margin-top: 15px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <span>📅</span>
                <span><?php echo date('F d, Y', strtotime($post['published_at'])); ?></span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span>✍️</span>
                <span><?php echo htmlspecialchars($post['author']); ?></span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span>👁️</span>
                <span><?php echo $post['views']; ?> views</span>
            </div>
        </div>
    </div>
</div>

<div style="max-width: 800px; margin: 0 auto; padding: 60px 20px;">
    <div style="color: #2c3e50; line-height: 1.8; font-size: 16px; margin-bottom: 40px;">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
    </div>

    <div style="border-top: 1px solid #e2e8f0; padding-top: 30px; margin-top: 40px;">
        <div style="display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap;">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" style="display: inline-block; padding: 10px 16px; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">Share on Facebook</a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>" class="share-btn" target="_blank" style="display: inline-block; padding: 10px 16px; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">Share on Twitter</a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-btn" target="_blank" style="display: inline-block; padding: 10px 16px; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">Share on LinkedIn</a>
        </div>
    </div>

    <?php if (!empty($related_posts)): ?>
        <div style="margin-top: 60px; padding-top: 40px; border-top: 1px solid #e2e8f0;">
            <h3 style="color: #2c3e50; margin-bottom: 30px; font-size: 24px; font-weight: 700;">Related Articles</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($related_posts as $related): ?>
                    <div style="background: white; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; transition: all 0.3s;">
                        <div style="color: #2c3e50; font-weight: 700; margin-bottom: 10px; font-size: 16px;"><?php echo htmlspecialchars($related['title']); ?></div>
                        <div style="color: #64748b; font-size: 14px; margin-bottom: 15px; line-height: 1.6;"><?php echo htmlspecialchars(substr($related['excerpt'] ?? $related['content'], 0, 100)); ?>...</div>
                        <a href="blog-post.php?id=<?php echo $related['id']; ?>" style="color: #5dd3e0; text-decoration: none; font-weight: 600;">Read More →</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
