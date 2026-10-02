/* Keep the native dialog's focus trap; guard against globally styled dialogs. */
(() => {
    'use strict';
    const init = () => {
        const trigger = document.getElementById('ddw-pe-history');
        const dialog = document.getElementById('ddw-pe-dialog');
        const close = document.getElementById('ddw-pe-dialog-close');
        if (!trigger || !dialog || !close) return;
        dialog.hidden = true;
        trigger.addEventListener('click', () => {
            if (typeof dialog.showModal !== 'function' || dialog.open) return;
            dialog.hidden = false;
            dialog.showModal();
            close.focus();
        });
        const dismiss = () => { if (dialog.open) dialog.close(); };
        close.addEventListener('click', dismiss);
        dialog.addEventListener('cancel', event => { event.preventDefault(); dismiss(); });
        dialog.addEventListener('close', () => { dialog.hidden = true; trigger.focus(); });
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
