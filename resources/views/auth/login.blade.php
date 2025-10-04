<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">





    <title>Login</title>

    <!-- vendor css -->
    <link href="{{ asset('backend/lib/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="{{ asset('backend/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="{{ asset('backend/css/bracket.css')}}">
    <link rel="stylesheet" href="{{ asset('backend/css/custom.css')}}">
  </head>

  <body>


  <div class="row no-gutters flex-row-reverse ht-100v">
      <div class="col-md-6 bg-gray-200 d-flex align-items-center justify-content-center">
          <div class="login-wrapper wd-250 wd-xl-350 mg-y-30">
              <h4 class="tx-inverse tx-center">Sign In</h4>
              <form action="{{ route('login') }}" method="POST">
                  @csrf
                  <div class="form-group">
                      <input type="text" id="email" name="email" class="form-control" value="{{ old('email')}}" placeholder="Enter your Email" required autofocus>
                  </div><!-- form-group -->
                  <div class="form-group">
                      <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
                  </div><!-- form-group -->
                  <button type="submit" class="btn btn-info btn-block">Sign In</button>
              </form>
              <x-auth-session-status class="mb-4" :status="session('status')" />
              <x-auth-validation-errors class="mb-4" :errors="$errors" />

          </div><!-- login-wrapper -->
      </div><!-- col -->
      <div class="col-md-6 bg-br-primary d-flex align-items-center justify-content-center">
          <div class="wd-250 wd-xl-450 mg-y-30">
              <div class="signin-logo tx-28 tx-bold tx-white"><span class="tx-normal"></span> Welcome To<span class="tx-info"> SM IT </span> <span class="tx-normal"></span></div>
              <div class="tx-white mg-b-60">Ecommerce Platform</div>


              <a href="{{route('homepage')}}" target="_blank" class="btn btn-outline-light bd bd-white bd-2 tx-white pd-x-25 tx-uppercase tx-12 tx-spacing-2 tx-medium">Go To Store</a>
          </div><!-- wd-500 -->
      </div>
  </div>



    <script src="{{ asset('backend/lib/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('backend/lib/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <script src="{{ asset('backend/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

  </body>
</html>

