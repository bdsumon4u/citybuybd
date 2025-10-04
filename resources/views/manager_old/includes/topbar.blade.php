 <div class="br-header">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
        
      </div><!-- br-header-left -->
      <div class="br-header-right">
        <nav class="nav">
          
         
          <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <span class="logged-name hidden-md-down"></span>
              <img src="{{asset('backend/img/avatar.jpg')}}" class="wd-32 rounded-circle" alt="">
              <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-250">
              <div class="nav-user-info">
                         <h5 class="mb-0 text-white " style="background-color: #5969ff;
                        line-height: 1.4;
                        padding: 12px;
                        color: #fff;
                        font-size: 13px;
                        border-radius: 2px 2px 0 0;">
                                        {{Auth::user()->name}}
                                </h5>
                        </div>
         
             
              <ul class="list-unstyled user-profile-nav">
                
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <li><a href="{{route('manager.reset')}}">Change Password</a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit(); ">
                  <i class="icon ion-power"></i> Sign Out</a></li>
              </form>
              </ul>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </nav>
       
      </div><!-- br-header-right -->
    </div><!-- br-header -->