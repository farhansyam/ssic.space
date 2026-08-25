import Alpine from 'alpinejs';
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// x-reveal: fades + slides an element up into place the first time it scrolls into view.
Alpine.directive('reveal', (el, { modifiers }) => {
    const delay = Number(modifiers.find((m) => !isNaN(m))) || 0;
    el.style.opacity = '0';
    el.style.transform = 'translateY(24px)';
    el.style.transition = `opacity 600ms ease-out ${delay}ms, transform 600ms ease-out ${delay}ms`;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.15 });

    observer.observe(el);
});

window.Alpine = Alpine;
Alpine.start();

// Retro 8-bit pixel transition: chunky pixel-dissolve on page load + between internal page navigations.
// Only runs on pages that opt in via <body data-pixel-transition="true">.
(function initPixelTransition() {
    if (document.body?.dataset?.pixelTransition !== 'true') return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const COLS = 12;
    const ROWS = 7;
    const STEP_MS = 400;
    const MAX_STAGGER_MS = 260;

    function buildOverlay() {
        const overlay = document.createElement('div');
        overlay.setAttribute('aria-hidden', 'true');
        overlay.style.cssText = [
            'position:fixed', 'inset:0', 'z-index:99999', 'display:grid',
            `grid-template-columns:repeat(${COLS},1fr)`, `grid-template-rows:repeat(${ROWS},1fr)`,
            'pointer-events:none',
        ].join(';');

        const cells = [];
        const maxDelaySpan = ROWS + COLS;

        for (let r = 0; r < ROWS; r++) {
            for (let c = 0; c < COLS; c++) {
                const cell = document.createElement('div');
                const tone = (r + c) % 2 === 0
                    ? 'var(--color-primary-700, #1d5ca6)'
                    : 'var(--color-primary-900, #15365d)';
                const delay = Math.round(((r + c) / maxDelaySpan) * MAX_STAGGER_MS);
                cell.style.cssText = [
                    `background-color:${tone}`,
                    'transform:scale(1)',
                    `transition:transform ${STEP_MS}ms steps(4,end) ${delay}ms`,
                ].join(';');
                overlay.appendChild(cell);
                cells.push(cell);
            }
        }

        document.body.appendChild(overlay);
        return { overlay, cells, totalMs: STEP_MS + MAX_STAGGER_MS };
    }

    function reveal() {
        const { overlay, cells, totalMs } = buildOverlay();
        requestAnimationFrame(() => requestAnimationFrame(() => {
            cells.forEach((cell) => { cell.style.transform = 'scale(0)'; });
        }));
        setTimeout(() => overlay.remove(), totalMs + 60);
    }

    function coverThenGo(href) {
        const { overlay, cells, totalMs } = buildOverlay();
        cells.forEach((cell) => { cell.style.transform = 'scale(0)'; });
        // Force reflow so the browser registers the scale(0) start state before animating in.
        void overlay.offsetHeight;
        requestAnimationFrame(() => {
            cells.forEach((cell) => { cell.style.transform = 'scale(1)'; });
        });
        setTimeout(() => { window.location.href = href; }, totalMs);
    }

    function isTransitionable(anchor) {
        if (!anchor || !anchor.href) return false;
        if (anchor.target && anchor.target !== '_self') return false;
        if (anchor.hasAttribute('download')) return false;
        const href = anchor.getAttribute('href') || '';
        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return false;

        let url;
        try {
            url = new URL(anchor.href, window.location.href);
        } catch {
            return false;
        }

        if (url.origin !== window.location.origin) return false;
        if (url.href.split('#')[0] === window.location.href.split('#')[0]) return false;

        return true;
    }

    document.addEventListener('click', (event) => {
        if (event.defaultPrevented || event.button !== 0) return;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

        const anchor = event.target.closest('a');
        if (!isTransitionable(anchor)) return;

        event.preventDefault();
        coverThenGo(anchor.href);
    });

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        reveal();
    } else {
        document.addEventListener('DOMContentLoaded', reveal);
    }
})();
