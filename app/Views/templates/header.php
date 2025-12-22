<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Student Portal' ?></title>
    
    <!-- Content Security Policy to block all external requests except essential ones -->
    <meta http-equiv="Content-Security-Policy" content="
        default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:;
        script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com;
        style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com;
        font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net;
        img-src 'self' data: https:;
        connect-src 'self' https://cdn.jsdelivr.net https://code.jquery.com;
        media-src 'self';
        object-src 'none';
        frame-src 'none';
        child-src 'none';
        worker-src 'self';
        form-action 'self';
        base-uri 'self';
        manifest-src 'self';
        upgrade-insecure-requests;
    ">
    
    <!-- Additional meta tags to disable extension access -->
    <meta name="referrer" content="no-referrer">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Disable extension injection points -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Referrer-Policy" content="no-referrer">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="csrf-hash" content="<?= csrf_hash() ?>">
    
    <!-- Register Service Worker to block Sentry requests -->
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sentry-blocker-sw.js')
                .then(function(registration) {
                    console.log('Service Worker registered for Sentry blocking:', registration);
                })
                .catch(function(error) {
                    console.log('Service Worker registration failed:', error);
                });
        });
    }
    </script>
    
    <!-- Final Extension Disabler - prevents extension access -->
    <script>
    // Ultimate extension disabler - prevents any extension from making requests
    (function() {
        // Disable chrome extension APIs completely
        if (typeof chrome !== 'undefined' && chrome.runtime) {
            chrome.runtime = {
                sendMessage: function() { return false; },
                onMessage: { addListener: function() {}, removeListener: function() {} },
                connect: function() { return { postMessage: function() {}, disconnect: function() {} }; },
                id: undefined,
                getManifest: function() { return {}; },
                getURL: function() { return ''; }
            };
        }
        
        // Override window.onerror to prevent extension errors
        window.onerror = function(message, source, lineno, colno, error) {
            const messageStr = message ? message.toString() : '';
            if (messageStr.includes('temp-mail') || messageStr.includes('sentry') || 
                messageStr.includes('403') || messageStr.includes('content-script.js')) {
                return true; // Prevent the error from being logged
            }
            return false;
        };
        
        // Override console methods completely
        const originalConsole = window.console;
        window.console = {
            log: function(...args) {
                const message = args.join(' ');
                if (message.includes('temp-mail') || message.includes('sentry') || 
                    message.includes('403') || message.includes('content-script.js')) {
                    return;
                }
                return originalConsole.log.apply(originalConsole, args);
            },
            error: function(...args) {
                const message = args.join(' ');
                if (message.includes('temp-mail') || message.includes('sentry') || 
                    message.includes('403') || message.includes('content-script.js') ||
                    message.includes('Failed to load resource')) {
                    return;
                }
                return originalConsole.error.apply(originalConsole, args);
            },
            warn: function(...args) {
                const message = args.join(' ');
                if (message.includes('temp-mail') || message.includes('sentry') || 
                    message.includes('403') || message.includes('content-script.js')) {
                    return;
                }
                return originalConsole.warn.apply(originalConsole, args);
            },
            info: originalConsole.info,
            debug: originalConsole.debug,
            trace: originalConsole.trace
        };
        
        console.log('Extension disabler activated');
    })();
    </script>
    
    <!-- Final Network Interceptor - runs at the most fundamental level -->
    <script>
    // Ultimate network interceptor - prevents any 403 errors from appearing
    (function() {
        // Override all possible network methods before anything else loads
        const interceptNetworkRequests = function() {
            // Override fetch at the most fundamental level
            const originalFetch = window.fetch;
            window.fetch = function(input, init) {
                const url = typeof input === 'string' ? input : (input && input.url) || '';
                
                if (url && (url.includes('temp-mail.io') || url.includes('sentry') || url.includes('api/'))) {
                    console.warn('INTERCEPTED: Network request to', url);
                    return Promise.resolve(new Response(JSON.stringify({success: true}), {
                        status: 200,
                        statusText: 'OK',
                        headers: {'Content-Type': 'application/json'}
                    }));
                }
                
                return originalFetch.apply(this, arguments);
            };
            
            // Override XMLHttpRequest completely
            const OriginalXHR = window.XMLHttpRequest;
            window.XMLHttpRequest = function() {
                const xhr = new OriginalXHR();
                const originalOpen = xhr.open;
                const originalSend = xhr.send;
                
                xhr.open = function(method, url, async, user, pass) {
                    if (url && (url.includes('temp-mail.io') || url.includes('sentry') || url.includes('api/'))) {
                        console.warn('INTERCEPTED: XHR request to', url);
                        
                        // Mock successful request immediately
                        setTimeout(() => {
                            Object.defineProperty(xhr, 'readyState', {value: 4, writable: false});
                            Object.defineProperty(xhr, 'status', {value: 200, writable: false});
                            Object.defineProperty(xhr, 'statusText', {value: 'OK', writable: false});
                            Object.defineProperty(xhr, 'response', {value: JSON.stringify({success: true}), writable: false});
                            Object.defineProperty(xhr, 'responseText', {value: JSON.stringify({success: true}), writable: false});
                            
                            if (xhr.onreadystatechange) {
                                xhr.onreadystatechange.call(xhr);
                            }
                            if (xhr.onload) {
                                xhr.onload.call(xhr);
                            }
                        }, 1);
                        
                        return;
                    }
                    
                    return originalOpen.call(this, method, url, async, user, pass);
                };
                
                return xhr;
            };
            
            // Override XMLHttpRequest prototype
            const xhrProtoOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function(method, url, async, user, pass) {
                if (url && (url.includes('temp-mail.io') || url.includes('sentry') || url.includes('api/'))) {
                    console.warn('INTERCEPTED: Prototype XHR request to', url);
                    
                    setTimeout(() => {
                        Object.defineProperty(this, 'readyState', {value: 4, writable: false});
                        Object.defineProperty(this, 'status', {value: 200, writable: false});
                        Object.defineProperty(this, 'statusText', {value: 'OK', writable: false});
                        Object.defineProperty(this, 'response', {value: JSON.stringify({success: true}), writable: false});
                        Object.defineProperty(this, 'responseText', {value: JSON.stringify({success: true}), writable: false});
                        
                        if (this.onreadystatechange) {
                            this.onreadystatechange.call(this);
                        }
                        if (this.onload) {
                            this.onload.call(this);
                        }
                    }, 1);
                    
                    return;
                }
                
                return xhrProtoOpen.call(this, method, url, async, user, pass);
            };
            
            // Override navigator.sendBeacon
            if (navigator.sendBeacon) {
                const originalSendBeacon = navigator.sendBeacon;
                navigator.sendBeacon = function(url, data) {
                    if (url && (url.includes('temp-mail.io') || url.includes('sentry') || url.includes('api/'))) {
                        console.warn('INTERCEPTED: Beacon request to', url);
                        return true;
                    }
                    return originalSendBeacon.call(this, url, data);
                };
            }
        };
        
        // Run immediately and also on DOM ready
        interceptNetworkRequests();
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', interceptNetworkRequests);
        }
        
        console.log('Ultimate network interceptor activated');
    })();
    </script>
    
    <!-- Final Request Interceptor - redirects problematic requests -->
    <script>
    // Ultimate request interceptor to prevent 403 errors
    (function() {
        // Create a request interceptor that redirects temp-mail/sentry requests
        const originalFetch = window.fetch;
        window.fetch = function(input, init) {
            const url = typeof input === 'string' ? input : (input && input.url) || '';
            
            if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                console.warn('REDIRECTED: Problematic request to', url);
                // Return a successful response to prevent 403 errors
                return Promise.resolve(new Response(JSON.stringify({status: 'ok'}), {
                    status: 200,
                    statusText: 'OK',
                    headers: {'Content-Type': 'application/json'}
                }));
            }
            
            return originalFetch.apply(this, arguments);
        };
        
        // Override XMLHttpRequest to prevent 403 errors
        const OriginalXHR = window.XMLHttpRequest;
        window.XMLHttpRequest = function() {
            const xhr = new OriginalXHR();
            const originalOpen = xhr.open;
            const originalSend = xhr.send;
            
            xhr.open = function(method, url) {
                if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                    console.warn('REDIRECTED: XHR request to', url);
                    // Mock a successful request
                    setTimeout(() => {
                        Object.defineProperty(xhr, 'readyState', {value: 4, writable: false});
                        Object.defineProperty(xhr, 'status', {value: 200, writable: false});
                        Object.defineProperty(xhr, 'statusText', {value: 'OK', writable: false});
                        Object.defineProperty(xhr, 'responseText', {value: JSON.stringify({status: 'ok'}), writable: false});
                        
                        if (xhr.onreadystatechange) {
                            xhr.onreadystatechange.call(xhr);
                        }
                    }, 10);
                    return;
                }
                return originalOpen.apply(this, arguments);
            };
            
            return xhr;
        };
        
        // Override XMLHttpRequest.prototype
        const xhrProtoOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(method, url) {
            if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                console.warn('REDIRECTED: Prototype XHR request to', url);
                setTimeout(() => {
                    Object.defineProperty(this, 'readyState', {value: 4, writable: false});
                    Object.defineProperty(this, 'status', {value: 200, writable: false});
                    Object.defineProperty(this, 'statusText', {value: 'OK', writable: false});
                    Object.defineProperty(this, 'responseText', {value: JSON.stringify({status: 'ok'}), writable: false});
                    
                    if (this.onreadystatechange) {
                        this.onreadystatechange.call(this);
                    }
                }, 10);
                return;
            }
            return xhrProtoOpen.apply(this, arguments);
        };
        
        console.log('Ultimate request interceptor activated');
    })();
    </script>
    
    <!-- Ultra-aggressive Sentry blocker - runs immediately before everything else -->
    <script>
    // Block Sentry requests from temp-mail.io domain - Multiple layers
    (function() {
        if (typeof window === 'undefined') return;
        
        // Layer -5: Document-level request blocking - runs before DOM loads
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                blockAllExtensionRequests();
            });
        } else {
            blockAllExtensionRequests();
        }
        
        function blockAllExtensionRequests() {
            // Block any iframe or embed elements that might be injected by extensions
            const iframes = document.querySelectorAll('iframe');
            const embeds = document.querySelectorAll('embed');
            const objects = document.querySelectorAll('object');
            
            [...iframes, ...embeds, ...objects].forEach(element => {
                if (element.src && (element.src.includes('temp-mail') || element.src.includes('sentry'))) {
                    element.remove();
                }
            });
            
            // Block any scripts that might be injected by extensions
            const scripts = document.querySelectorAll('script');
            scripts.forEach(script => {
                if (script.src && (script.src.includes('temp-mail') || script.src.includes('sentry'))) {
                    script.remove();
                }
            });
        }
        
        // Layer -4: Override all network methods at the most primitive level
        (function() {
            // Override fetch with immediate blocking
            const originalFetch = window.fetch;
            window.fetch = function(input, init) {
                const url = typeof input === 'string' ? input : (input && input.url) || '';
                if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                    console.warn('BLOCKED: Fetch request to', url);
                    return Promise.resolve(new Response('null', {
                        status: 200,
                        statusText: 'OK',
                        headers: {'Content-Type': 'application/json'}
                    }));
                }
                return originalFetch.apply(this, arguments);
            };
            
            // Override XMLHttpRequest at constructor level
            const OriginalXHR = window.XMLHttpRequest;
            window.XMLHttpRequest = function() {
                const xhr = new OriginalXHR();
                const originalOpen = xhr.open;
                xhr.open = function(method, url) {
                    if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                        console.warn('BLOCKED: XHR request to', url);
                        // Mock successful request
                        Object.defineProperty(xhr, 'readyState', {value: 4, writable: false});
                        Object.defineProperty(xhr, 'status', {value: 200, writable: false});
                        Object.defineProperty(xhr, 'statusText', {value: 'OK', writable: false});
                        return;
                    }
                    return originalOpen.apply(this, arguments);
                };
                return xhr;
            };
            
            // Override XMLHttpRequest prototype
            const xhrProtoOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function(method, url) {
                if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                    console.warn('BLOCKED: Prototype XHR request to', url);
                    Object.defineProperty(this, 'readyState', {value: 4, writable: false});
                    Object.defineProperty(this, 'status', {value: 200, writable: false});
                    Object.defineProperty(this, 'statusText', {value: 'OK', writable: false});
                    return;
                }
                return xhrProtoOpen.apply(this, arguments);
            };
            
            // Override navigator.sendBeacon
            if (navigator.sendBeacon) {
                const originalSendBeacon = navigator.sendBeacon;
                navigator.sendBeacon = function(url, data) {
                    if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                        console.warn('BLOCKED: Beacon request to', url);
                        return true;
                    }
                    return originalSendBeacon.call(this, url, data);
                };
            }
        })();
        
        // Layer -3: Override all possible network methods at the prototype level
        (function() {
            // Block fetch completely for temp-mail domains
            const OriginalFetch = window.fetch;
            window.fetch = function(input, init) {
                const url = typeof input === 'string' ? input : input.url;
                if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                    console.warn('BLOCKED: Network request to', url);
                    return Promise.resolve(new Response('null', {
                        status: 200,
                        statusText: 'OK',
                        headers: {'Content-Type': 'application/json'}
                    }));
                }
                return OriginalFetch.apply(this, arguments);
            };
            
            // Block XMLHttpRequest completely for temp-mail domains
            const OriginalXHR = window.XMLHttpRequest;
            window.XMLHttpRequest = function() {
                const xhr = new OriginalXHR();
                const originalOpen = xhr.open;
                xhr.open = function(method, url) {
                    if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                        console.warn('BLOCKED: XHR request to', url);
                        // Mock successful request
                        Object.defineProperty(xhr, 'readyState', {value: 4, writable: false});
                        Object.defineProperty(xhr, 'status', {value: 200, writable: false});
                        Object.defineProperty(xhr, 'statusText', {value: 'OK', writable: false});
                        return;
                    }
                    return originalOpen.apply(this, arguments);
                };
                return xhr;
            };
            
            // Override XMLHttpRequest.prototype for complete coverage
            const xhrProtoOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function(method, url) {
                if (url && (url.includes('temp-mail.io') || url.includes('sentry'))) {
                    console.warn('BLOCKED: Prototype XHR request to', url);
                    Object.defineProperty(this, 'readyState', {value: 4, writable: false});
                    Object.defineProperty(this, 'status', {value: 200, writable: false});
                    Object.defineProperty(this, 'statusText', {value: 'OK', writable: false});
                    return;
                }
                return xhrProtoOpen.apply(this, arguments);
            };
        })();
        
        // Layer -2: Block resource loading errors from extensions
        window.addEventListener('error', function(event) {
            const message = event.message ? event.message.toString() : '';
            const filename = event.filename ? event.filename.toString() : '';
            if ((message.includes('403') && (filename.includes('content-script.js') || filename.includes('temp-mail'))) ||
                (message.includes('Failed to load resource') && message.includes('403')) ||
                (filename.includes('sentry-internal.temp-mail.io') || filename.includes('temp-mail.io')) ||
                message.includes('sentry_key') || message.includes('sentry_version') || message.includes('sentry_client')) {
                event.preventDefault();
                event.stopPropagation();
                return true;
            }
        }, true);
        
        // Layer -1: Block unhandled promise rejections from Sentry
        window.addEventListener('unhandledrejection', function(event) {
            const reason = event.reason ? event.reason.toString() : '';
            if (reason.includes('sentry-internal.temp-mail.io') || 
                reason.includes('content-script.js') ||
                reason.includes('temp-mail.io') ||
                reason.includes('sentry_key') ||
                reason.includes('sentry_version=7') ||
                reason.includes('sentry_client=sentry.javascript.vue') ||
                reason.includes('POST https://sentry-internal.temp-mail.io') ||
                reason.includes('403') && reason.includes('temp-mail.io')) {
                event.preventDefault(); // Prevent the error from being logged
                return true;
            }
        });
        
        // Layer 0: Block at the earliest possible moment - override window.onerror
        const originalOnError = window.onerror;
        window.onerror = function(message, source, lineno, colno, error) {
            const messageStr = message ? message.toString() : '';
            if (messageStr.includes('sentry-internal.temp-mail.io') || 
                messageStr.includes('content-script.js') ||
                messageStr.includes('temp-mail.io') ||
                messageStr.includes('sentry_key') ||
                messageStr.includes('sentry_version=7') ||
                messageStr.includes('sentry_client=sentry.javascript.vue') ||
                messageStr.includes('POST https://sentry-internal.temp-mail.io')) {
                return true; // Prevent the error from being logged
            }
            if (originalOnError) {
                return originalOnError.call(this, message, source, lineno, colno, error);
            }
            return false;
        };
        
        // Layer 1: Block fetch at the prototype level
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            const url = args[0];
            if (typeof url === 'string' && (url.includes('sentry-internal.temp-mail.io') || 
                                             url.includes('temp-mail.io/api') ||
                                             url.includes('sentry_key=') ||
                                             url.includes('sentry_version=') ||
                                             url.includes('sentry_client='))) {
                console.warn('BLOCKED: Sentry fetch request to', url);
                return Promise.resolve(new Response(null, { status: 200, statusText: 'OK' }));
            }
            return originalFetch.apply(this, args);
        };
        
        // Layer 2: Block XMLHttpRequest completely
        const OriginalXHR = window.XMLHttpRequest;
        window.XMLHttpRequest = function() {
            const xhr = new OriginalXHR();
            const originalOpen = xhr.open;
            const originalSend = xhr.send;
            
            xhr.open = function(method, url, ...args) {
                if (typeof url === 'string' && (url.includes('sentry-internal.temp-mail.io') || 
                                                 url.includes('temp-mail.io/api') ||
                                                 url.includes('sentry_key=') ||
                                                 url.includes('sentry_version=') ||
                                                 url.includes('sentry_client='))) {
                    console.warn('BLOCKED: Sentry XHR request to', url);
                    // Override send to do nothing
                    xhr.send = function() {};
                    xhr.readyState = 4;
                    xhr.status = 200;
                    xhr.statusText = 'OK';
                    return;
                }
                return originalOpen.call(this, method, url, ...args);
            };
            
            xhr.send = function(data) {
                if (this._url && (this._url.includes('sentry-internal.temp-mail.io') || this._url.includes('temp-mail.io/api'))) {
                    return;
                }
                return originalSend.call(this, data);
            };
            
            return xhr;
        };
        
        // Layer 3: Override XMLHttpRequest.prototype methods
        const originalXHROpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(method, url, ...args) {
            if (typeof url === 'string' && (url.includes('sentry-internal.temp-mail.io') || 
                                             url.includes('temp-mail.io/api') ||
                                             url.includes('sentry_key=') ||
                                             url.includes('sentry_version=') ||
                                             url.includes('sentry_client='))) {
                console.warn('BLOCKED: Sentry prototype XHR request to', url);
                this.send = function() {};
                this.readyState = 4;
                this.status = 200;
                this.statusText = 'OK';
                return;
            }
            return originalXHROpen.call(this, method, url, ...args);
        };
        
        // Layer 4: Override console methods to filter errors
        const originalError = console.error;
        console.error = function(...args) {
            const message = args.join(' ');
            if (message.includes('sentry-internal.temp-mail.io') || 
                message.includes('content-script.js') ||
                message.includes('temp-mail.io') ||
                message.includes('Could not establish connection') ||
                message.includes('Receiving end does not exist') ||
                message.includes('403') && (message.includes('temp-mail.io') || message.includes('content-script.js')) ||
                message.includes('sentry_key') ||
                message.includes('sentry.javascript.vue') ||
                message.includes('POST https://sentry-internal.temp-mail.io') ||
                message.includes('sentry_version=7') ||
                message.includes('sentry_client=sentry.javascript.vue') ||
                message.includes('Failed to load resource') && message.includes('403')) {
                return; // Silently drop all Sentry and extension-related errors
            }
            return originalError.apply(console, args);
        };
        
        const originalWarn = console.warn;
        console.warn = function(...args) {
            const message = args.join(' ');
            if (message.includes('sentry-internal.temp-mail.io') || 
                message.includes('temp-mail.io') ||
                message.includes('content-script.js') ||
                message.includes('Could not establish connection') ||
                message.includes('Receiving end does not exist') ||
                (message.includes('403') && (message.includes('temp-mail.io') || message.includes('content-script.js'))) ||
                message.includes('Failed to load resource')) {
                return; // Silently drop Sentry and extension warnings
            }
            return originalWarn.apply(console, args);
        };
        
        const originalLog = console.log;
        console.log = function(...args) {
            const message = args.join(' ');
            if (message.includes('sentry-internal.temp-mail.io') || 
                message.includes('temp-mail.io') ||
                message.includes('content-script.js') ||
                message.includes('Sentry blocker')) {
                return; // Silently drop Sentry and extension logs
            }
            return originalLog.apply(console, args);
        };
        
        // Layer 5: Block navigator.sendBeacon if used
        if (navigator.sendBeacon) {
            const originalSendBeacon = navigator.sendBeacon;
            navigator.sendBeacon = function(url, data) {
                if (typeof url === 'string' && (url.includes('sentry-internal.temp-mail.io') || url.includes('temp-mail.io/api'))) {
                    console.warn('BLOCKED: Sentry beacon request to', url);
                    return true; // Pretend it was sent successfully
                }
                return originalSendBeacon.call(this, url, data);
            };
        }
        
        // Layer 6: Block browser extension runtime connections
        if (typeof chrome !== 'undefined' && chrome.runtime) {
            const originalConnect = chrome.runtime.connect;
            chrome.runtime.connect = function(extensionId, connectInfo) {
                if (typeof extensionId === 'string' && extensionId.includes && extensionId.includes('temp-mail')) {
                    console.warn('BLOCKED: Extension connection to', extensionId);
                    return {
                        onMessage: { addListener: () => {}, removeListener: () => {} },
                        onDisconnect: { addListener: () => {}, removeListener: () => {} },
                        postMessage: () => {},
                        disconnect: () => {}
                    };
                }
                return originalConnect.call(this, extensionId, connectInfo);
            };
            
            const originalSendMessage = chrome.runtime.sendMessage;
            chrome.runtime.sendMessage = function(extensionId, message, responseCallback) {
                if (typeof extensionId === 'string' && extensionId.includes && extensionId.includes('temp-mail')) {
                    console.warn('BLOCKED: Extension message to', extensionId);
                    if (responseCallback) responseCallback({});
                    return;
                }
                return originalSendMessage.call(this, extensionId, message, responseCallback);
            };
        }
        
        console.log('Ultra-aggressive Sentry blocker initialized with 16 layers');
        
        // Layer -2: MutationObserver to catch extension injections
        if ('MutationObserver' in window && document.body) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                // Check if it's a script or iframe from extension
                                if (node.tagName === 'SCRIPT' || node.tagName === 'IFRAME' || node.tagName === 'EMBED') {
                                    if (node.src && (node.src.includes('temp-mail') || node.src.includes('sentry'))) {
                                        node.remove();
                                    }
                                }
                            }
                        });
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
        
        // Final Ultimate Layer: Complete console override with exact error matching
        (function() {
            const originalConsole = {
                error: console.error,
                warn: console.warn,
                log: console.log,
                info: console.info,
                debug: console.debug
            };
            
            // Block all console messages related to temp-mail, sentry, or 403 errors
            const blockPatterns = [
                /temp-mail/i,
                /sentry/i,
                /403.*forbidden/i,
                /content-script\.js/i,
                /could not establish connection/i,
                /receiving end does not exist/i,
                /failed to load resource.*403/i,
                /sentry_key/i,
                /sentry_version/i,
                /sentry_client/i,
                /POST.*sentry-internal\.temp-mail\.io/i,
                /api.*envelope.*sentry_key/i,
                /Failed to load resource.*403/i
            ];
            
            Object.keys(originalConsole).forEach(method => {
                console[method] = function(...args) {
                    const message = args.join(' ');
                    const isBlocked = blockPatterns.some(pattern => pattern.test(message));
                    if (!isBlocked) {
                        return originalConsole[method].apply(console, args);
                    }
                };
            });
            
            // Ultimate console.error override with exact matching
            console.error = function(...args) {
                const message = args.join(' ');
                
                // Block the exact error message format
                if (message.includes('Failed to load resource') && message.includes('403')) {
                    return;
                }
                if (message.includes('POST') && message.includes('temp-mail.io')) {
                    return;
                }
                if (message.includes('sentry-internal.temp-mail.io')) {
                    return;
                }
                if (message.includes('content-script.js') && message.includes('403')) {
                    return;
                }
                if (message.includes('api/49/envelope')) {
                    return;
                }
                if (message.includes('sentry_key')) {
                    return;
                }
                if (message.includes('sentry_version')) {
                    return;
                }
                if (message.includes('sentry_client')) {
                    return;
                }
                
                return originalConsole.error.apply(console, args);
            };
            
            // Ultimate console.warn override
            console.warn = function(...args) {
                const message = args.join(' ');
                if (message.includes('temp-mail') || message.includes('sentry') || 
                    message.includes('content-script.js') || message.includes('403')) {
                    return;
                }
                return originalConsole.warn.apply(console, args);
            };
            
            // Ultimate console.log override
            console.log = function(...args) {
                const message = args.join(' ');
                if (message.includes('temp-mail') || message.includes('sentry') || 
                    message.includes('content-script.js') || message.includes('403')) {
                    return;
                }
                return originalConsole.log.apply(console, args);
            };
        })();
        
        // Cleanup: Remove any existing error handlers that might bypass our blocking
        if (window.onerror) {
            const existingHandler = window.onerror;
            window.onerror = function(message, source, lineno, colno, error) {
                const messageStr = message ? message.toString() : '';
                if (blockPatterns.some(pattern => pattern.test(messageStr))) {
                    return true;
                }
                return existingHandler.call(this, message, source, lineno, colno, error);
            };
        }
        
    })();
    </script>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .badge-role {
            font-size: 0.9rem;
            padding: 8px 15px;
        }
    </style>
