/**
 * Cosmixify — Solar system pill navigator
 */
(function () {
    'use strict';

    var app = document.getElementById('solar-app');
    if (!app) return;

    var pills = app.querySelectorAll('.solar-pill');
    var panels = app.querySelectorAll('.solar-planet');
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function selectPlanet(planetId) {
        if (!planetId || app.dataset.current === planetId) return;

        app.dataset.current = planetId;

        pills.forEach(function (pill) {
            var active = pill.dataset.planet === planetId;
            pill.classList.toggle('is-active', active);
            pill.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        panels.forEach(function (panel) {
            panel.classList.toggle('is-active', panel.dataset.planet === planetId);
        });

        app.dispatchEvent(new CustomEvent('solar:change', { detail: { planet: planetId } }));
    }

    pills.forEach(function (pill, index) {
        pill.addEventListener('click', function () {
            selectPlanet(pill.dataset.planet);
        });

        pill.addEventListener('keydown', function (e) {
            var next = index;

            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                next = (index + 1) % pills.length;
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                next = (index - 1 + pills.length) % pills.length;
            } else if (e.key === 'Home') {
                e.preventDefault();
                next = 0;
            } else if (e.key === 'End') {
                e.preventDefault();
                next = pills.length - 1;
            } else {
                return;
            }

            pills[next].focus();
            selectPlanet(pills[next].dataset.planet);
        });
    });

    if (!reduced) {
        app.addEventListener('solar:change', function () {
            var activeImg = app.querySelector('.solar-planet.is-active .solar-planet__img');
            if (!activeImg) return;
            activeImg.style.animation = 'none';
            void activeImg.offsetWidth;
            activeImg.style.animation = '';
        });
    }
})();
