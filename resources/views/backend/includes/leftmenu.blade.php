<style>
.colorful-sidebar {
    background: linear-gradient(180deg, #209B7E 0%, #76B729 100%);
    /* teal → lime */
    color: #fff;
}

.colorful-sidebar .br-menu-link {
    color: #fff;
    transition: background 0.3s ease;
}

.colorful-sidebar .br-menu-link:hover {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 6px;
}

.colorful-sidebar .br-menu-link.active {
    background: rgba(255, 255, 255, 0.25);
    border-radius: 6px;
}

.colorful-sidebar .menu-item-label {
    color: #fff;
}

</style>
<div class="br-logo"><a href=""><span>
                            @if($settings)
                            <img
                                src="{{ asset('backend/img/'.$settings->logo)  }}" class="img-fluid" width="70">
                            @endif
                            </span></a></div>
    <div class="br-sideleft sideleft-scrollbar colorful-sidebar">

      <ul class="br-sideleft-menu">

{{--              <div class="card pd-10 bg-br-primary">--}}
{{--                  <div class="tx-center">--}}
{{--                      <a href=""><img src="{{asset('backend/img/avatar.jpg')}}" class="wd-50 rounded-circle" alt=""></a>--}}
{{--                      <p class="my-1 tx-16"> Admin</p>--}}

{{--                  </div>--}}
{{--              </div><!-- card -->--}}


              <!-- brand menu start -->

         <!-- brand menu end -->

                  <!-- category menu start -->
       <li class="br-menu-item">
          <a href="{{ route('admin.dashboard')}}"  class="br-menu-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
            <!-- <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i> -->
            <i class="fas fa-fw fa-desktop"></i>
            <span class="menu-item-label">Dashboard</span>
          </a>
        </li>


         <li class="br-menu-item">
          <a href="{{ route('order.newmanage')}}" class="br-menu-link {{ Request::is('admin/order-management/new-manage*') ? 'active' : '' }}">
            <!-- <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i> -->
            <i class="fas fa-fw fa-cart-plus"></i>
            <span class="menu-item-label">Orders</span>
          </a>
        </li>

        <li class="br-menu-item">
            <a href="{{ route('order.incomplete.admin') }}"
              class="br-menu-link {{ Request::is('order-management/incomplete*') ? 'active' : '' }}">
                <i class="fas fa-fw fa-exclamation-circle"></i>
                <span class="menu-item-label">Incomplete Orders</span>
            </a>
        </li>

@if(Request::is('admin/*'))
    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ Request::is('admin/product/*') || Request::is('admin/category/*') || Request::is('admin/subcategory/*') || Request::is('admin/landing/*') || Request::is('admin/childcategory/*') || Request::is('admin/brand/*') ? 'active' : '' }}">
            <i class="fas fa-fw fa-box"></i>
            <span class="menu-item-label">Products</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{ route('product.manage')}}" class="sub-link {{  Request::is('admin/product/*') ? 'active' : '' }}">Products</a></li>
            <li class="sub-item"><a href="{{ route('category.manage')}}"  class="sub-link {{ Request::is('admin/category/*') ? 'active' : '' }}">Category</a></li>
            <li class="sub-item"><a href="{{ route('subcategory.manage')}}"  class="sub-link {{ Request::is('admin/subcategory/*') ? 'active' : '' }}">Sub Category</a></li>
            <li class="sub-item"><a href="{{ route('childcategory.manage')}}"  class="sub-link {{ Request::is('admin/childcategory/*') ? 'active' : '' }}">Child Category</a></li>
            <li class="sub-item"><a href="{{ route('brand.manage')}}"  class="sub-link {{ Request::is('admin/brand/*') ? 'active' : '' }}">Brand</a></li>
            <!--<li class="sub-item"><a href="{{ route('product.stock')}}"  class="sub-link {{ Request::is('admin/product/*') ? 'active' : '' }}">Stock</a></li>-->
            <li class="sub-item"><a href="{{ route('landing.manage')}}" class="sub-link {{  Request::is('admin/landing/*') ? 'active' : '' }}">Landing Page Product</a></li>
        </ul>
    </li>





    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ Request::is('admin/slider/*') || Request::is('images*') ? 'active' : '' }}">
            <i class="fas fa-fw fa-images"></i>
            <span class="menu-item-label">Media</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{route('slider.manage')}}" class="sub-link {{  Request::is('admin/slider/*') ? 'active' : '' }}">Slider</a></li>
            <li class="sub-item"><a href="{{url('images')}}"  class="sub-link {{ Request::is('images*') ? 'active' : '' }}">All Media</a></li>


        </ul>
    </li>



         <li class="br-menu-item">
          <a href="#" class="br-menu-link with-sub {{ Request::is('admin/courier/*') || Request::is('admin/city/*')||Request::is('admin/zone/*')|| Request::is('admin/shipping/*') ? 'active' : '' }}">
            <i class="fas fa fa-truck"></i>
            <span class="menu-item-label">Couriers</span>
          </a><!-- br-menu-link -->
          <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{route('courier.manage')}}" class="sub-link {{ Request::is('admin/courier/*') ? 'active' : '' }}">Courier</a></li>
            <li class="sub-item"><a href="{{route('city.manage')}}"  class="sub-link {{ Request::is('admin/city/*') ? 'active' : '' }}">City</a></li>
            <li class="sub-item"><a href="{{route('zone.manage')}}" class="sub-link {{route('courier.manage')}}" class="sub-link {{ Request::is('admin/zone/*') ? 'active' : '' }}">Zone</a></li><li class="sub-item"><a href="{{ route('shipping.manage')}}" class="sub-link {{route('courier.manage')}}" class="sub-link {{ Request::is('admin/shipping/*') ? 'active' : '' }}">Shipping Methods</a></li>

          </ul>
        </li>

