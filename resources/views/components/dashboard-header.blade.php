<div class="sticky top-0 z-[9]" id="app_header">
  <div class="app-header z-[999] bg-white shadow-sm dark:bg-slate-800 dark:shadow-slate-700">
    <div class="flex h-full items-center justify-between">
      <div class="vertical-box flex items-center space-x-4 rtl:space-x-reverse md:space-x-4">
        <div class="inline-block xl:hidden">
          <x-application-logo class="mobile-logo" />
        </div>
        <button class="smallDeviceMenuController open-sdiebar-controller hidden md:inline-block xl:hidden">
          <iconify-icon class="relative top-[2px] bg-transparent text-xl leading-none text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
        </button>
        <button class="sidebarOpenButton !ml-0 text-xl text-slate-900 dark:text-white">
          <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
        </button>
        {{-- <x-header-search /> --}}
      </div>
      <!-- end vertcial -->

      <div class="horizental-box items-center space-x-4 rtl:space-x-reverse">
        <x-application-logo />
        <button class="smallDeviceMenuController open-sdiebar-controller hidden md:inline-block xl:hidden">
          <iconify-icon class="relative top-[2px] bg-transparent text-xl leading-none text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
        </button>
        {{-- <x-header-search /> --}}

      </div>
      <!-- end horizontal -->

      <!-- start horizontal nav -->
      <x-topbar-menu />
      <!-- end horizontal nav -->

      <div class="nav-tools leading-0 flex items-center space-x-3 rtl:space-x-reverse lg:space-x-5">
        <x-nav-lang-dropdown />
        @if (theme_config('enable_custom_theme', false))
          <x-dark-light />
          <x-gray-scale />
        @endif
        <x-nav-user-dropdown />
        <button class="smallDeviceMenuController leading-0 block md:hidden">
          <iconify-icon class="cursor-pointer text-2xl text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
        </button>
        <!-- end mobile menu -->
      </div>
      <!-- end nav tools -->
    </div>
  </div>
</div>

<!-- BEGIN: Search Modal -->
<div class="modal fade backdrop-brightness-10 fixed inset-0 left-0 top-0 hidden h-full w-full overflow-y-auto overflow-x-hidden bg-slate-900/40 outline-none backdrop-blur-sm backdrop-filter" id="searchModal"
  tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog pointer-events-none relative top-1/4 w-auto">
    <div class="modal-content pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-slate-900">
      <form>
        <div class="relative">
          <button class="absolute left-0 top-1/2 flex h-full w-9 -translate-y-1/2 items-center justify-center text-xl dark:text-slate-300">
            <iconify-icon icon="heroicons-solid:search"></iconify-icon>
          </button>
          <input type="text" class="form-control !py-[14px] !pl-10" placeholder="Search" autofocus>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END: Search Modal -->
