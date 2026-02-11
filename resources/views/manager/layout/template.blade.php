
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('manager.includes.header')
   @include('manager.includes.css')
   @vite(['resources/js/app.js'])

  </head>

  <body>


     @include('manager.includes.leftmenu')
     @include('manager.includes.topbar')


    <div class="br-mainpanel">


      @yield('body-content')
      @include('manager.includes.footer')
    </div><!-- br-mainpanel -->
    @include('manager.includes.script')


  </body>
</html>
