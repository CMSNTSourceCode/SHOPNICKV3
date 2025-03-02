<!-- BEGIN: Sidebar -->
<div class="sidebar-wrapper group hidden w-0 xl:block xl:w-[248px]">
  <div id="bodyOverlay" class="fixed top-0 z-10 hidden h-screen w-screen bg-slate-900 bg-opacity-50 backdrop-blur-sm">
  </div>
  <div class="logo-segment">

    <!-- Application Logo -->
    <x-application-logo />

    <!-- Sidebar Type Button -->
    <div id="sidebar_type" class="cursor-pointer text-lg text-slate-900 dark:text-white">
      <iconify-icon class="sidebarDotIcon extend-icon text-slate-900 dark:text-slate-200" icon="fa-regular:dot-circle"></iconify-icon>
      <iconify-icon class="sidebarDotIcon collapsed-icon text-slate-900 dark:text-slate-200" icon="material-symbols:circle-outline"></iconify-icon>
    </div>
    <button class="sidebarCloseIcon inline-block text-2xl md:hidden">
      <iconify-icon class="text-slate-900 dark:text-slate-200" icon="clarity:window-close-line"></iconify-icon>
    </button>
  </div>
  <div id="nav_shadow" class="nav_shadow nav-shadow pointer-events-none absolute top-[80px] z-[1] h-[60px] w-full opacity-0 transition-all duration-200"></div>
  <div class="sidebar-menus z-50 h-[calc(100%-80px)] bg-white px-4 py-2 dark:bg-slate-800" id="sidebar_menus">
    <ul class="sidebar-menu">
      <li class="sidebar-menu-title">{{ __('MENU') }}</li>
      <li>
        <a href="{{ route('home') }}" class="navItem {{ request()->routeIs('home') ? 'active' : '' }}">
          <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="material-symbols:dashboard-outline"></iconify-icon>
            <span>{{ __('Mua Tài Khoản') }}</span>
          </span>
        </a>
      </li>
      <li class="">
        <a href="javascript:void(0)" class="navItem">
          <span class="flex items-center">
            <iconify-icon class=" nav-icon" icon="humbleicons:cart"></iconify-icon>
            <span>Lịch Sử Mua Nick</span>
          </span>
          <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('account.orders.accounts') }}" class="{{ request()->routeIs('account.orders.accounts') ? 'active' : '' }}">{{ __('Tài Khoản Loại 1') }}</a>
          </li>
          <li>
            <a href="{{ route('account.orders.accounts-v2') }}" class="{{ request()->routeIs('account.orders.accounts-v2') ? 'active' : '' }}">{{ __('Tài Khoản Loại 2') }}</a>
          </li>
        </ul>
      </li>
      <li class="">
        <a href="javascript:void(0)" class="navItem">
          <span class="flex items-center">
            <iconify-icon class=" nav-icon" icon="tabler:lego"></iconify-icon>
            <span>Đơn Dịch Vụ Khác</span>
          </span>
          <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{ route('account.orders.boosting') }}" class="{{ request()->routeIs('account.orders.boosting') ? 'active' : '' }}">{{ __('Lịch Sử Cày Thuê') }}</a>
          </li>
          <li>
            <a href="{{ route('account.withdraws.index') }}" class="{{ request()->routeIs('account.withdraws.index') ? 'active' : '' }}">{{ __('Rút Thưởng Trò Chơi') }}</a>
          </li>
          <li>
            <a href="{{ route('account.orders.items') }}" class="{{ request()->routeIs('account.orders.items') ? 'active' : '' }}">{{ __('Lịch Sử Mua Vật Phẩm') }}</a>
          </li>
        </ul>
      </li>

      <li>
        <a href="{{ route('account.profile.transactions') }}" class="navItem {{ request()->routeIs('account.profile.transactions') ? 'active' : '' }}">
          <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="ph:money"></iconify-icon>
            <span>{{ __('Lịch Sử Giao Dịch') }}</span>
          </span>
        </a>
      </li>
      <li>
        <a href="{{ route('account.deposits.index') }}" class="navItem {{ request()->routeIs('account.deposits.index') ? 'active' : '' }}">
          <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="ph:credit-card-bold"></iconify-icon>
            <span>{{ __('Nạp Tiền Tài Khoản') }}</span>
          </span>
        </a>
      </li>
      <li>
        <a href="{{ route('account.profile.index') }}" class="navItem {{ request()->routeIs('account.profile.index') ? 'active' : '' }}">
          <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="solar:user-broken"></iconify-icon>
            <span>{{ __('Thông Tin Tài Khoản') }}</span>
          </span>
        </a>
      </li>
      <li>
        <a href="{{ route('articles.index') }}" class="navItem {{ request()->routeIs('articles.index') ? 'active' : '' }}">
          <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="fluent:form-new-48-regular"></iconify-icon>
            <span>{{ __('Bài Viết Hướng Dẫn') }}</span>
          </span>
        </a>
      </li>

      @if (Auth::check() && Auth::user()->role === 'admin')
        <li>
          <a href="{{ route('admin.dashboard') }}" class="navItem {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="flex items-center">
              <iconify-icon class="nav-icon" icon="carbon:share"></iconify-icon>
              <span>{{ __('Trang Quản Trị Viên') }}</span>
            </span>
          </a>
        </li>
      @elseif (Auth::check() && Auth::user()->role === 'collaborator')
        <li>
          <a href="{{ route('staff.dashboard') }}" class="navItem {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <span class="flex items-center">
              <iconify-icon class="nav-icon" icon="carbon:share"></iconify-icon>
              <span>{{ __('Trang Cộng Tác Viên') }}</span>
            </span>
          </a>
        </li>
      @endif

    </ul>
  </div>
</div>
<!-- End: Sidebar -->
