<div class="main-menu">
  <ul class="whitespace-nowrap">
    <li class="menu-item-has-children !hidden">
      <a href="{{ route('home') }}" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="icon-park:dashboard-car"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Mua Tài Khoản') }}
          </div>
        </div>
      </a>
    </li>

    <li class="menu-item-has-children">
      <a href="{{ route('home') }}" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="ic:twotone-dashboard"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Trang Chủ') }}
          </div>
        </div>
      </a>
    </li>

    <li class="menu-item-has-children">
      <a href="javascript:void()" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="ic:twotone-dashboard"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Lịch Sử Mua Nick') }}
          </div>
        </div>
        <div class="relative top-1 flex-none text-sm leading-[1] ltr:ml-3 rtl:mr-3">
          <iconify-icon icon="heroicons-outline:chevron-down"></iconify-icon>
        </div>
      </a>
      <ul class="sub-menu">
        <li>
          <a href="{{ route('account.orders.accounts') }}" class="transition-all hover:scale-[105%]">
            <div class="flex items-start space-x-2 rtl:space-x-reverse">
              <iconify-icon icon="healthicons:1" class="text-base leading-[1]"></iconify-icon>
              <span class="leading-[1]">
                {{ __('Tài Khoản Loại 1') }}
              </span>
            </div>
          </a>
        </li>
        <li>
          <a href="{{ route('account.orders.accounts-v2') }}" class="transition-all hover:scale-[105%]">
            <div class="flex items-start space-x-2 rtl:space-x-reverse">
              <iconify-icon icon="healthicons:2" class="text-base leading-[1]"></iconify-icon>
              <span class="leading-[1]">
                {{ __('Tài Khoản Loại 2') }}
              </span>
            </div>
          </a>
        </li>

      </ul>
    </li>

    <li class="menu-item-has-children">
      <a href="javascript:void()" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="material-symbols:other-admission-outline-rounded"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Dịch Vụ Khác') }}
          </div>
        </div>
        <div class="relative top-1 flex-none text-sm leading-[1] ltr:ml-3 rtl:mr-3">
          <iconify-icon icon="heroicons-outline:chevron-down"></iconify-icon>
        </div>
      </a>
      <ul class="sub-menu">
        <li>
          <a href="{{ route('account.orders.boosting') }}" class="transition-all hover:scale-[105%]">
            <div class="flex items-start space-x-2 rtl:space-x-reverse">
              <iconify-icon icon="arcticons:boost" class="text-base leading-[1]"></iconify-icon>
              <span class="leading-[1]">
                {{ __('Lịch Sử Cày Thuê') }}
              </span>
            </div>
          </a>
        </li>
        <li>
          <a href="{{ route('account.withdraws.index') }}" class="transition-all hover:scale-[105%]">
            <div class="flex items-start space-x-2 rtl:space-x-reverse">
              <iconify-icon icon="fluent-mdl2:game" class="text-base leading-[1]"></iconify-icon>
              <span class="leading-[1]">
                {{ __('Rút Thưởng Trò Chơi') }}
              </span>
            </div>
          </a>
        </li>
        <li>
          <a href="{{ route('account.orders.items') }}" class="transition-all hover:scale-[105%]">
            <div class="flex items-start space-x-2 rtl:space-x-reverse">
              <iconify-icon icon="tabler:lego" class="text-base leading-[1]"></iconify-icon>
              <span class="leading-[1]">
                {{ __('Lịch Sử Mua Vật Phẩm') }}
              </span>
            </div>
          </a>
        </li>

      </ul>
    </li>

    <li class="menu-item-has-children">
      <a href="{{ route('account.profile.index') }}" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="iconamoon:profile"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Tài Khoản') }}
          </div>
        </div>
      </a>
    </li>

    <li class="menu-item-has-children">
      <a href="{{ route('account.deposits.index') }}" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="gg:credit-card"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Nạp Tiền') }}
          </div>
        </div>
      </a>
    </li>

    <li class="menu-item-has-children">
      <a href="{{ route('account.profile.transactions') }}" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="grommet-icons:money"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Dòng Tiền') }}
          </div>
        </div>
      </a>
    </li>

    <li class="menu-item-has-children">
      <a href="{{ route('articles.index') }}" class="transition-all hover:scale-[105%]">
        <div class="flex flex-1 items-center space-x-[6px] rtl:space-x-reverse">
          <span class="icon-box">
            <iconify-icon icon="wpf:news"></iconify-icon>
          </span>
          <div class="text-box"> {{ __('Tin Tức') }}
          </div>
        </div>
      </a>
    </li>
  </ul>
</div>
<!-- end top menu -->
