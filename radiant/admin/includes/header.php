<header class="admin-header">
    <div class="header-left">
        <h2><?php echo ucfirst(str_replace(['-', '.php'], [' ', ''], basename($_SERVER['PHP_SELF']))); ?></h2>
    </div>
    <div class="header-right">
        <span class="admin-user">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'Admin'); ?>
        </span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</header>
