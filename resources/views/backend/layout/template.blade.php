
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('backend.includes.header')
   @include('backend.includes.css')
   @vite(['resources/js/app.js'])
   <style type="text/css">
     html, body {
       overflow-x: hidden;
       max-width: 100%;
     }
     .br-mainpanel {
       overflow-x: hidden;
       max-width: 100%;
     }
     td {

  vertical-align: middle !important;
}
   </style>

  </head>

  <body>


     @include('backend.includes.leftmenu')

    @include('backend.includes.topbar')


    <div class="br-mainpanel">


      @yield('body-content')
      @include('backend.includes.footer')
    </div><!-- br-mainpanel -->
    @include('backend.includes.script')


  </body>
</html>
