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

<div class="br-logo">
    <a href="">
        <span>
            @if($settings)
            <img src="{{ asset('backend/img/'.$settings->logo)  }}" class="img-fluid" width="70">
            @endif
        </span>
    </a>
</div>

<div class="br-sideleft sideleft-scrollbar colorful-sidebar">

    <ul class="br-sideleft-menu">

        <li class="br-menu-item">
            <a href="{{ route('employee.dashboard')}}" class="br-menu-link {{ Request::is('employee/dashboard*') ? 'active' : '' }}">
                <i class="fas fa-fw fa-desktop"></i>
                <span class="menu-item-label">Dashboard</span>
            </a>
        </li>

        <li class="br-menu-item">
            <a href="{{ route('employee.order.newmanage')}}" class="br-menu-link {{ Request::is('employee/order-management/*') && !Request::is('employee/order-management/barcode-scan*') ? 'active' : '' }}">
                <i class="fas fa-fw fa-cart-plus"></i>
                <span class="menu-item-label">Orders</span>
            </a>
        </li>

        <li class="br-menu-item d-none">
            <a href="{{ route('employee.order.barcodeScan')}}" class="br-menu-link {{ Request::is('employee/order-management/barcode-scan*') ? 'active' : '' }}">
                <i class="fas fa-fw fa-barcode"></i>
                <span class="menu-item-label">Barcode Scanner</span>
            </a>
        </li>

            <li class="br-menu-item">
            <a href="{{ route('order.incomplete') }}"
              class="br-menu-link {{ Request::is('order-management/incomplete*') ? 'active' : '' }}">
                <i class="fas fa-fw fa-exclamation-circle"></i>
                <span class="menu-item-label">Incomplete Orders</span>
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
