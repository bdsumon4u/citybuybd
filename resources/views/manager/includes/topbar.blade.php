 <div class="br-header d-flex justify-content-between">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>

      </div><!-- br-header-left -->
      <div class="br-header-right">
        <nav class="nav">
          <div class="nav-item" style="margin-right: 20px; display: flex; align-items: center; padding: 8px 0;">
            <label class="switch" style="margin: 0; cursor: pointer;" title="Toggle In-App Notifications">
              <input type="checkbox" id="inAppNotificationToggle">
              <span class="slider round"></span>
            </label>
            <span style="margin-left: 8px; font-size: 12px; color: #5969ff; white-space: nowrap;">Notifications</span>
          </div>
          <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <span class="logged-name hidden-md-down"></span>
              <img src="{{asset('backend/img/avatar.jpg')}}" class="wd-32 rounded-circle" alt="">
              <span class="square-10 bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-250">
              <div class="nav-user-info">
                         <h5 class="mb-0 text-white" style="background-color: #5969ff;
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

<style>
.br-header-right .nav-item {
  display: flex !important;
  align-items: center !important;
}

.switch {
  position: relative;
  display: inline-block;
  width: 45px;
  height: 22px;
  flex-shrink: 0;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
}

input:checked + .slider {
  background-color: #5969ff;
}

input:checked + .slider:before {
  transform: translateX(23px);
}

.slider.round {
  border-radius: 22px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
    </div><!-- br-header -->
