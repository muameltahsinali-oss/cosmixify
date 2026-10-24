-- Cosmixify — Replace gallery, APOD, and events with real NASA space content
-- Safe to re-run: truncates content tables only (keeps admin + users)

USE `cosmixify`;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `favs`;
TRUNCATE TABLE `photos_tags`;
TRUNCATE TABLE `user_photos`;
TRUNCATE TABLE `photos`;
TRUNCATE TABLE `apod`;
TRUNCATE TABLE `astronomical_events`;
TRUNCATE TABLE `site_visits`;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- Main gallery — NASA images (HTTPS URLs, bilingual descriptions)
-- ============================================================================

INSERT INTO `photos` (`Title`, `description`, `ardescription`, `image`, `category`, `source`, `sourceUrl`, `date`, `views`) VALUES
(
  'Pillars of Creation',
  'Iconic towers of gas and dust in the Eagle Nebula, captured by the Hubble Space Telescope. New stars are forming within these cosmic pillars.',
  'أعمدة غبار وغاز شهيرة في سديم النسر، التقطها تلسكوب هابل الفضائي. نجوم جديدة تتشكل داخل هذه الأعمدة الكونية.',
  'https://images-assets.nasa.gov/image/PIA09178/PIA09178~orig.jpg',
  'Nebulae',
  'NASA / ESA / Hubble',
  'https://science.nasa.gov/mission/hubble/science/science-highlights/pillars-of-creation/',
  '2024-10-18',
  2840
),
(
  'Andromeda Galaxy (M31)',
  'The Andromeda Galaxy is the nearest major spiral galaxy to the Milky Way, located about 2.5 million light-years away.',
  'مجرة أندروميدا هي أقرب مجرة حلزونية كبيرة إلى درب التبانة، تبعد نحو 2.5 مليون سنة ضوئية.',
  'https://images-assets.nasa.gov/image/PIA25533/PIA25533~orig.jpg',
  'Galaxies',
  'NASA',
  'https://science.nasa.gov/resource/andromeda-galaxy-m31/',
  '2024-08-01',
  3210
),
(
  'Orion Nebula',
  'A stellar nursery visible to the naked eye, where thousands of young stars are being born in clouds of gas and dust.',
  'حضانة نجمية مرئية بالعين المجردة، حيث يولد آلاف النجوم الشابة في سحب من الغاز والغبار.',
  'https://images-assets.nasa.gov/image/PIA18033/PIA18033~orig.jpg',
  'Nebulae',
  'NASA / Spitzer',
  'https://science.nasa.gov/mission/spitzer/multimedia/spitzer-images/orion/',
  '2024-06-15',
  1950
),
(
  'Saturn and Its Rings',
  'Saturn, the ringed giant, photographed by NASA''s Cassini spacecraft revealing intricate ring structure and atmospheric bands.',
  'زحل، العملاق ذو الحلقات، كما التقطته مركبة كاسيني التابعة لناسا، تُظهر بنية الحلقات المعقدة والنطاقات الجوية.',
  'https://images-assets.nasa.gov/image/PIA12567/PIA12567~orig.jpg',
  'Planets',
  'NASA / Cassini',
  'https://science.nasa.gov/saturn/',
  '2024-05-20',
  2100
),
(
  'Cosmic Cliffs (Carina Nebula)',
  'James Webb Space Telescope reveals previously hidden stellar nurseries in the Cosmic Cliffs of the Carina Nebula.',
  'تلسكوب جيمس ويب الفضائي يكشف حضانات نجمية كانت مخفية في منحدرات سديم كارينا الكونية.',
  'https://images-assets.nasa.gov/image/PIA25428/PIA25428~orig.jpg',
  'James Webb',
  'NASA / ESA / CSA / JWST',
  'https://webbtelescope.org/contents/media/images/2022/031/01G7DA5ADA2WDSK1JJPQ0PTG4A',
  '2024-07-12',
  4520
),
(
  'Jupiter''s Great Red Spot',
  'A centuries-old giant storm on Jupiter, larger than Earth, captured in detail by NASA''s Juno mission.',
  'عاصفة عملاقة عمرها قرون على المشتري، أكبر من الأرض، التقطت بتفاصيل دقيقة من مهمة جونو.',
  'https://images-assets.nasa.gov/image/PIA22946/PIA22946~orig.jpg',
  'Planets',
  'NASA / Juno',
  'https://www.missionjuno.swri.edu/jupiter/great-red-spot',
  '2024-04-10',
  1780
),
(
  'Sombrero Galaxy (M104)',
  'A brilliant disk galaxy with a prominent dust lane, resembling a wide-brimmed hat in deep space.',
  'مجرة قرصية لامعة بشريط غبار بارز، تشبه قبعة عريضة الحواف في الفضاء العميق.',
  'https://images-assets.nasa.gov/image/PIA05963/PIA05963~orig.jpg',
  'Galaxies',
  'NASA / Hubble',
  'https://science.nasa.gov/resource/sombrero-galaxy-m104/',
  '2024-03-22',
  1420
),
(
  'Crab Nebula',
  'The remnant of a supernova explosion recorded by Chinese astronomers in 1054 AD, powered by a pulsar at its center.',
  'بقايا انفجار مستعر أعظم سجله الفلكيون الصينيون عام 1054، يغذيه نجم نابض في مركزه.',
  'https://images-assets.nasa.gov/image/PIA03605/PIA03605~orig.jpg',
  'Nebulae',
  'NASA / Hubble',
  'https://science.nasa.gov/mission/hubble/science/science-highlights/crab-nebula/',
  '2024-02-14',
  1650
),
(
  'Earth from Apollo 17',
  'The Blue Marble — one of the most famous photographs of Earth, taken by the Apollo 17 crew during their journey to the Moon.',
  'الرخامة الزرقاء — من أشهر صور الأرض، التقطها طاقم أبولو 17 خلال رحلتهم إلى القمر.',
  'https://images-assets.nasa.gov/image/GSFC_20171208_Archive_e000132/GSFC_20171208_Archive_e000132~orig.jpg',
  'NASA',
  'NASA / Apollo 17',
  'https://earthobservatory.nasa.gov/images/84229/the-blue-marble',
  '2024-01-08',
  3890
),
(
  'Whirlpool Galaxy (M51)',
  'A classic face-on spiral galaxy interacting with a smaller companion, showcasing sweeping spiral arms.',
  'مجرة حلزونية كلاسيكية تتفاعل مع رفيقة أصغر، تُظهر أذرعاً حلزونية ممتدة.',
  'https://images-assets.nasa.gov/image/PIA23553/PIA23553~orig.jpg',
  'Galaxies',
  'NASA / Hubble',
  'https://science.nasa.gov/resource/whirlpool-galaxy-m51/',
  '2024-09-05',
  2200
),
(
  'Ring Nebula (M57)',
  'A planetary nebula formed when a Sun-like star shed its outer layers at the end of its life.',
  'سديم كوكبي تشكل عندما تخلص نجم شبيه بالشمس من طبقاته الخارجية في نهاية حياته.',
  'https://images-assets.nasa.gov/image/PIA17137/PIA17137~orig.jpg',
  'Stars',
  'NASA / Hubble',
  'https://science.nasa.gov/resource/ring-nebula-m57/',
  '2024-11-20',
  980
),
(
  'Mars: Valles Marineris',
  'The largest canyon system in the solar system, stretching across the Martian surface for thousands of kilometers.',
  'أكبر نظام أودية في المجموعة الشمسية، يمتد عبر سطح المريخ لآلاف الكيلومترات.',
  'https://images-assets.nasa.gov/image/PIA00422/PIA00422~orig.jpg',
  'Planets',
  'NASA / Viking',
  'https://science.nasa.gov/mars/',
  '2024-12-01',
  1340
);

