import _ from "lodash";
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

console.log('🚀 bootstrap.js is loading...');
window.Pusher = Pusher;

// Extract public key from full Ably key (part before the colon)
function getAblyPublicKey() {
    // Remove quotes if present
    const publicKey = (import.meta.env.VITE_ABLY_PUBLIC_KEY || '').toString().replace(/^["']|["']$/g, '');
    if (publicKey && publicKey !== 'undefined' && !publicKey.includes('${')) {
        console.log('Using VITE_ABLY_PUBLIC_KEY:', publicKey.substring(0, 10) + '...');
        return publicKey;
    }

    // If full key is provided, extract the public part (before the colon)
    const fullKey = (import.meta.env.VITE_ABLY_KEY || '').toString().replace(/^["']|["']$/g, '');
    if (fullKey && fullKey !== 'undefined' && !fullKey.includes('${') && fullKey.includes(':')) {
        const extracted = fullKey.split(':')[0];
        console.log('Extracted public key from VITE_ABLY_KEY:', extracted.substring(0, 10) + '...');
        return extracted;
    }

    console.warn('No valid Ably key found. VITE_ABLY_PUBLIC_KEY:', import.meta.env.VITE_ABLY_PUBLIC_KEY, 'VITE_ABLY_KEY:', import.meta.env.VITE_ABLY_KEY);
    return null;
}

const ablyPublicKey = getAblyPublicKey();

// Only initialize Echo if we have a key
if (ablyPublicKey) {
    try {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: ablyPublicKey,
            cluster: 'us1', // Default Ably cluster, can be overridden via env
            wsHost: 'realtime-pusher.ably.io',
            wsPort: 443,
            disableStats: true,
            encrypted: true,
            forceTLS: true,
        });
        console.log('✅ Laravel Echo initialized successfully with key:', ablyPublicKey.substring(0, 10) + '...');
    } catch (error) {
        console.error('❌ Failed to initialize Laravel Echo:', error);
        window.Echo = undefined;
    }
} else {
    console.warn('⚠️ VITE_ABLY_PUBLIC_KEY or VITE_ABLY_KEY is not set or invalid. Echo will not be initialized.');
    console.log('Available env vars:', {
        VITE_ABLY_PUBLIC_KEY: import.meta.env.VITE_ABLY_PUBLIC_KEY,
        VITE_ABLY_KEY: import.meta.env.VITE_ABLY_KEY
    });
    window.Echo = undefined;
}
