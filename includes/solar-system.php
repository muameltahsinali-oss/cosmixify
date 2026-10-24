<?php
$planets = [
    ['id' => 'mercury', 'glow' => 'rgba(180, 170, 150, 0.35)', 'image' => 'https://images-assets.nasa.gov/image/PIA15160/PIA15160~medium.jpg'],
    ['id' => 'venus',   'glow' => 'rgba(255, 198, 92, 0.35)',  'image' => 'https://images-assets.nasa.gov/image/PIA00271/PIA00271~medium.jpg'],
    ['id' => 'earth',   'glow' => 'rgba(46, 211, 255, 0.35)',  'image' => 'https://images-assets.nasa.gov/image/GSFC_20171208_Archive_e000132/GSFC_20171208_Archive_e000132~medium.jpg'],
    ['id' => 'mars',    'glow' => 'rgba(232, 93, 76, 0.35)',   'image' => 'https://images-assets.nasa.gov/image/PIA04591/PIA04591~medium.jpg'],
    ['id' => 'jupiter', 'glow' => 'rgba(212, 165, 116, 0.35)','image' => 'https://images-assets.nasa.gov/image/PIA22946/PIA22946~medium.jpg'],
    ['id' => 'saturn',  'glow' => 'rgba(244, 213, 158, 0.35)','image' => 'https://images-assets.nasa.gov/image/PIA12567/PIA12567~medium.jpg'],
    ['id' => 'uranus',  'glow' => 'rgba(125, 227, 244, 0.35)','image' => 'https://images-assets.nasa.gov/image/PIA18182/PIA18182~medium.jpg'],
    ['id' => 'neptune', 'glow' => 'rgba(65, 105, 225, 0.35)', 'image' => 'https://images-assets.nasa.gov/image/PIA01492/PIA01492~medium.jpg'],
];
?>
<div id="solar-app" class="solar-app" data-current="mercury">
    <div class="solar-pills" role="tablist" aria-label="<?= e(t('solar.nav_label')) ?>">
        <?php foreach ($planets as $i => $planet): ?>
        <button type="button"
                class="solar-pill<?= $i === 0 ? ' is-active' : '' ?>"
                role="tab"
                id="solar-tab-<?= e($planet['id']) ?>"
                aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                aria-controls="solar-panel-<?= e($planet['id']) ?>"
                data-planet="<?= e($planet['id']) ?>">
            <?= e(t('solar.' . $planet['id'] . '.name')) ?>
        </button>
        <?php endforeach; ?>
    </div>

    <div class="solar-stage">
        <?php foreach ($planets as $i => $planet): ?>
        <article class="solar-planet<?= $i === 0 ? ' is-active' : '' ?>"
                 id="solar-panel-<?= e($planet['id']) ?>"
                 role="tabpanel"
                 aria-labelledby="solar-tab-<?= e($planet['id']) ?>"
                 data-planet="<?= e($planet['id']) ?>"
                 style="--planet-glow: <?= e($planet['glow']) ?>">
            <div class="solar-planet__visual">
                <div class="solar-planet__glow" aria-hidden="true"></div>
                <img src="<?= e($planet['image']) ?>"
                     alt="<?= e(t('solar.' . $planet['id'] . '.name')) ?>"
                     class="solar-planet__img"
                     loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                     width="480"
                     height="480">
            </div>
            <div class="solar-planet__info">
                <h3 class="solar-planet__name"><?= e(t('solar.' . $planet['id'] . '.name')) ?></h3>
                <p class="solar-planet__desc"><?= e(t('solar.' . $planet['id'] . '.desc')) ?></p>
                <dl class="solar-planet__stats">
                    <div class="solar-planet__stat">
                        <dt><?= e(t('solar.stat_day')) ?></dt>
                        <dd><?= e(t('solar.' . $planet['id'] . '.day_length')) ?></dd>
                    </div>
                    <div class="solar-planet__stat">
                        <dt><?= e(t('solar.stat_gravity')) ?></dt>
                        <dd><?= e(t('solar.' . $planet['id'] . '.gravity')) ?></dd>
                    </div>
                </dl>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</div>
