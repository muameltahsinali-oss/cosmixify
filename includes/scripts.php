<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && !document.body.classList.contains('home-page')) {
    AOS.init({ once: true, duration: 600 });
}
</script>
<?php include __DIR__ . '/history-replace.php'; ?>
