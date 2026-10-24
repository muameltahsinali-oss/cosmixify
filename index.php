<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

$conn->query("INSERT INTO site_visits (visit_date) VALUES (NOW())");

function fetch_rows(mysqli $conn, string $sql): array
{
    $rows = [];
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

$latestPhotos   = fetch_rows($conn, "SELECT id, Title, description, ardescription, image FROM photos ORDER BY id DESC LIMIT 4");
$topRatedPhotos = fetch_rows($conn, "SELECT id, Title, description, ardescription, image FROM photos ORDER BY views DESC LIMIT 4");
$nasaPhotos     = fetch_rows($conn, "SELECT id, title, explanation, url, date FROM apod ORDER BY date DESC LIMIT 4");
$userPhotos     = fetch_rows($conn, "SELECT id, Title, description, image FROM user_photos WHERE accept = 'on' ORDER BY id DESC LIMIT 4");
$events         = fetch_rows($conn, "SELECT id, event_title, event_description, event_image, event_date FROM astronomical_events WHERE status='Coming' ORDER BY event_date ASC LIMIT 4");

$categories = [
    ['search' => 'Galaxy', 'icon' => 'assets/img/galaxy.png', 'title' => t('cat.galaxies'), 'desc' => t('cat.galaxies_desc'), 'large' => true],
    ['search' => 'planet', 'icon' => 'assets/img/saturn.png', 'title' => t('cat.planets'), 'desc' => t('cat.planets_desc'), 'large' => false],
    ['search' => 'james', 'icon' => 'assets/img/james.png', 'title' => t('cat.james'), 'desc' => t('cat.james_desc'), 'large' => false],
    ['search' => 'star', 'icon' => 'assets/img/constellation.png', 'title' => t('cat.stars'), 'desc' => t('cat.stars_desc'), 'large' => false],
    ['search' => 'nasa', 'icon' => 'assets/img/nasa.png', 'title' => t('cat.nasa'), 'desc' => t('cat.nasa_desc'), 'large' => false],
    ['search' => 'esa', 'icon' => 'assets/img/ea.png', 'title' => t('cat.esa'), 'desc' => t('cat.esa_desc'), 'large' => false],
];

$pageClass = 'home-page';
$navMode = 'home';
$pageStyles = ['assets/css/home-cosmic.css', 'assets/css/home-premium.css', 'assets/css/solar-system.css'];
include __DIR__ . '/includes/layout-start.php';
?>

<div class="popup-background" id="welcomePopup" role="dialog" aria-modal="true" aria-labelledby="welcomeTitle">
    <div class="popup-container">
        <img src="assets/img/logo.png" alt="<?= e(t('site.name')) ?>">
        <h2 id="welcomeTitle"><?= e(t('home.popup_title')) ?></h2>
        <p><?= e(t('home.popup_text')) ?></p>
        <button class="btn-cosmic btn-cosmic--primary btn-close" type="button" onclick="closePopup()"><?= e(t('home.popup_btn')) ?></button>
    </div>
</div>

<header class="hero-split" id="home">
    <canvas class="cosmic-canvas" aria-hidden="true"></canvas>
    <div class="cosmic-grain" aria-hidden="true"></div>
    <div class="cosmic-glow" aria-hidden="true"></div>

    <div class="home-container hero-split__inner">
        <div class="hero-split__top">
            <?php include __DIR__ . '/includes/lang-switcher.php'; ?>
        </div>

        <div class="hero-split__main">
            <div class="hero-split__visual">
                <img src="assets/img/austu.png" alt="" class="hero-split__astronaut"
                     onerror="this.onerror=null;this.src='assets/img/rocket.svg';this.classList.add('hero-split__fallback');">
            </div>

            <div class="hero-split__text">
                <h1 class="hero-split__title">
                    <span class="cosmic-line">
                        <?php foreach (preg_split('/\s+/u', trim(t('home.welcome_title'))) as $word): ?>
                            <?php if ($word !== ''): ?><span class="cosmic-word"><?= e($word) ?></span><?php endif; ?>
                        <?php endforeach; ?>
                    </span>
                    <span class="cosmic-line">
                        <span class="cosmic-word text-accent"><?= e(t('site.name')) ?></span>
                    </span>
                </h1>
                <p class="hero-split__lead cosmic-line">
                    <span class="cosmic-line-inner"><?= e(t('home.hero_lead')) ?></span>
                </p>
                <p class="hero-split__desc cosmic-line">
                    <span class="cosmic-line-inner"><?= e(t('home.hero_desc')) ?></span>
                </p>

                <div class="hero-split__actions">
                    <a href="#Latest" class="btn-cosmic btn-cosmic--primary"><?= e(t('home.explore_photos')) ?></a>
                    <a href="calendar.php" class="btn-cosmic btn-cosmic--ghost"><?= e(t('home.view_calendar')) ?></a>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="home-section" id="Latest">
    <div class="home-container">
        <?php render_section_head(t('home.latest_eyebrow'), t('home.latest_title'), 'last.php', t('home.view_all')); ?>

        <?php if ($latestPhotos): ?>
        <div class="bento-grid bento-grid--photos">
            <?php foreach ($latestPhotos as $i => $photo): ?>
                <?php
                $photo['description'] = locale_content($photo, 'description', 'ardescription');
                render_bento_photo($photo, $i === 0 ? 'bento-card--featured' : '');
                ?>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('home.empty_photos')) ?></div>
        <?php endif; ?>
    </div>