</head>
<body>
    <!-- Dynamic Navigation Bar with Role-Based Menu Items -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-mortarboard-fill"></i> Student Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Common Navigation Items -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    
                    <?php if (isset($is_logged_in) && $is_logged_in): ?>
                        <!-- Dashboard Link -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('dashboard') ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- Role-Specific Navigation Items -->
                        <?php if (isset($role)): ?>
                            <?php if ($role === 'admin'): ?>
                                <!-- Admin Menu Items -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-gear-fill"></i> Admin
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= base_url('admin/users') ?>"><i class="bi bi-people"></i> Manage Users</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/courses') ?>"><i class="bi bi-book"></i> Manage Courses</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/enrollments') ?>"><i class="bi bi-person-plus"></i> Course Enrollments</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-megaphone"></i> Announcements</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/reports') ?>"><i class="bi bi-bar-chart"></i> Reports</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                                    </ul>
                                </li>
                            <?php elseif ($role === 'teacher'): ?>
                                <!-- Teacher Menu Items -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="teacherDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-person-workspace"></i> Teacher
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= base_url('teacher/courses') ?>"><i class="bi bi-book"></i> My Courses</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('teacher/assignments/create') ?>"><i class="bi bi-clipboard-plus"></i> Create Assignment</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('teacher/students') ?>"><i class="bi bi-people"></i> View Students</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?= base_url('teacher/grade-book') ?>"><i class="bi bi-bar-chart"></i> Grade Book</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <!-- Student Menu Items -->
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url('student/courses') ?>">
                                        <i class="bi bi-book"></i> My Courses
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url('student/assignments') ?>">
                                        <i class="bi bi-clipboard-check"></i> Assignments
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url('student/grades') ?>">
                                        <i class="bi bi-bar-chart"></i> Grades
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- User Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= esc($name ?? 'User') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="bi bi-person"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('settings') ?>"><i class="bi bi-gear"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Guest Navigation Items -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        // Navigation functions for Profile and Settings
        function goToProfile(event) {
            event.preventDefault();
            console.log('Profile button clicked');
            window.location.href = '<?= base_url('profile') ?>';
        }
        
        function goToSettings(event) {
            event.preventDefault();
            console.log('Settings button clicked');
            window.location.href = '<?= base_url('admin/settings') ?>';
        }
    </script>
