<?php
?>
<form action="search.php" method="GET" class="site-header-form" role="search">    <input type="hidden" name="lang" value="<?= e(current_lang()) ?>">
    <div class="search-container">
        <div class="search-bar">
            <img src="assets/img/logo.png" alt="<?= e(t('site.name')) ?>" class="search-icon">
            <input type="text" name="SpaceSearch" placeholder="<?= e(t('nav.search_placeholder')) ?>" aria-label="<?= e(t('nav.search_placeholder')) ?>">
            <button type="submit"><?= e(t('nav.search')) ?></button>
        </div>
        <div class="login-container">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="login-button" aria-label="<?= e(t('nav.profile')) ?>">
                    <span class="material-symbols-outlined" aria-hidden="true">account_circle</span>
                </a>
            <?php else: ?>
                <a href="login-register.php" class="login-button" aria-label="<?= e(t('nav.login')) ?>">
                    <span class="material-symbols-outlined" aria-hidden="true">login</span>
                </a>
            <?php endif; ?>
            <a href="calendar.php" class="calendar-icon" aria-label="<?= e(t('nav.calendar')) ?>">
                <span class="material-symbols-outlined" aria-hidden="true">calendar_today</span>
            </a>
        </div>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var lastScrollTop = 0;
    var searchContainer = document.querySelector('.search-container');
    if (!searchContainer) return;
    window.addEventListener('scroll', function () {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        searchContainer.classList.toggle('show-search-bar', scrollTop <= lastScrollTop);
        lastScrollTop = scrollTop;
    });
});
</script>
