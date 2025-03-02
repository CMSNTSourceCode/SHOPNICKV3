<div class="leading-0 hidden w-full md:block">
  <button class="inline-flex items-center rounded-lg text-center text-sm font-medium text-slate-800 focus:outline-none focus:ring-0 dark:text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <div class="h-7 w-7 flex-1 rounded-full ltr:mr-[10px] rtl:ml-[10px] lg:h-8 lg:w-8">
      <img class="block h-full w-full rounded-full object-cover" src="{{ Auth::user()->avatar ?? '/images/avatar/av-1.svg' }}" alt="user" />
    </div>
    <div class="ltr:text-left rtl:text-right">
      <span class="hidden flex-none items-center overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold text-slate-600 dark:text-white lg:flex">
        {{ Str::limit(Auth::user()?->username, 20) ?? __('Khách') }}
      </span>
      <small class="text-danger-600 block text-[15px] font-bold">{{ Helper::formatCurrency(auth()->user()?->balance ?? 0) }}</small>
    </div>
    <svg class="ml-[10px] inline-block h-[16px] w-[16px] text-base rtl:mr-[10px] dark:text-white lg:inline-block" aria-hidden="true" fill="none" stroke="currentColor" viewbox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
  </button>
  <!-- Dropdown menu -->
  <div class="dropdown-menu !top-[23px] z-10 hidden w-44 divide-y divide-slate-100 overflow-hidden rounded-md border bg-white shadow dark:border-slate-700 dark:bg-slate-800">
    <ul class="py-1 text-sm text-slate-800 dark:text-slate-200" :class="listView ? 'z-20 opacity-100 top-[61px]' : 'opacity-0 -z-20 top-5'" x-show="listView" @click.away="listView = false">
      @if (Auth::check())
        <li>
          <a href="{{ route('account.deposits.index') }}" class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white"
            @class([
                'active' => request()->routeIs('account.deposits.index'),
            ])>
            <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="carbon:money"></iconify-icon>
            <span class="dropdown-option">
              @lang('Nạp Tiền')
            </span>
          </a>
        </li>
        <li>
          <a href="{{ route('account.profile.index') }}" class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white"
            @class([
                'country-list',
                'active' => request()->routeIs('profiles.index'),
            ])>
            <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="carbon:user-avatar">
            </iconify-icon>
            <span class="dropdown-option">
              @lang('Thông Tin')
            </span>
          </a>
        </li>
        @if (Auth::check() && Auth::user()->role === 'admin')
          <li>
            <a href="{{ route('admin.dashboard') }}" class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white">
              <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="carbon:share">
              </iconify-icon>
              <span class="dropdown-option">
                @lang('Trang Quản Trị')
              </span>
            </a>
          </li>
        @elseif(Auth::check() && Auth::user()->role === 'collaborator')
          <li>
            <a href="{{ route('staff.dashboard') }}" class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white">
              <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="carbon:share">
              </iconify-icon>
              <span class="dropdown-option">
                @lang('Cộng Tác Viên')
              </span>
            </a>
          </li>
        @endif
        {{-- Logout --}}
        <li>
          <form method="POST" action="{{ route('logout') }}"
            class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white">
            @csrf
            <button type="submit" class="country-list flex items-start">
              <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="carbon:logout">
              </iconify-icon>
              <span class="dropdown-option">
                @lang('Đăng Xuất')
              </span>
            </button>
          </form>
        </li>
      @else
        <li>
          <a href="{{ route('login') }}" class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white"
            @class([
                'active' => request()->routeIs('login'),
            ])>
            <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="material-symbols:login"></iconify-icon>
            <span class="dropdown-option">
              @lang('Đăng Nhập')
            </span>
          </a>
        </li>
        <li>
          <a href="{{ route('register') }}" class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white"
            @class([
                'active' => request()->routeIs('register'),
            ])>
            <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="solar:user-linear"></iconify-icon>
            <span class="dropdown-option">
              @lang('Tạo Tài Khoản')
            </span>
          </a>
        </li>
        <li>
          <a href="{{ route('password.request') }}" class="font-inter flex items-center px-4 py-2 text-sm font-normal text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-600 dark:hover:text-white"
            @class([
                'active' => request()->routeIs('password.request'),
            ])>
            <iconify-icon class="text-textColor mr-2 text-lg dark:text-white" icon="solar:password-linear"></iconify-icon>
            <span class="dropdown-option">
              @lang('Quên Mật Khẩu?')
            </span>
          </a>
        </li>
      @endif
    </ul>
  </div>
</div>
