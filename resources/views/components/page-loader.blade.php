{{-- ============================================================ --}}
{{-- GLOBAL PAGE LOADING OVERLAY                                 --}}
{{-- Premium glassmorphic loader with KKP branding               --}}
{{-- ============================================================ --}}
<div id="pageLoader" class="page-loader">
    <div class="page-loader-content">
        <div class="loader-spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-dot"></div>
        </div>
        <p class="loader-text">Memuat<span class="loader-dots"></span></p>
    </div>
    {{-- Top progress bar --}}
    <div class="loader-progress">
        <div class="loader-progress-bar" id="loaderProgressBar"></div>
    </div>
</div>

<style>
/* ============================================================
   PAGE LOADER — Premium Glassmorphic Overlay
   ============================================================ */
.page-loader {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-loader.active {
    opacity: 1;
    visibility: visible;
}

.page-loader.fade-out {
    opacity: 0;
    visibility: hidden;
}

/* Loader Content Card */
.page-loader-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
    padding: 2rem 2.5rem;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset;
    transform: scale(0.92);
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-loader.active .page-loader-content {
    transform: scale(1);
}

/* Spinner */
.loader-spinner {
    position: relative;
    width: 48px;
    height: 48px;
}

.spinner-ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 3px solid transparent;
}

.spinner-ring:nth-child(1) {
    border-top-color: #0891B2;
    animation: spinRing 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
}

.spinner-ring:nth-child(2) {
    border-right-color: #06B6D4;
    animation: spinRing 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    animation-delay: -0.4s;
}

.spinner-ring:nth-child(3) {
    border-bottom-color: rgba(6, 182, 212, 0.3);
    animation: spinRing 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    animation-delay: -0.8s;
}

.spinner-dot {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 8px;
    height: 8px;
    margin: -4px 0 0 -4px;
    border-radius: 50%;
    background: #22D3EE;
    animation: pulseDot 1.2s ease-in-out infinite;
}

@keyframes spinRing {
    0%   { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes pulseDot {
    0%, 100% { transform: scale(0.8); opacity: 0.6; }
    50%      { transform: scale(1.3); opacity: 1; }
}

/* Text */
.loader-text {
    margin: 0;
    font-size: 0.85rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.85);
    letter-spacing: 0.03em;
    font-family: 'Poppins', sans-serif;
}

.loader-dots {
    display: inline-block;
    width: 16px;
    text-align: left;
}

.loader-dots::after {
    content: '';
    animation: loadingDots 1.4s steps(4, end) infinite;
}

@keyframes loadingDots {
    0%   { content: ''; }
    25%  { content: '.'; }
    50%  { content: '..'; }
    75%  { content: '...'; }
    100% { content: ''; }
}

/* Top Progress Bar */
.loader-progress {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.08);
    overflow: hidden;
}

.loader-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #0891B2, #22D3EE, #06B6D4);
    border-radius: 0 3px 3px 0;
    transition: width 0.3s ease;
    animation: progressIndeterminate 1.8s ease-in-out infinite;
}

@keyframes progressIndeterminate {
    0%   { width: 0%; margin-left: 0%; }
    50%  { width: 40%; margin-left: 30%; }
    100% { width: 0%; margin-left: 100%; }
}
</style>

<script>
(function() {
    const loader = document.getElementById('pageLoader');
    if (!loader) return;

    // Show loader on page navigation
    function showLoader() {
        loader.classList.remove('fade-out');
        loader.classList.add('active');
    }

    // Hide loader
    function hideLoader() {
        loader.classList.add('fade-out');
        setTimeout(() => {
            loader.classList.remove('active', 'fade-out');
        }, 350);
    }

    // 1. Intercept all link clicks (except # anchors, javascript:, new tabs)
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href) return;
        if (href.startsWith('#') || href.startsWith('javascript:')) return;
        if (link.target === '_blank' || link.target === '_new') return;
        if (link.hasAttribute('download')) return;
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;
        // Don't show for onclick handlers that prevent default
        if (link.getAttribute('onclick') && link.getAttribute('onclick').includes('preventDefault')) return;

        showLoader();
    });

    // 2. Intercept all form submissions
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;

        // Don't show for AJAX forms
        if (form.hasAttribute('data-no-loader')) return;

        showLoader();
    });

    // 3. Hide loader when page finishes loading (for back/forward navigation)
    window.addEventListener('pageshow', function(e) {
        hideLoader();
    });

    // 4. Also hide on DOMContentLoaded as safety net
    document.addEventListener('DOMContentLoaded', function() {
        hideLoader();
    });

    // 5. Hide if page loaded from cache (bfcache)
    window.addEventListener('load', function() {
        setTimeout(hideLoader, 100);
    });
})();
</script>
