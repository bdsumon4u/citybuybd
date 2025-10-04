<div class="br-logo"><a href=""><span>@foreach(App\Models\Settings::all() as $settings)
                            <img
                                src="{{ asset('backend/img/'.$settings->logo)  }}" class="img-fluid" width="70">
                            @endforeach</span></a></div>
    <div class="br-sideleft sideleft-scrollbar ">

      <ul class="br-sideleft-menu">





                  <!-- category menu start -->
       <li class="br-menu-item">
          <a href="{{ route('employee.dashboard')}}"  class="br-menu-link {{ Request::is('employee/dashboard*') ? 'active' : '' }}">
            <!-- <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i> -->
            <i class="fas fa-fw fa-desktop"></i>
            <span class="menu-item-label">Dashboard</span>
          </a>
        </li>


         <li class="br-menu-item">
          <a href="{{ route('employee.order.manage')}}" class="br-menu-link {{ Request::is('employee/order-management/*') ? 'active' : '' }}">
            <i class="fas fa-fw fa-cart-plus"></i>
            <span class="menu-item-label">Orders</span>
          </a>
        </li>

          <li class="br-menu-item">
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <a href="{{ route('logout') }}" class="br-menu-link" onclick="event.preventDefault();this.closest('form').submit(); ">
                      <i class="icon ion-power"></i>
                      <span class="menu-item-label">Sign Out</span>
                  </a>
              </form>
          </li>


      </ul>
    </div>





















      <br>
    </div><!-- br-sideleft -->



