INSERT INTO `photos_tags` (`photo_id`, `tag`) VALUES
(1, 'nebula'), (1, 'eagle'), (1, 'hubble'), (1, 'pillar'),
(2, 'galaxy'), (2, 'andromeda'), (2, 'm31'),
(3, 'nebula'), (3, 'orion'), (3, 'star'),
(4, 'planet'), (4, 'saturn'), (4, 'rings'),
(5, 'james'), (5, 'webb'), (5, 'jwst'), (5, 'carina'),
(6, 'planet'), (6, 'jupiter'), (6, 'storm'),
(7, 'galaxy'), (7, 'sombrero'), (7, 'm104'),
(8, 'nebula'), (8, 'crab'), (8, 'supernova'),
(9, 'earth'), (9, 'nasa'), (9, 'apollo'),
(10, 'galaxy'), (10, 'whirlpool'), (10, 'm51'),
(11, 'nebula'), (11, 'ring'), (11, 'star'),
(12, 'planet'), (12, 'mars'), (12, 'valles');

-- APOD table is populated by: php database/sync-apod.php
-- (uses NASA API with real daily images)

-- ============================================================================
-- Astronomical events — real 2026 events with NASA images
-- ============================================================================

INSERT INTO `astronomical_events`
  (`event_title`, `event_description`, `event_image`, `event_date`, `event_end`, `status`, `best_viewing_method`, `light_pollution_viewability`, `best_viewing_location`)