</section>

<?php if ($events): ?>
<section class="home-section home-section--alt" id="Events">
    <div class="home-container">
        <?php render_section_head(t('home.events_eyebrow'), t('home.events_title'), 'calendar.php', t('home.full_calendar')); ?>

        <div class="events-bento">
            <?php foreach ($events as $event): ?>
            <article class="event-bento-card">
                <img src="<?= e($event['event_image']) ?>" alt="<?= e($event['event_title']) ?>" loading="lazy">
                <div class="event-bento-card__body">
                    <div class="event-bento-card__date"><?= e($event['event_date']) ?></div>
                    <h3><?= e($event['event_title']) ?></h3>
                    <p><?= e(mb_substr(strip_tags($event['event_description']), 0, 120)) ?>...</p>
                    <a href="event-details.php?id=<?= (int) $event['id'] ?>" class="btn-cosmic btn-cosmic--ghost"><?= e(t('home.more_details')) ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="home-section" id="NASAImages">
    <div class="home-container">
        <?php render_section_head(t('home.nasa_eyebrow'), t('home.nasa_title'), 'all-nasa-photos.php', t('home.view_all')); ?>

        <?php if ($nasaPhotos): ?>
        <div class="bento-grid bento-grid--nasa">
            <?php foreach ($nasaPhotos as $i => $photo): ?>
                <?php
                $photo['Title'] = $photo['title'];
                $photo['description'] = $photo['explanation'];
                $photo['image'] = $photo['url'];
                $photo['badge'] = t('home.badge_nasa');
                render_bento_photo($photo, $i === 0 ? 'bento-card--featured' : '', '', 'nasa-photo-details.php');
                ?>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('home.empty_nasa')) ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="home-section home-section--alt" id="TopRated">
    <div class="home-container">
        <?php render_section_head(t('home.toprated_eyebrow'), t('home.toprated_title'), 'TopRated.php', t('home.view_more')); ?>

        <?php if ($topRatedPhotos): ?>
        <div class="bento-grid bento-grid--photos">
            <?php foreach ($topRatedPhotos as $i => $photo): ?>
                <?php
                $photo['description'] = locale_content($photo, 'description', 'ardescription');
                $photo['badge'] = t('home.badge_top');
                render_bento_photo($photo, $i === 0 ? 'bento-card--featured' : '');
                ?>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('home.empty_rated')) ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="home-section" id="Shares">
    <div class="home-container">
        <?php render_section_head(t('home.community_eyebrow'), t('home.community_title'), 'share.php', t('home.share_yours')); ?>

        <?php if ($userPhotos): ?>
        <div class="bento-grid bento-grid--photos">
            <?php foreach ($userPhotos as $i => $photo): ?>
                <?php
                $photo['badge'] = t('home.badge_community');
                render_bento_user_photo($photo, $i === 0 ? 'bento-card--featured' : '');
                ?>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="home-empty"><?= e(t('home.empty_community')) ?> <a href="share.php"><?= e(t('home.upload_now')) ?></a></div>
        <?php endif; ?>
    </div>
</section>

<section class="home-section home-section--alt" id="Categories">
    <div class="home-container">
        <?php render_section_head(t('home.categories_eyebrow'), t('home.categories_title')); ?>

        <div class="bento-grid bento-grid--categories">
            <?php foreach ($categories as $cat): ?>
            <a href="search.php?SpaceSearch=<?= urlencode($cat['search']) ?>"
               class="category-bento <?= $cat['large'] ? 'category-bento--large' : '' ?>">
                <img src="<?= e($cat['icon']) ?>" alt="" class="category-bento__icon" loading="lazy">
                <h3><?= e($cat['title']) ?></h3>
                <p><?= e($cat['desc']) ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="home-solar" id="SolarSystem">
    <div class="home-container">
        <h2 class="section-title text-center"><?= t('home.solar_title') ?></h2>
        <?php include __DIR__ . '/includes/solar-system.php'; ?>
    </div>
</section>

<script src="assets/js/gsap.min.js"></script>
<script src="assets/js/ScrollTrigger.min.js"></script>
<script src="assets/js/lenis.min.js"></script>
<script src="assets/js/solar-system.js"></script>
<script src="assets/js/home-cosmic.js"></script>

<?php include __DIR__ . '/includes/layout-end.php'; ?>
