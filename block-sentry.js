// Block Sentry requests from temp-mail.io domain
if (typeof window !== 'undefined') {
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0];
        if (typeof url === 'string' && url.includes('sentry-internal.temp-mail.io')) {
            console.warn('Blocked Sentry request to temp-mail.io');
            return Promise.resolve(new Response(null, { status: 200 }));
        }
        return originalFetch.apply(this, args);
    };
}
