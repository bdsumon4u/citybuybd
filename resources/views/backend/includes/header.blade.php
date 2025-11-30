 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="">
    <meta name="twitter:description" content=".">
    <meta name="twitter:image" content="">
    
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/bracketplus">
    <meta property="og:title" content="">
    <meta property="og:description" content=".">
@if($settings)
     <link rel="icon" type="image/x-icon" href="{{ asset('backend/img/'. $settings->favicon)  }}">
    @endif
    <meta property="og:image:secure_url" content="">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content=".">
    <meta name="author" content="">

    <title>{{ $settings->insta_link ?? '' }}</title>
