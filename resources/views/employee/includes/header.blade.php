 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="">
    <meta name="twitter:description" content="Ecommerce Business">

   <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Facebook -->
    <meta property="og:title" content="">
    <meta property="og:description" content="Ecommerce Business">
@if($settings)
     <link rel="icon" type="image/x-icon" href="{{ asset('backend/img/'. $settings->favicon)  }}">
    @endif
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Ecommerce Business">
    <meta name="author" content="Dorkeriponno">

    <title>{{ $settings->insta_link ?? '' }}</title>
