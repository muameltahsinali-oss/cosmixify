# Cosmixify

Space imagery archive and astronomy community, in English and Arabic.

- **Agency archive**: images from NASA (APOD), ESA, CAST and other agencies in one place, with a curated section of images that changed space science.
- **Community feed**: members upload their own astrophotography, which is reviewed by admins before it goes public.
- **Sky events calendar**: full moons, conjunctions and meteor showers, with a light-pollution map for picking an observing spot.
- **Admin panel** for photos, events, members and community submissions.

Stack: PHP 8, MySQL, vanilla JavaScript, HTML/CSS.

## Run locally (XAMPP)

1. Put the folder in `htdocs/` (for example `htdocs/space`).
2. Copy `config.example.php` to `config.php` and set your database credentials and NASA API key ([api.nasa.gov](https://api.nasa.gov)).
3. Import `database/schema.sql`, then `database/seed.sql`.
4. Optional: pull recent APOD images with `php database/sync-apod.php 14`.
5. Open `http://localhost/space/`.

See [`docs/SITE-GUIDE.md`](docs/SITE-GUIDE.md) for a full tour of the site.

Built by [Muamel Tahsin](https://github.com/muameltahsinali-oss).
