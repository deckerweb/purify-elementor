/** Host-owned footer controls; embedded Library runtime files remain unchanged. */
(() => {
    'use strict';
    const key = Symbol.for('deckerweb.purifyElementor.footerDialogs.v1');
    if (window[key]) return;
    window[key] = true;
    const openers = new WeakMap();
    const owned = dialog => typeof window.HTMLDialogElement === 'function' && dialog instanceof window.HTMLDialogElement && ['ddw-pe-dialog', 'dwl-history'].includes(dialog.id);
    /** Restore the closed state and focus even if an instance method was replaced. */
    const finish = dialog => {
        dialog.hidden = true;
        const opener = openers.get(dialog);
        if (opener?.isConnected) opener.focus({preventScroll: true});
    };
    /** Use native dialog behavior, retaining attribute cleanup for damaged dialogs. */
    const dismiss = dialog => {
        try {
            const nativeClose = window.HTMLDialogElement?.prototype.close;
            if (dialog.open && typeof nativeClose === 'function') nativeClose.call(dialog);
        } catch (_) { /* Closed-state cleanup below remains available. */ }
        dialog.removeAttribute('open');
        finish(dialog);
    };
    // Window capture precedes delegated document/target listeners; only owned controls stop propagation.
    window.addEventListener('click', event => {
        if (!(event.target instanceof Element)) return;
        const closer = event.target.closest('#ddw-pe-dialog-close, #dwl-history [data-dwl-close]');
        const dialog = closer?.closest('dialog');
        if (closer && owned(dialog)) {
            event.preventDefault();
            event.stopImmediatePropagation();
            dismiss(dialog);
            return;
        }
        const opener = event.target.closest('#ddw-pe-history, [data-dwl-history]');
        if (opener) {
            const target = document.getElementById(opener.id === 'ddw-pe-history' ? 'ddw-pe-dialog' : 'dwl-history');
            const nativeShow = window.HTMLDialogElement?.prototype.showModal;
            if (!owned(target) || typeof nativeShow !== 'function') return;
            try {
                target.hidden = false;
                if (!target.open) nativeShow.call(target);
            } catch (_) {
                dismiss(target);
                return; // Preserve a real changelog link when native opening fails.
            }
            openers.set(target, opener);
            event.preventDefault();
            event.stopImmediatePropagation();
            target.querySelector('[autofocus]')?.focus();
            return;
        }
        if (owned(event.target) && event.target.open) {
            const bounds = event.target.getBoundingClientRect();
            if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) dismiss(event.target);
        }
    }, true);
    window.addEventListener('keydown', event => {
        const dialog = event.target instanceof Element ? event.target.closest('dialog') : null;
        if (!owned(dialog) || !dialog.open) return;
        if (event.key === 'Tab') {
            const items = [...dialog.querySelectorAll('button, a[href], input:not([type="hidden"]), select, textarea, [tabindex]')]
                .filter(item => !item.disabled && item.tabIndex >= 0 && item.getClientRects().length);
            const first = items[0], last = items[items.length - 1];
            if (first && ((event.shiftKey && document.activeElement === first) || (!event.shiftKey && document.activeElement === last))) {
                event.preventDefault();
                event.stopImmediatePropagation();
                (event.shiftKey ? last : first).focus();
            }
            return;
        }
        if (event.key !== 'Escape') return;
        event.preventDefault();
        event.stopImmediatePropagation();
        dismiss(dialog);
    }, true);
    window.addEventListener('cancel', event => {
        if (!owned(event.target)) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        dismiss(event.target);
    }, true);
    window.addEventListener('close', event => {
        if (owned(event.target) && !event.target.open) finish(event.target);
    }, true);
})();
