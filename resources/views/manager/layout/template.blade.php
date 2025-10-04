
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('manager.includes.header')
   @include('manager.includes.css')

  </head>

  <body>


     @include('manager.includes.leftmenu')
 <div class="br-header">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
        
      </div><!-- br-header-left -->
     
    </div><!-- br-header -->


    <div class="br-mainpanel">


      @yield('body-content')
      @include('manager.includes.footer')
    </div><!-- br-mainpanel -->
    @include('manager.includes.script')


  </body>
</html>
