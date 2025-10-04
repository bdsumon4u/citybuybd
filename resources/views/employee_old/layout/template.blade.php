
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('employee.includes.header')
   @include('employee.includes.css')

  </head>

  <body>


     @include('employee.includes.leftmenu')

 @include('employee.includes.topbar')


    <div class="br-mainpanel">


      @yield('body-content')
      @include('employee.includes.footer')
    </div><!-- br-mainpanel -->
    @include('employee.includes.script')


  </body>
</html>
