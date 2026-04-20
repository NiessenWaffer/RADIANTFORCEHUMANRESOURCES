<?php
// Determine the current page for active nav link
$current_page = basename($_SERVER['PHP_SELF']);
$is_jobs_folder = strpos($_SERVER['PHP_SELF'], '/jobs/') !== false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo isset($page_title) ? $page_title : 'Radiant Force HR'; ?></title>
    <link rel="stylesheet" href="<?php echo $is_jobs_folder ? '../design/styles.css' : '../design/styles.css'; ?>">
</head>
<body>
    <!-- Header / Navigation -->
    <header id="header">
        <div class="container">
            <a href="<?php echo $is_jobs_folder ? '../radiantforcehumanresources.php' : '../radiantforcehumanresources.php'; ?>" class="logo">
                <img src="<?php echo $is_jobs_folder ? '../imagees/logo.png' : '../imagees/logo.png'; ?>" alt="Radiant Force HR Logo">
                <span class="logo-text">Radiant Force</span>
            </a>
            <nav id="nav">
                <a href="<?php echo $is_jobs_folder ? '../radiantforcehumanresources.php' : '../radiantforcehumanresources.php'; ?>" class="nav-link">Home</a>
                <a href="<?php echo $is_jobs_folder ? 'jobs.php' : 'jobs/jobs.php'; ?>" class="nav-link">Jobs</a>
                <a href="<?php echo $is_jobs_folder ? 'blog.php' : 'jobs/blog.php'; ?>" class="nav-link">Blog</a>
                <a href="<?php echo $is_jobs_folder ? 'faqs.php' : 'jobs/faqs.php'; ?>" class="nav-link">FAQs</a>
                <a href="<?php echo $is_jobs_folder ? 'referral-program.php' : 'jobs/referral-program.php'; ?>" class="nav-link">Referral</a>
                <a href="<?php echo $is_jobs_folder ? 'testimonials.php' : 'jobs/testimonials.php'; ?>" class="nav-link">Testimonials</a>
                <a href="<?php echo $is_jobs_folder ? 'contact-form.php' : 'jobs/contact-form.php'; ?>" class="nav-link nav-cta">Contact</a>
            </nav>
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>
