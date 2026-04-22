@php
    $facebookPixelId = trim((string) ($settings->fb_pixel_id ?? ''));
    $facebookPixelCode = trim((string) ($settings->fb_pixel ?? ''));
@endphp

@if ($facebookPixelId !== '')
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', @json($facebookPixelId));
        fbq('track', 'PageView');
        window.citybuybdFacebookTrack = function(eventName, params) {
            if (typeof window.fbq === 'function') {
                window.fbq('track', eventName, params || {});
            }
        };
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $facebookPixelId }}&ev=PageView&noscript=1" />
    </noscript>
@elseif ($facebookPixelCode !== '')
    {!! $facebookPixelCode !!}
    <script>
        window.citybuybdFacebookTrack = function(eventName, params) {
            if (typeof window.fbq === 'function') {
                window.fbq('track', eventName, params || {});
            }
        };
    </script>
@endif