<footer class="site-footer">
    <div class="site-footer__glow" aria-hidden="true"></div>
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <a href="index.php" class="site-footer__logo-link">
                <img src="assets/img/logo.png" alt="" class="site-footer__logo" width="44" height="44" loading="lazy">
                <span class="site-footer__name"><?= e(t('site.name')) ?></span>
            </a>
            <p class="site-footer__tagline"><?= e(t('footer.tagline')) ?></p>
        </div>

        <nav class="site-footer__links" aria-label="Footer">
            <a href="index.php"><?= e(t('nav.home')) ?></a>
            <a href="calendar.php"><?= e(t('nav.calendar')) ?></a>
            <a href="about-us.php"><?= e(t('nav.about')) ?></a>
            <a href="share.php"><?= e(t('home.share_yours')) ?></a>
        </nav>

        <div class="site-footer__social">
            <a href="https://www.instagram.com/cosmixify_" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="site-footer__social-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </a>
            <a href="mailto:contact@cosmixify.space" aria-label="Email" class="site-footer__social-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z"/>
                    <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z"/>
                </svg>
            </a>
        </div>
    </div>

    <div class="site-footer__bottom">
        <p>&copy; <?= date('Y') ?> <?= e(t('site.name')) ?> &middot; <?= e(t('footer.rights')) ?></p>
    </div>
</footer>
