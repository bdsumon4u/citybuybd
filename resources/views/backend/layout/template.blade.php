
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('backend.includes.header')
   @include('backend.includes.css')
   @vite(['resources/js/app.js'])
    <style type="text/css">
      html, body, .br-mainpanel, .br-pagebody, .table-responsive {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }
      ::-webkit-scrollbar:horizontal {
        display: none !important;
        height: 0 !important;
        background: transparent !important;
      }
      html::-webkit-scrollbar:horizontal,
      body::-webkit-scrollbar:horizontal,
      .br-mainpanel::-webkit-scrollbar:horizontal,
      .br-pagebody::-webkit-scrollbar:horizontal,
      .table-responsive::-webkit-scrollbar:horizontal {
        display: none !important;
        height: 0 !important;
        background: transparent !important;
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
