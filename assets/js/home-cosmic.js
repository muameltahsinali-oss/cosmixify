/**
 * Cosmixify homepage — cosmic motion (GSAP + ScrollTrigger + Lenis)
 */
(function () {
    'use strict';

    if (!document.body.classList.contains('home-page')) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const mobile = window.matchMedia('(max-width: 768px)').matches;
    const rtl = document.documentElement.dir === 'rtl';

    document.body.classList.add(reduced ? 'cosmic-reduced' : 'cosmic-ready');

    if (typeof gsap === 'undefined') return;

    if (!reduced && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
    }

    let lenis = null;
    let starfield = null;
    const hero = document.querySelector('.hero-split');

    function initStarfield() {
        if (reduced) return null;

        const canvas = document.querySelector('.cosmic-canvas');
        if (!canvas || !hero) return null;

        const ctx = canvas.getContext('2d');
        const count = mobile ? 36 : 72;
        const stars = [];
        let scrollY = 0;
        let running = true;

        function resize() {
            const rect = hero.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
        }

        function seed() {
            stars.length = 0;
            for (let i = 0; i < count; i++) {
                stars.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    r: Math.random() * 1.4 + 0.3,
                    a: Math.random(),
                    speed: Math.random() * 0.15 + 0.05,
                    layer: Math.random() > 0.6 ? 1 : 0,
                    twinkle: Math.random() * Math.PI * 2,
                });
            }
        }

        function draw() {
            if (!running || document.hidden) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach((s) => {
                s.twinkle += 0.02;
                const flicker = 0.55 + Math.sin(s.twinkle) * 0.45;
                const parallax = scrollY * (s.layer ? 0.08 : 0.03);
                let y = (s.y + parallax) % canvas.height;
                if (y < 0) y += canvas.height;
                ctx.beginPath();
                ctx.fillStyle = 'rgba(245, 247, 250, ' + (s.a * flicker * 0.55) + ')';
                ctx.arc(s.x, y, s.r, 0, Math.PI * 2);
                ctx.fill();
                s.y -= s.speed;
                if (s.y < -2) s.y = canvas.height + 2;
            });
            requestAnimationFrame(draw);
        }

        resize();
        seed();
        requestAnimationFrame(draw);

        window.addEventListener('resize', function () { resize(); seed(); });
        document.addEventListener('visibilitychange', function () {
            running = !document.hidden;
            if (running) draw();
        });

        return {
            setScroll: function (y) { scrollY = y; },
            fadeIn: function () {
                gsap.to(canvas, { opacity: 1, duration: 1.2, ease: 'power2.out' });
            },
        };
    }

    function initLenis() {
        if (reduced || typeof Lenis === 'undefined') return null;

        if (typeof ScrollTrigger === 'undefined') return null;

        const instance = new Lenis({
            duration: 1.1,
            smoothWheel: true,
            touchMultiplier: 1.2,
        });

        instance.on('scroll', ScrollTrigger.update);

        ScrollTrigger.scrollerProxy(document.documentElement, {
            scrollTop: function (value) {
                if (arguments.length) {
                    instance.scrollTo(value, { immediate: true });
                }
                return instance.scroll;
            },
            getBoundingClientRect: function () {
                return { top: 0, left: 0, width: window.innerWidth, height: window.innerHeight };
            },
        });

        ScrollTrigger.addEventListener('refresh', function () { instance.resize(); });

        gsap.ticker.add(function (time) { instance.raf(time * 1000); });
        gsap.ticker.lagSmoothing(0);

        return instance;
    }

    function initHeroEntrance() {
        const visual = document.querySelector('.hero-split__visual');
        const astronaut = document.querySelector('.hero-split__astronaut');
        const words = document.querySelectorAll('.hero-split__title .cosmic-word');
        const lines = document.querySelectorAll('.hero-split__lead .cosmic-line-inner, .hero-split__desc .cosmic-line-inner');
        const buttons = document.querySelectorAll('.hero-split__actions .btn-cosmic');
        const langSwitcher = document.querySelector('.hero-split__top');
        const glow = document.querySelector('.cosmic-glow');

        if (reduced) {
            gsap.set([visual, words, lines, buttons, langSwitcher], { opacity: 1, clearProps: 'transform' });
            if (astronaut) gsap.set(astronaut, { clearProps: 'all' });
            if (starfield) starfield.fadeIn();
            return;
        }

        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

        if (starfield) tl.add(function () { starfield.fadeIn(); }, 0);

        if (visual && astronaut) {
            tl.fromTo(visual, { opacity: 0 }, { opacity: 1, duration: 0.3 }, 0);
            tl.fromTo(astronaut, { y: 40, opacity: 0, rotation: -2 }, { y: 0, opacity: 1, rotation: 0, duration: 0.9 }, 0.15);
        }

        if (words.length) {
            tl.fromTo(words, { yPercent: 110 }, { yPercent: 0, opacity: 1, duration: 0.75, stagger: 0.06 }, 0.35);
        }

        if (lines.length) {
            tl.fromTo(lines, { y: 24, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, stagger: 0.12 }, 0.55);
        }

        if (buttons.length) {
            tl.fromTo(buttons, { scale: 0.9, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.5, stagger: 0.1 }, 0.75);
        }

        if (langSwitcher) {
            tl.fromTo(langSwitcher, { y: -12, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5 }, 0.2);
        }

        if (!mobile && astronaut && hero) {
            const ax = gsap.quickTo(astronaut, 'x', { duration: 0.6, ease: 'power2.out' });
            const ay = gsap.quickTo(astronaut, 'y', { duration: 0.6, ease: 'power2.out' });
            const gx = glow ? gsap.quickTo(glow, 'x', { duration: 1.2, ease: 'power2.out' }) : null;
            const gy = glow ? gsap.quickTo(glow, 'y', { duration: 1.2, ease: 'power2.out' }) : null;

            hero.addEventListener('mousemove', function (e) {
                const rect = hero.getBoundingClientRect();
                const px = (e.clientX - rect.left) / rect.width - 0.5;
                const py = (e.clientY - rect.top) / rect.height - 0.5;
                ax(px * 30);
                ay(py * 20);
                if (gx && gy) {
                    gx(px * -20);
                    gy(py * -15);
                }
            });
        }

        if (hero && typeof ScrollTrigger !== 'undefined') {
            const text = document.querySelector('.hero-split__text');
            if (visual) {
                gsap.to(visual, {
                    y: 80,
                    ease: 'none',
                    scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1 },
                });
            }
            if (text) {
                gsap.to(text, {
                    y: 40,
                    ease: 'none',
                    scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1 },
                });
            }
        }
    }

    function initScrollReveals() {
        if (reduced || typeof ScrollTrigger === 'undefined') {
            gsap.set('[data-cosmic-reveal], .category-bento, .event-bento-card', { opacity: 1, y: 0, scale: 1, clearProps: 'all' });
            gsap.set('.home-solar .section-title', { opacity: 1, y: 0, scale: 1, clearProps: 'all' });
            return;
        }

        document.querySelectorAll('.section-head[data-cosmic-reveal]').forEach(function (head) {
            const eyebrow = head.querySelector('.section-eyebrow');
            const title = head.querySelector('.section-title');
            const link = head.querySelector('.btn-cosmic');

            gsap.set(head, { opacity: 1, y: 0 });

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: head,
                    start: 'top 88%',
                    toggleActions: 'play none none reverse',
                },
            });

            if (eyebrow) {
                tl.fromTo(eyebrow, { x: rtl ? 30 : -30, opacity: 0 }, { x: 0, opacity: 1, duration: 0.5 }, 0);
            }
            if (title) {
                tl.fromTo(title, { y: 28, opacity: 0 }, { y: 0, opacity: 1, duration: 0.55 }, 0.1);
            }
            if (link) {
                tl.fromTo(link, { opacity: 0 }, { opacity: 1, duration: 0.4 }, 0.25);
            }
        });

        gsap.set('.bento-card[data-cosmic-reveal]', { opacity: 0, y: 48, scale: 0.96 });

        ScrollTrigger.batch('.bento-card[data-cosmic-reveal]', {
            start: 'top 90%',
            onEnter: function (batch) {
                gsap.to(batch, {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 0.65,
                    stagger: 0.08,
                    ease: 'power3.out',
                    overwrite: true,
                });
            },
            onLeaveBack: function (batch) {
                gsap.to(batch, { opacity: 0, y: 48, scale: 0.96, duration: 0.3, stagger: 0.04, overwrite: true });
            },
        });

        document.querySelectorAll('.bento-card--parallax img').forEach(function (img) {
            gsap.to(img, {
                yPercent: -8,
                ease: 'none',
                scrollTrigger: {
                    trigger: img.closest('.bento-card'),
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 1,
                },
            });
        });

        gsap.set('.category-bento', { opacity: 0, y: 40 });

        ScrollTrigger.batch('.category-bento', {
            start: 'top 92%',
            onEnter: function (batch) {
                batch.forEach(function (el, i) {
                    const icon = el.querySelector('.category-bento__icon');
                    gsap.fromTo(el, { opacity: 0, y: 40 }, { opacity: 1, y: 0, duration: 0.6, delay: i * 0.06, ease: 'power3.out' });
                    if (icon) {
                        gsap.fromTo(icon, { rotation: -8, scale: 0.85 }, { rotation: 0, scale: 1, duration: 0.7, delay: i * 0.06, ease: 'back.out(1.4)' });
                    }
                });
            },
        });

        document.querySelectorAll('.event-bento-card').forEach(function (card, i) {
            const fromX = (i % 2 === 0) ? (rtl ? 40 : -40) : (rtl ? -40 : 40);
            gsap.fromTo(card,
                { opacity: 0, x: fromX, y: 30 },
                {
                    opacity: 1, x: 0, y: 0, duration: 0.7, ease: 'power3.out',
                    scrollTrigger: { trigger: card, start: 'top 90%', toggleActions: 'play none none reverse' },
                }
            );
        });

        const solarSection = document.querySelector('.home-solar');
        const solarTitle = document.querySelector('.home-solar .section-title');
        if (solarTitle && solarSection) {
            gsap.fromTo(solarTitle,
                { opacity: 0, y: 32, scale: 0.98 },
                {
                    opacity: 1, y: 0, scale: 1, duration: 0.7, ease: 'power3.out',
                    scrollTrigger: { trigger: solarSection, start: 'top 85%', once: true },
                }
            );
        }
    }

    function initAltSectionLines() {
        if (reduced || typeof ScrollTrigger === 'undefined') return;

        document.querySelectorAll('.home-section--alt').forEach(function (section) {
            ScrollTrigger.create({
                trigger: section,
                start: 'top 82%',
                onEnter: function () { section.classList.add('cosmic-line-visible'); },
                onLeaveBack: function () { section.classList.remove('cosmic-line-visible'); },
            });
        });
    }

    function initMicroInteractions() {
        if (reduced || mobile) return;

        document.querySelectorAll('.home-page .btn-cosmic').forEach(function (btn) {
            btn.addEventListener('mousemove', function (e) {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                gsap.to(btn, { x: x * 0.2, y: y * 0.25, duration: 0.3, ease: 'power2.out' });
            });
            btn.addEventListener('mouseleave', function () {
                gsap.to(btn, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.6)' });
            });
        });

        document.querySelectorAll('.home-page .bento-card').forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                const rect = card.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                gsap.to(card, {
                    rotateY: x * 8,
                    rotateX: -y * 8,
                    transformPerspective: 800,
                    duration: 0.35,
                    ease: 'power2.out',
                });
            });
            card.addEventListener('mouseleave', function () {
                gsap.to(card, { rotateY: 0, rotateX: 0, duration: 0.5, ease: 'power2.out' });
            });
        });
    }

    function initBottomNavSpy() {
        const sections = document.querySelectorAll('section[id], header[id]');
        const navLinks = document.querySelectorAll('.bottom-nav__item');

        function updateSpy() {
            const scrollPos = lenis ? lenis.scroll : window.scrollY;
            if (starfield) starfield.setScroll(scrollPos);

            let current = '';
            sections.forEach(function (section) {
                if (scrollPos >= section.offsetTop - 200) {
                    current = section.getAttribute('id') || '';
                }
            });

            navLinks.forEach(function (link) {
                const href = link.getAttribute('href') || '';
                link.classList.toggle('active', href === '#' + current);
            });
        }

        if (lenis) {
            lenis.on('scroll', updateSpy);
        } else {
            window.addEventListener('scroll', updateSpy, { passive: true });
        }
        updateSpy();
    }

    function initPopup() {
        const popup = document.getElementById('welcomePopup');
        if (!popup) return;

        const container = popup.querySelector('.popup-container');

        window.closePopup = function closePopup() {
            if (reduced) {
                popup.classList.remove('is-visible');
                popup.style.display = 'none';
            } else if (container) {
                gsap.to(container, { scale: 0.92, opacity: 0, duration: 0.35, ease: 'power2.in' });
                gsap.to(popup, {
                    opacity: 0, duration: 0.3, delay: 0.05,
                    onComplete: function () {
                        popup.classList.remove('is-visible');
                        popup.style.display = 'none';
                    },
                });
            } else {
                popup.style.display = 'none';
            }
            localStorage.setItem('hasVisited', 'true');
        };

        if (!localStorage.getItem('hasVisited')) {
            popup.style.display = 'flex';
            popup.classList.add('is-visible');
            if (!reduced && container) {
                gsap.fromTo(popup, { opacity: 0 }, { opacity: 1, duration: 0.4 });
                gsap.fromTo(container, { scale: 0.92, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.5, ease: 'back.out(1.4)', delay: 0.1 });
            } else {
                popup.style.opacity = '1';
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && popup.classList.contains('is-visible')) {
                window.closePopup();
            }
        });
    }

    function init() {
        starfield = initStarfield();
        lenis = initLenis();
        initHeroEntrance();
        initScrollReveals();
        initAltSectionLines();
        initMicroInteractions();
        initBottomNavSpy();
        initPopup();

        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.refresh();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
