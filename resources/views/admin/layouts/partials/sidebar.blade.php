<aside class="app-sidebar sticky" id="sidebar">

  <!-- Start::main-sidebar-header -->
  <div class="main-sidebar-header">
    <a href="{{ route('admin.dashboard') }}" class="header-logo">
      <img src="/cmsnt/cmsnt_light.png" alt="logo" class="desktop-logo">
      <img src="/_admin/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
      <img src="/cmsnt/cmsnt_dark.png" alt="logo" class="desktop-dark">
      <img src="/_admin/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
      <img src="/cmsnt/cmsnt_light.png" alt="logo" class="desktop-white">
      <img src="/_admin/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
    </a>
  </div>
  <!-- End::main-sidebar-header -->

  <!-- Start::main-sidebar -->
  <div class="main-sidebar" id="sidebar-scroll">

    <!-- Start::nav -->
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
      <div class="slide-left" id="slide-left">
        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
          <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
        </svg>
      </div>
      <ul class="main-menu">
        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Main</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bx bx-home side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Bảng Điều Khiển') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Quick Access</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide has-sub {{ request()->routeIs('admin.accounts.*') ? 'open' : '' }}">
          <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
            <i class="bx bx-lemon side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Shop Nick') }}</span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Dịch Vụ Cày Thuê') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accounts.items', ['sold' => 1]) }}"
                class="side-menu__item {{ request()->routeIs('admin.accounts.items') && request()->input('sold') == 1 ? 'active' : '' }}">{{ __t('Quản Lý Đơn Hàng') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accounts.items', ['sold' => 0]) }}"
                class="side-menu__item {{ request()->routeIs('admin.accounts.items') && request()->input('sold') == 0 ? 'active' : '' }}">{{ __t('Quản Lý Tài Khoản') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accounts.categories') }}" class="side-menu__item {{ request()->routeIs('admin.accounts.categories') ? 'active' : '' }}">{{ __t('Quản Lý Chuyên Mục') }}</a>
            </li>
          </ul>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide has-sub {{ request()->routeIs('admin.accountsv2.*') ? 'open' : '' }}">
          <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.accountsv2.*') ? 'active' : '' }}">
            <i class="bx bx-sushi side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Shop Nick v2') }}</span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Quản Lý Shop Nick v2') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accountsv2.items') }}" class="side-menu__item {{ request()->routeIs('admin.accountsv2.items') ? 'active' : '' }}">{{ __t('Quản Lý Đơn Hàng') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accountsv2.categories') }}" class="side-menu__item {{ request()->routeIs('admin.accountsv2.categories') ? 'active' : '' }}">{{ __t('Quản Lý Chuyên Mục') }}</a>
            </li>
          </ul>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">System Settings</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.pin-groups') }}" class="side-menu__item {{ request()->routeIs('admin.pin-groups') ? 'active' : '' }}">
            <i class="bx bx-pin side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Danh Sách Ghim') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.settings.general') }}" class="side-menu__item {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
            <i class="bx bx-cog side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Cài Đặt Hệ Thống') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.settings.apis') }}" class="side-menu__item {{ request()->routeIs('admin.settings.apis') ? 'active' : '' }}">
            <i class="bx bx-share side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Cấu Hình API Keys') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.settings.notices') }}" class="side-menu__item {{ request()->routeIs('admin.settings.notices') ? 'active' : '' }}">
            <i class="bx bx-comment side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Cài Đặt Thông Báo') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Users Manager</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.users') }}" class="side-menu__item {{ request()->routeIs('admin.users') || request()->routeIs('admin.users.show') ? 'active' : '' }}">
            <i class="bx bx-user side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Thành Viên') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.banks') }}" class="side-menu__item {{ request()->routeIs('admin.banks') ? 'active' : '' }}">
            <i class="bx bx-credit-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Thông Tin Nạp Tiền') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.transactions') }}" class="side-menu__item {{ request()->routeIs('admin.transactions') ? 'active' : '' }}">
            <i class="bx bx-data side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Giao Dịch') }}</span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.histories') }}" class="side-menu__item {{ request()->routeIs('admin.histories') ? 'active' : '' }}">
            <i class="bx bx-server side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Hoạt Động') }}</span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.cards') }}" class="side-menu__item {{ request()->routeIs('admin.cards') ? 'active' : '' }}">
            <i class="bx bx-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Nạp Thẻ Cào') }}</span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.banks.deposit') }}" class="side-menu__item {{ request()->routeIs('admin.banks.deposit') ? 'active' : '' }}">
            <i class="bx bx-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Lịch Sử Nạp Tiền Bank') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Staff Manager</span></li>
        <!-- End::slide__category -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.staff.withdraws') }}" class="side-menu__item {{ request()->routeIs('admin.staff.withdraws') ? 'active' : '' }}">
            <i class="bx bx-credit-card side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Yêu Cầu Rút Tiền') }} <span class="badge bg-primary-gradient">{{ \App\Models\CollaWithdraw::where('status', 'Pending')->count() }}</span></span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.affiliates') }}" class="side-menu__item {{ request()->routeIs('admin.affiliates') ? 'active' : '' }}">
            <i class="bx bx-share side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Tiếp Thị Liên Kết') }} <span class="badge bg-danger-gradient">{{ \App\Models\AffiliateUser::count() }}</span></span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Data Manager</span></li>
        <!-- End::slide__category -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.posts') }}" class="side-menu__item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
            <i class="bx bx-comment side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Bài Viết') }}</span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.games.spin-quest') }}" class="side-menu__item {{ request()->routeIs('admin.games.spin-quest.*') ? 'active' : '' }}">
            <i class="bx bx-play side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Vòng Quay') }}</span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.inventories.vars') }}" class="side-menu__item {{ request()->routeIs('admin.inventories.vars') ? 'active' : '' }}">
            <i class="bx bx-gift side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản lý kho hàng') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.invoices') }}" class="side-menu__item {{ request()->routeIs('admin.invoices') ? 'active' : '' }}">
            <i class="bx bx-dollar side-menu__icon"></i>
            <span class="side-menu__label">Quản Lý Hoá Đơn<span class="badge bg-warning-transparent ms-2">Hot</span></span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.inventories') }}" class="side-menu__item {{ request()->routeIs('admin.inventories') ? 'active' : '' }}">
            <i class="bx bx-server side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản lý phần thưởng') }}</span>
          </a>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.inventory.withdraws') }}" class="side-menu__item {{ request()->routeIs('admin.inventory.withdraws') ? 'active' : '' }}">
            <i class="bx bx-gift side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Yêu cầu rút thưởng ') }} <span class="badge bg-danger">{{ \App\Models\WithdrawData::where('type', 'withdraw')->where('status', 'Pending')->count() }}</span></span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide -->
        <li class="slide">
          <a href="{{ route('admin.games.withdraws') }}" class="side-menu__item {{ request()->routeIs('admin.games.withdraws') ? 'active' : '' }}">
            <i class="bx bx-gift side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Yêu Cầu Trả Thưởng') }}</span>
          </a>
        </li>
        <!-- End::slide -->
        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">Product Manager</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <li class="slide has-sub {{ request()->routeIs('admin.boosting.*') ? 'open' : '' }}">
          <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.boosting.*') ? 'active' : '' }}">
            <i class="bx bx-shower side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Dịch Vụ Cày Thuê') }} <span class="badge bg-primary-gradient">{{ \App\Models\GBOrder::where('status', 'Pending')->count() }}</span></span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Dịch Vụ Cày Thuê') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.boosting.orders') }}" class="side-menu__item {{ request()->routeIs('admin.boosting.orders') ? 'active' : '' }}">{{ __t('Quản Lý Đơn Hàng') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.boosting.categories') }}" class="side-menu__item {{ request()->routeIs('admin.boosting.categories') ? 'active' : '' }}">{{ __t('Quản Lý Chuyên Mục') }}</a>
            </li>
          </ul>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide has-sub {{ request()->routeIs('admin.items.*') ? 'open' : '' }}">
          <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
            <i class="bx bx-archive side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Dịch Vụ Vật Phẩm') }} <span class="badge bg-danger-gradient">{{ \App\Models\ItemOrder::where('status', 'Pending')->count() }}</span></span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Dịch Vụ Vật Phẩm') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.items.orders') }}" class="side-menu__item {{ request()->routeIs('admin.items.orders') ? 'active' : '' }}">{{ __t('Quản Lý Đơn Hàng') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.items.categories') }}" class="side-menu__item {{ request()->routeIs('admin.items.categories') ? 'active' : '' }}">{{ __t('Quản Lý Chuyên Mục') }}</a>
            </li>
          </ul>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide has-sub {{ request()->routeIs('admin.accounts.*') ? 'open' : '' }}">
          <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
            <i class="bx bx-lemon side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Shop Nick') }}</span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Dịch Vụ Cày Thuê') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accounts.items', ['sold' => 1]) }}"
                class="side-menu__item {{ request()->routeIs('admin.accounts.items') && request()->input('sold') == 1 ? 'active' : '' }}">{{ __t('Quản Lý Đơn Hàng') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accounts.items', ['sold' => 0]) }}"
                class="side-menu__item {{ request()->routeIs('admin.accounts.items') && request()->input('sold') == 0 ? 'active' : '' }}">{{ __t('Quản Lý Tài Khoản') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accounts.categories') }}" class="side-menu__item {{ request()->routeIs('admin.accounts.categories') ? 'active' : '' }}">{{ __t('Quản Lý Chuyên Mục') }}</a>
            </li>
          </ul>
        </li>
        <!-- End::slide -->

        <!-- Start::slide -->
        <li class="slide has-sub {{ request()->routeIs('admin.accountsv2.*') ? 'open' : '' }}">
          <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.accountsv2.*') ? 'active' : '' }}">
            <i class="bx bx-sushi side-menu__icon"></i>
            <span class="side-menu__label">{{ __t('Quản Lý Shop Nick v2') }}</span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
          </a>
          <ul class="slide-menu child1">
            <li class="slide side-menu__label1">
              <a href="javascript:void(0)">{{ __t('Quản Lý Shop Nick v2') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accountsv2.items') }}" class="side-menu__item {{ request()->routeIs('admin.accountsv2.items') ? 'active' : '' }}">{{ __t('Quản Lý Đơn Hàng') }}</a>
            </li>
            <li class="slide">
              <a href="{{ route('admin.accountsv2.categories') }}" class="side-menu__item {{ request()->routeIs('admin.accountsv2.categories') ? 'active' : '' }}">{{ __t('Quản Lý Chuyên Mục') }}</a>
            </li>
          </ul>
        </li>
        <!-- End::slide -->

      </ul>
      <div class="slide-right" id="slide-right">
        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
          <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
        </svg>
      </div>
    </nav>
    <!-- End::nav -->

  </div>
  <!-- End::main-sidebar -->

</aside>