{{--        <li class="br-menu-item">--}}
{{--          <a href="{{ route('shipping.manage')}}" class="br-menu-link {{ Request::is('admin/shipping/*') ? 'active' : '' }}">--}}
{{--            <i class="fas fa fa-truck"></i>--}}
{{--            <span class="menu-item-label">Shipping Methods</span>--}}
{{--          </a>--}}
{{--        </li>--}}


    <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ Request::is('admin/user/*') || Request::is('admin/user_products*') || Request::is('admin/customers*')  ? 'active' : '' }}">
            <i class="fas fa fa-user"></i>
            <span class="menu-item-label">Users</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{route('user.manage')}}" class="sub-link {{ Request::is('admin/user/*') ? 'active' : '' }}">User</a></li>
            <li class="sub-item"><a href="{{route('user_products')}}"  class="sub-link {{ Request::is('admin/user_products*') ? 'active' : '' }}">User Products</a></li>
            <li class="sub-item"><a href="{{route('customer.manage')}}"  class="sub-link {{  Request::is('admin/customers*') ? 'active' : '' }}"> Customers</a></li>

        </ul>
    </li>







        <li class="br-menu-item">
          <a href="#" class="br-menu-link with-sub {{ Request::is('admin/employee-orders*') || Request::is('admin/ordered_product_c')||Request::is('admin/product_orders*') ? 'active' : '' }}">
            <i class="fas fa fa-file-excel"></i>
            <span class="menu-item-label">Reports</span>
          </a><!-- br-menu-link -->
          <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{route('employee_orders')}}" class="sub-link {{ Request::is('admin/employee-orders*') ? 'active' : '' }}">Orders (Employee)</a></li>
              <li class="sub-item"><a href="{{route('product_orders')}}" class="sub-link {{route('product_orders')}}" class="sub-link {{ Request::is('admin/product_orders*') ? 'active' : '' }}">Orders (Products)</a></li>
            <li class="sub-item"><a href="{{route('ordered_product_c')}}"  class="sub-link {{ Request::is('admin/ordered_product_c') ? 'active' : '' }}">Order Status (Products)</a></li>



          </ul>
        </li>

        <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub {{ Request::is('admin/settings*') || Request::is('admin/attribute/manage') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span class="menu-item-label">Settings</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
            <li class="sub-item"><a href="{{route('settings.edit')}}" class="sub-link {{ Request::is('admin/settings') ? 'active' : '' }}">Web</a></li>

            <li class="sub-item"><a href="{{route('settings.web')}}"  class="sub-link {{ Request::is('admin/settings/page') ? 'active' : '' }}">Page</a></li>

            <li class="sub-item"><a href="{{route('attribute.manage')}}"  class="sub-link {{ Request::is('admin/attribute/manage') ? 'active' : '' }}">Attribute</a></li>

            <li class="sub-item"><a href="{{route('settings.pathaoIndex')}}"  class="sub-link {{ Request::is('admin/settings/pathao-api') ? 'active' : '' }}">Courier API</a></li>

            <li class="sub-item"><a href="{{route('settings.whatsappIndex')}}"  class="sub-link {{ Request::is('admin/settings/whatsapp') ? 'active' : '' }}">WhatsApp</a></li>
            <li class="sub-item"><a href="{{route('settings.smsIndex')}}"  class="sub-link {{ Request::is('admin/settings/sms') ? 'active' : '' }}">SMS API</a></li>

        </ul>
    </li>




          <li class="br-menu-item">
              <a href="#" class="br-menu-link with-sub {{ Request::is('admin/reset*') ? 'active' : '' }}">
                  <i class="fa-solid fa-unlock"></i>
                  <span class="menu-item-label">Profile</span>
              </a><!-- br-menu-link -->
              <ul class="br-menu-sub">


                      <li class="sub-item"><a href="{{route('admin.reset')}}" class="sub-link {{ Request::is('admin/reset*') ? 'active' : '' }}">Change Password</a></li>


              </ul>
          </li>

@endif


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



























