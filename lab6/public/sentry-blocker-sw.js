// Service Worker to block Sentry requests at network level
self.addEventListener('fetch', function(event) {
  const url = event.request.url;
  
  // Block all Sentry and temp-mail related requests with aggressive patterns
  if (url.includes('sentry-internal.temp-mail.io') || 
      url.includes('temp-mail.io/api') ||
      url.includes('sentry_key') ||
      url.includes('sentry_version') ||
      url.includes('sentry_client') ||
      url.includes('sentry.javascript.vue') ||
      url.includes('/envelope/') ||
      (url.includes('temp-mail.io') && url.includes('api'))) {
    console.log('Service Worker: Blocked request to', url);
    // Respond with a mock successful response to prevent errors
    event.respondWith(
      new Response(JSON.stringify({}), {
        status: 200,
        statusText: 'OK',
        headers: {
          'Content-Type': 'application/json'
        }
      })
    );
    return;
  }
  
  // Let all other requests pass through normally
  event.respondWith(fetch(event.request));
});

// Install and activate the service worker
self.addEventListener('install', function(event) {
  console.log('Service Worker: Installing Sentry blocker...');
  self.skipWaiting();
});

self.addEventListener('activate', function(event) {
  console.log('Service Worker: Sentry blocker activated');
  event.waitUntil(self.clients.claim());
});
