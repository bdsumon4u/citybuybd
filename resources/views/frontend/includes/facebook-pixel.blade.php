@php
    $facebookPixelId = trim((string) ($settings->fb_pixel_id ?? ''));
    $facebookPixelCode = trim((string) ($settings->fb_pixel ?? ''));
    $serverFbp = \App\Support\FacebookTracking::resolveFbp(request());
    $serverFbc = \App\Support\FacebookTracking::resolveFbc(request());
@endphp

@if ($facebookPixelId !== '')
    <script>
        // Ensure first-party _fbp and _fbc cookies are active in document.cookie for Meta Pixel
        (function() {
            function getCookie(name) {
                var match = document.cookie.match(new RegExp('(^|;\\s*)' + name + '=([^;]*)'));
                return match ? decodeURIComponent(match[2]) : null;
            }
            function setCookie(name, value, days) {
                var expires = "";
                if (days) {
                    var d = new Date();
                    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + d.toUTCString();
                }
                document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/; SameSite=Lax";
            }

            // Capture and persist fbclid into _fbc
            try {
                var urlParams = new URLSearchParams(window.location.search);
                var fbclid = urlParams.get('fbclid');
                if (fbclid) {
                    var fbcVal = 'fb.1.' + Date.now() + '.' + fbclid;
                    setCookie('_fbc', fbcVal, 90);
                } else if (!getCookie('_fbc') && @json($serverFbc)) {
                    setCookie('_fbc', @json($serverFbc), 90);
                }

                // Ensure _fbp exists
                if (!getCookie('_fbp')) {
                    var fbpVal = @json($serverFbp) || ('fb.1.' + Date.now() + '.' + Math.floor(1000000000 + Math.random() * 9000000000));
                    setCookie('_fbp', fbpVal, 90);
                }
            } catch (e) {}
        })();

        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');

        @php
            $advancedMatching = [];
            if (auth()->check()) {
                if (!empty(auth()->user()->email)) {
                    $advancedMatching['em'] = strtolower(trim(auth()->user()->email));
                }
                if (!empty(auth()->user()->phone)) {
                    $ph = preg_replace('/\D+/', '', auth()->user()->phone);
                    if (str_starts_with($ph, '01') && strlen($ph) === 11) {
                        $ph = '88' . $ph;
                    }
                    $advancedMatching['ph'] = $ph;
                }
                $advancedMatching['external_id'] = (string) auth()->id();
            }
        @endphp

        @if(!empty($advancedMatching))
            fbq('init', @json($facebookPixelId), @json($advancedMatching));
        @else
            fbq('init', @json($facebookPixelId));
        @endif

        fbq('track', 'PageView');

        window.citybuybdFacebookTrack = function(eventName, params, eventId) {
            if (typeof window.fbq === 'function') {
                if (eventId) {
                    window.fbq('track', eventName, params || {}, { eventID: eventId });
                } else {
                    window.fbq('track', eventName, params || {});
                }
            }
        };
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $facebookPixelId }}&ev=PageView&noscript=1{{ $serverFbp ? '&ud[fbp]=' . urlencode($serverFbp) : '' }}{{ $serverFbc ? '&ud[fbc]=' . urlencode($serverFbc) : '' }}" />
    </noscript>
@elseif ($facebookPixelCode !== '')
    {!! $facebookPixelCode !!}
    <script>
        window.citybuybdFacebookTrack = function(eventName, params, eventId) {
            if (typeof window.fbq === 'function') {
                if (eventId) {
                    window.fbq('track', eventName, params || {}, { eventID: eventId });
                } else {
                    window.fbq('track', eventName, params || {});
                }
            }
        };
    </script>
@endif
