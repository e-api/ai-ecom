/**
 * Custom Toast Notification System
 * Lightweight, bottom-left positioned, React-style notifications
 * Usage: Toast.success('Message'), Toast.error('Message'), Toast.info('Message')
 */
(function() {
    'use strict';

    var container = null;

    function getContainer() {
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position:fixed;bottom:20px;left:20px;z-index:99999;display:flex;flex-direction:column-reverse;gap:8px;max-width:380px;width:100%;pointer-events:none';
            document.body.appendChild(container);
        }
        return container;
    }

    function createToast(message, type) {
        var c = getContainer();
        var toast = document.createElement('div');

        var bgColor, iconColor, iconSvg;
        switch (type) {
            case 'success':
                bgColor = '#065f46';
                iconColor = '#34d399';
                iconSvg = '<svg class="toast-icon" viewBox="0 0 20 20" fill="' + iconColor + '" style="width:18px;height:18px;flex-shrink:0"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
                break;
            case 'error':
                bgColor = '#7f1d1d';
                iconColor = '#fca5a5';
                iconSvg = '<svg class="toast-icon" viewBox="0 0 20 20" fill="' + iconColor + '" style="width:18px;height:18px;flex-shrink:0"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
                break;
            case 'info':
            default:
                bgColor = '#1e3a5f';
                iconColor = '#93c5fd';
                iconSvg = '<svg class="toast-icon" viewBox="0 0 20 20" fill="' + iconColor + '" style="width:18px;height:18px;flex-shrink:0"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';
                break;
        }

        toast.style.cssText = 'pointer-events:auto;display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:8px;background:' + bgColor + ';color:#fff;font-size:14px;line-height:1.4;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;box-shadow:0 4px 12px rgba(0,0,0,0.25);transform:translateX(-120%);opacity:0;transition:transform 0.35s cubic-bezier(0.22,1,0.36,1),opacity 0.35s ease';

        toast.innerHTML = iconSvg + '<span style="flex:1;min-width:0">' + message + '</span>';

        c.appendChild(toast);

        // Trigger slide-in
        requestAnimationFrame(function() {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });

        // Auto-dismiss after 4 seconds
        var dismissTimer = setTimeout(function() {
            dismiss(toast);
        }, 4000);

        // Dismiss on click
        toast.addEventListener('click', function() {
            clearTimeout(dismissTimer);
            dismiss(toast);
        });

        return toast;
    }

    function dismiss(toast) {
        if (!toast || toast._dismissing) return;
        toast._dismissing = true;
        toast.style.transform = 'translateX(-120%)';
        toast.style.opacity = '0';
        setTimeout(function() {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 350);
    }

    // Expose global Toast API
    window.Toast = {
        success: function(msg) { return createToast(msg, 'success'); },
        error: function(msg) { return createToast(msg, 'error'); },
        info: function(msg) { return createToast(msg, 'info'); }
    };
})();