// Block Sentry requests from temp-mail.io domain
(function() {
    if (typeof window === 'undefined') return;
    
    // Block fetch requests
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0];
        if (typeof url === 'string' && url.includes('sentry-internal.temp-mail.io')) {
            console.warn('Blocked Sentry request to temp-mail.io');
            return Promise.resolve(new Response(null, { status: 200 }));
        }
        return originalFetch.apply(this, args);
    };
    
    // Block XMLHttpRequest
    const originalXHROpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(method, url, ...args) {
        if (typeof url === 'string' && url.includes('sentry-internal.temp-mail.io')) {
            console.warn('Blocked Sentry XHR request to temp-mail.io');
            // Override send to do nothing
            this.send = function() {};
            return;
        }
        return originalXHROpen.call(this, method, url, ...args);
    };
    
    // Console override to filter Sentry errors
    const originalError = console.error;
    console.error = function(...args) {
        const message = args.join(' ');
        if (message.includes('sentry-internal.temp-mail.io') && message.includes('403')) {
            return; // Silently drop Sentry 403 errors
        }
        return originalError.apply(console, args);
    };
    
    console.log('Sentry blocker initialized');
})();
