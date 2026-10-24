<?php
$otherLang = current_lang() === 'ar' ? 'en' : 'ar';
$otherLabel = current_lang() === 'ar' ? 'English' : 'العربية';
$otherFlag = current_lang() === 'ar' ? 'assets/img/uk.png' : 'assets/img/iraq.png';
?>
<div class="dropdown lang-switcher">
    <button class="hero-lang-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?= e($otherFlag) ?>" width="22" height="22" alt=""> <?= e(t('lang.change')) ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-dark">
        <li><a class="dropdown-item" href="<?= e(lang_url('ar')) ?>"><img src="assets/img/iraq.png" width="20" alt=""> العربية</a></li>
        <li><a class="dropdown-item" href="<?= e(lang_url('en')) ?>"><img src="assets/img/uk.png" width="20" alt=""> English</a></li>
    </ul>
</div>
