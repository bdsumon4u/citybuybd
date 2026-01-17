<div class="br-logo"><a href=""><span>
                            @if($settings)
                            <img
                                src="{{ asset('backend/img/'.$settings->logo)  }}" class="img-fluid" width="70">
                            @endif
                            </span></a></div>
    <div class="br-sideleft sideleft-scrollbar">

      <ul class="br-sideleft-menu">
        <!-- <li class="br-menu-item">
          <a href="{{ route('admin.dashboard')}}" class="br-menu-link active">
            <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
            <span class="menu-item-label">Dashboard</span>
          </a>
        </li> -->

              <!-- brand menu start -->

         <!-- brand menu end -->

                  <!-- category menu start -->
       <li class="br-menu-item">
          <a href="{{ route('manager.dashboard')}}"  class="br-menu-link {{ Request::is('manager/dashboard*') ? 'active' : '' }}">
            <!-- <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i> -->
            <i class="fas fa-fw fa-desktop"></i>
            <span class="menu-item-label">Dashboard</span>
          </a>
        </li>


         <li class="br-menu-item">
          <a href="{{ route('manager.order.newmanage')}}" class="br-menu-link {{ Request::is('manager/order-management/*') ? 'active' : '' }}">
            <!-- <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i> -->
            <i class="fas fa-fw fa-cart-plus"></i>
            <span class="menu-item-label">Orders</span>
          </a>
        </li>

        <li class="br-menu-item">
            <a href="{{ route('manager.order.parcelHandover')}}" class="br-menu-link {{ Request::is('manager/order-management/parcel-handover*') ? 'active' : '' }}">
                <i class="fas fa-fw fa-box-open"></i>
                <span class="menu-item-label">Parcel Handover</span>
            </a>
        </li>

        <li class="br-menu-item">
            <a href="{{ route('manager.order.returnReceived')}}" class="br-menu-link {{ Request::is('manager/order-management/return-received*') ? 'active' : '' }}">
                <i class="fas fa-fw fa-undo"></i>
                <span class="menu-item-label">Return Received</span>
            </a>
        </li>

          <li class="br-menu-item">
          <a href="{{ route('manager.product.manage')}}" class="br-menu-link {{ Request::is('manager/product/*') ? 'active' : '' }}">
            <!-- <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i> -->
            <i class="fas fa-fw fa-box"></i>
            <span class="menu-item-label">Products</span>
          </a>
        </li>

        <!--    <li class="br-menu-item">-->
        <!--  <a href="{{ route('manager.product.stock')}}" class="br-menu-link {{ Request::is('manager/stock/*') ? 'active' : '' }}">-->
            <!-- <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i> -->
        <!--      <i class="fa-duotone fa-boxes-stacked"></i>-->
        <!--    <span class="menu-item-label">Stock</span>-->
        <!--  </a>-->
        <!--</li>-->




         <li class="br-menu-item">
          <a href="#" class="br-menu-link with-sub {{ Request::is('manager/courier/*') || Request::is('manager/city/*')||Request::is('manager/zone/*') ? 'active' : '' }}">
            <i class="fas fa fa-truck"></i>
            <span class="menu-item-label">Couriers</span>
          </a><!-- br-menu-link -->
          <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{route('manager.courier.manage')}}" class="sub-link {{ Request::is('manager/courier/*') ? 'active' : '' }}">Courier</a></li>
            <li class="sub-item"><a href="{{route('manager.city.manage')}}"  class="sub-link {{ Request::is('manager/city/*') ? 'active' : '' }}">City</a></li>
            <li class="sub-item"><a href="{{route('manager.zone.manage')}}" class="sub-link {{route('manager.courier.manage')}}" class="sub-link {{ Request::is('manager/zone/*') ? 'active' : '' }}">Zone</a></li>

          </ul>
        </li>



         <li class="br-menu-item">
          <a href="{{ route('manager.user.manage')}}" class="br-menu-link {{ Request::is('manager/user/*') ? 'active' : '' }} ">
            <i class="fas fa fa-user"></i>
            <span class="menu-item-label">User</span>
          </a>
        </li>

            <li class="br-menu-item">
                <a href="{{ route('cache.clear') }}" class="br-menu-link">
                    <i class="fas fa-broom"></i>
                    <span class="menu-item-label">Clear Cache</span>
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



