VALUES
(
  'Perseid Meteor Shower 2026',
  '<p>The Perseids are one of the best meteor showers of the year, producing up to 100 meteors per hour at peak. Active from July 17 to August 24, with peak nights on <strong>August 12–13, 2026</strong>. Best viewed after midnight in a dark location away from city lights.</p>',
  'https://images-assets.nasa.gov/image/PIA21447/PIA21447~orig.jpg',
  '2026-07-17',
  '2026-08-24',
  'Coming',
  'Eye',
  'Yes',
  'Dark rural skies, Northern Hemisphere'
),
(
  'Saturn at Opposition 2026',
  '<p>Saturn reaches opposition on <strong>September 7, 2026</strong>, appearing brightest and largest in the night sky. A small telescope reveals its magnificent ring system and largest moon Titan.</p>',
  'https://images-assets.nasa.gov/image/PIA12567/PIA12567~orig.jpg',
  '2026-09-07',
  '2026-09-07',
  'Coming',
  'Telescope',
  'Yes',
  'Worldwide, visible all night'
),
(
  'Draconid Meteor Shower 2026',
  '<p>The Draconids peak on the night of <strong>October 8, 2026</strong>. Although typically a minor shower (10 meteors/hour), outbursts can occasionally produce hundreds of meteors per hour.</p>',
  'https://images-assets.nasa.gov/image/PIA21447/PIA21447~orig.jpg',
  '2026-10-08',
  '2026-10-09',
  'Coming',
  'Eye',
  'Yes',
  'Northern Hemisphere, evening hours'
),
(
  'Orionid Meteor Shower 2026',
  '<p>The Orionids peak on <strong>October 21–22, 2026</strong>, producing about 20 meteors per hour. Debris from Halley''s Comet creates fast, bright meteors with persistent trails.</p>',
  'https://images-assets.nasa.gov/image/PIA21447/PIA21447~orig.jpg',
  '2026-10-21',
  '2026-10-22',
  'Coming',
  'Eye',
  'Yes',
  'Worldwide, after midnight toward the constellation Orion'
),
(
  'Leonid Meteor Shower 2026',
  '<p>The Leonids peak on <strong>November 17–18, 2026</strong>. Known for producing bright fireballs and, in exceptional years, meteor storms with thousands of meteors per hour.</p>',
  'https://images-assets.nasa.gov/image/PIA21447/PIA21447~orig.jpg',
  '2026-11-17',
  '2026-11-18',
  'Coming',
  'Eye',
  'Yes',
  'Worldwide, radiant rises after midnight in Leo'
),
(
  'Geminid Meteor Shower 2026',
  '<p>The Geminids are the king of meteor showers, peaking on <strong>December 13–14, 2026</strong> with up to 120 multicolored meteors per hour. One of the few major showers best viewed in the evening.</p>',
  'https://images-assets.nasa.gov/image/PIA21447/PIA21447~orig.jpg',
  '2026-12-13',
  '2026-12-14',
  'Coming',
  'Eye',
  'Yes',
  'Worldwide, evening to dawn'
);
