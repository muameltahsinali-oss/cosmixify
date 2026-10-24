<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function asset_path(string $path, string $prefix = ''): string
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    if (str_starts_with($path, 'uploads/')) {
        return $prefix . $path;
    }
    return $prefix . 'uploads/' . ltrim($path, '/');
}

function user_photo_path(string $filename, string $prefix = ''): string
{
    if (str_starts_with($filename, 'uploads/')) {
        return $prefix . $filename;
    }
    return $prefix . 'uploads/user_photos/' . ltrim($filename, '/');
}

function pollution_label(string $value): string
{
    $map = [
        'Yes' => t('pollution.yes'),
        'No' => t('pollution.no'),
        'Uncertain' => t('pollution.uncertain'),
    ];
    return $map[$value] ?? $value;
}

function render_photo_card(array $row, string $linkPrefix = '', string $detailsPage = 'photo-details.php'): void
{
    $id = (int) $row['id'];
    $title = e($row['Title'] ?? '');
    $image = e(asset_path($row['image'] ?? '', $linkPrefix));
    echo '<div class="col-10 col-md-3" data-aos="fade-up" data-aos-delay="100">';
    echo '<div class="card">';
    echo '<a href="' . $linkPrefix . $detailsPage . '?id=' . $id . '">';
    echo '<img src="' . $image . '" class="card-img-top" alt="' . $title . '" loading="lazy">';
    echo '<div class="card-body"><h5 class="card-title">' . $title . '</h5></div>';
    echo '</a></div></div>';
}

function image_src(string $path, string $prefix = ''): string
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return asset_path($path, $prefix);
}

function render_bento_photo(array $row, string $class = '', string $linkPrefix = '', string $detailsPage = 'photo-details.php'): void
{
    $id = (int) $row['id'];
    $title = e($row['Title'] ?? $row['title'] ?? '');
    $desc = e(mb_substr(strip_tags($row['description'] ?? $row['explanation'] ?? ''), 0, 120));
    $image = e(image_src($row['image'] ?? $row['url'] ?? '', $linkPrefix));
    $badge = e($row['badge'] ?? '');
    $featured = str_contains($class, 'featured') ? ' bento-card--parallax' : '';

    echo '<a href="' . $linkPrefix . $detailsPage . '?id=' . $id . '" class="bento-card' . $featured . ' ' . $class . '" data-cosmic-reveal>';
    echo '<img src="' . $image . '" alt="' . $title . '" loading="lazy">';
    echo '<div class="bento-card__overlay"></div>';
    echo '<div class="bento-card__content">';
    if ($badge) {
        echo '<span class="bento-card__badge">' . $badge . '</span>';
    }
    echo '<h3>' . $title . '</h3>';
    if ($desc) {
        echo '<p>' . $desc . '</p>';
    }
    echo '</div></a>';
}

function render_bento_user_photo(array $row, string $class = '', string $linkPrefix = ''): void
{
    $row['image'] = user_photo_path($row['image'], $linkPrefix);
    $row['Title'] = $row['Title'] ?? '';
    render_bento_photo($row, $class, $linkPrefix, 'user_photo_details.php');
}

function render_section_head(string $eyebrow, string $title, string $linkUrl = '', string $linkLabel = ''): void
{
    echo '<div class="section-head" data-cosmic-reveal>';
    echo '<div><span class="section-eyebrow">' . e($eyebrow) . '</span><h2 class="section-title">' . $title . '</h2></div>';
    if ($linkUrl && $linkLabel) {
        echo '<a href="' . e($linkUrl) . '" class="btn-cosmic btn-cosmic--ghost">' . e($linkLabel) . ' <span aria-hidden="true">→</span></a>';
    }
    echo '</div>';
}
