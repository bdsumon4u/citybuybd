 @php $settings = App\Models\Settings::all() @endphp
 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Twitter -->

    <meta name="twitter:title" content="Ecommerce">


   <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Facebook -->


@foreach($settings as $settings)
     <link rel="icon" type="image/x-icon" href="{{ asset('backend/img/'. $settings->favicon)  }}">
    @endforeach
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->


    <title>Ecommerce</title>
