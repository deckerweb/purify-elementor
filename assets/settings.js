/* Scoped compatibility for the native WordPress settings form. */
(() => {
    'use strict';
    /* Keep the native Settings API fields; relocate only the routing value that
     * shadows form.action in Elementor 4.3.3–4.3.5 dashboard tracking. No-JS
     * submissions retain the unmodified WordPress fields and target. */
    const prepareSettingsForm = () => {
        const form = document.getElementById('ddw-pe-settings-form');
        if (!form || form.dataset.ddwPeTrackingWorkaround !== '1' || form.method !== 'post') return;
        const fields = form.querySelectorAll('input[type="hidden"][name="action"]');
        if (fields.length !== 1 || fields[0].value !== 'update') return;
        const target = new URL(form.getAttribute('action'), window.location.href);
        const nativeTarget = new URL('options.php', window.location.href);
        if (target.origin !== window.location.origin || target.pathname !== nativeTarget.pathname) return;
        target.searchParams.set('action', 'update');
        form.setAttribute('action', target.href);
        fields[0].remove();
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', prepareSettingsForm);
    else prepareSettingsForm();
})();
