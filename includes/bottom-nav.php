<?php
/** @var string $navMode 'home' | 'inner' | 'profile' */
$navMode = $navMode ?? 'inner';

function bottom_nav_icon(string $path): string
{
    return '<svg class="bottom-nav__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' . $path . '</svg>';
}

$icons = [
    'home'       => bottom_nav_icon('<path d="M11.47 3.84a1.5 1.5 0 011.06 0l8.69 4.41A1.5 1.5 0 0122 9.63V19.5a1.5 1.5 0 01-1.5 1.5h-4.5a1.5 1.5 0 01-1.5-1.5v-4.5a1.5 1.5 0 00-1.5-1.5h-3a1.5 1.5 0 00-1.5 1.5V19.5A1.5 1.5 0 015 21H3.5A1.5 1.5 0 012 19.5V9.63a1.5 1.5 0 01.78-1.37l8.69-4.42z"/>'),
    'latest'     => bottom_nav_icon('<path fill-rule="evenodd" d="M12 2.25c-5.39 0-9.75 4.36-9.75 9.75s4.36 9.75 9.75 9.75 9.75-4.36 9.75-9.75S17.39 2.25 12 2.25zm.75 3a.75.75 0 00-1.5 0v6c0 .41.34.75.75.75h4.5a.75.75 0 000-1.5h-3.75V5.25z" clip-rule="evenodd"/>'),
    'categories' => bottom_nav_icon('<path d="M5.57 4.66A4.5 4.5 0 016.75 4.5h10.5c.41 0 .81.05 1.18.16A1.5 1.5 0 0119.5 6v12a1.5 1.5 0 01-1.57 1.34A4.5 4.5 0 0117.25 19.5H6.75a4.5 4.5 0 01-1.18-.16A1.5 1.5 0 014.5 18V6a1.5 1.5 0 011.07-1.34zM6.75 6a3 3 0 00-3 3v9a3 3 0 003 3h10.5a3 3 0 003-3V9a3 3 0 00-3-3H6.75z"/>'),
    'shares'     => bottom_nav_icon('<path fill-rule="evenodd" d="M1.5 6A2.25 2.25 0 013.75 3.75h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zm4.5 4.5a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zm9.31 4.28a.75.75 0 00-1.22-.87l-2.83 3.9-1.88-1.88a.75.75 0 00-1.06 1.06l2.5 2.5a.75.75 0 001.15-.08l3.5-4.5z" clip-rule="evenodd"/>'),
    'favorites'  => bottom_nav_icon('<path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005z" clip-rule="evenodd"/>'),
    'about'      => bottom_nav_icon('<path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>'),
];

if ($navMode === 'home') {
    $items = [
        ['href' => '#home', 'icon' => $icons['home'], 'label' => t('nav.home')],
        ['href' => '#Latest', 'icon' => $icons['latest'], 'label' => t('nav.latest')],
        ['href' => '#Categories', 'icon' => $icons['categories'], 'label' => t('nav.categories')],
        ['href' => '#Shares', 'icon' => $icons['shares'], 'label' => t('nav.shares')],
    ];
} elseif ($navMode === 'profile') {
    $items = [
        ['href' => 'index.php#home', 'icon' => $icons['home'], 'label' => t('nav.home')],
        ['href' => '#Favorites', 'icon' => $icons['favorites'], 'label' => t('nav.favorites')],
        ['href' => '#Shares', 'icon' => $icons['shares'], 'label' => t('profile.my_shares')],
        ['href' => 'about-us.php', 'icon' => $icons['about'], 'label' => t('nav.about')],
    ];
} else {
    $items = [
        ['href' => 'index.php#home', 'icon' => $icons['home'], 'label' => t('nav.home')],
        ['href' => 'index.php#Latest', 'icon' => $icons['latest'], 'label' => t('nav.latest')],
        ['href' => 'index.php#Categories', 'icon' => $icons['categories'], 'label' => t('nav.categories')],
        ['href' => 'index.php#Shares', 'icon' => $icons['shares'], 'label' => t('nav.shares')],
    ];
}
?>
<nav class="bottom-nav" aria-label="Main navigation">
    <div class="bottom-nav__inner">
        <?php foreach ($items as $item): ?>
        <a href="<?= e($item['href']) ?>" class="bottom-nav__item">
            <span class="bottom-nav__icon"><?= $item['icon'] ?></span>
            <span class="bottom-nav__label"><?= e($item['label']) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</nav>
