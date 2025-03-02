<!-- Back to top button -->
<button type="button" data-te-ripple-init data-te-ripple-color="light"
  class="!fixed bottom-5 right-5 hidden rounded-full bg-primary p-3 mb-[50px] text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg"
  id="btn-back-to-top">
  <svg aria-hidden="true" focusable="false" data-prefix="fas" class="h-4 w-4" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
    <path fill="currentColor"
      d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z">
    </path>
  </svg>
</button>
<script>
  // Get the button
  const mybutton = document.getElementById("btn-back-to-top");

  // When the user scrolls down 20px from the top of the document, show the button

  const scrollFunction = () => {
    if (
      document.body.scrollTop > 20 ||
      document.documentElement.scrollTop > 20
    ) {
      mybutton.classList.remove("hidden");
    } else {
      mybutton.classList.add("hidden");
    }
  };
  const backToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  };

  // When the user clicks on the button, scroll to the top of the document
  mybutton.addEventListener("click", backToTop);

  window.addEventListener("scroll", scrollFunction);
</script>
@php
  $shop_info = Helper::getConfig('shop_info');
  $contact_info = Helper::getConfig('contact_info');
@endphp
<!-- BEGIN: Footer For Desktop and tab -->
<footer id="footer" class="mb-[60px] md:mb-0">
  <div class="py-3" style="background: #1b1a1a">
    <div class="relative mx-auto mt-2 grid w-full max-w-6xl grid-cols-2 gap-4 px-4 font-semibold text-white md:mb-0 md:px-0">
      <div class="col-span-2 py-2 md:col-span-1">
        <div class="flex flex-col items-center">
          <a href="{{ route('home') }}">
            <img src="{{ setting('logo_dark') ?? '/_assets/images/cmsnt_dark.png' }}" alt="{{ setting('title') }}" class="mb-2 max-w-[170px]">
          </a>
          @if (isset($shop_info['footer_text_1']))
            <span class="text-center">{!! $shop_info['footer_text_1'] !!}</span>
          @endif
        </div>
      </div>
      <div class="col-span-2 py-2 md:col-span-1">
        <span class="flex flex-col items-center">
          <h5 class="mb-6 text-white">{{ __('LIÊN HỆ HỖ TRỢ') }}</h5>
          @if (isset($shop_info['footer_text_2']))
            <span class="text-center">{!! $shop_info['footer_text_2'] !!}</span>
          @endif
        </span>
      </div>
      <div class="col-span-2 mb-4 py-2">
        <div class="grid grid-cols-1 gap-6 text-center md:flex md:flex-nowrap md:justify-around">

          @isset($contact_info['facebook'])
            <a href="{{ $contact_info['facebook'] ?? '#!' }}" target="_blank" class="btn btn-sm btn-outline-secondary !text-white"><i class="fa-brands fa-square-facebook"></i> Facebook</a>
          @endisset
          @isset($contact_info['telegram'])
            <a href="{{ $contact_info['telegram'] ?? '#!' }}" target="_blank" class="btn btn-sm btn-outline-secondary !text-white"><i class="fa-brands fa-telegram"></i> Telegram</a>
          @endisset
          @isset($contact_info['phone_no'])
            <a href="tel:{{ $contact_info['phone_no'] ?? '+84123456789' }}" class="btn btn-sm btn-outline-secondary !text-white"><i class="fa-solid fa-phone"></i>
              {{ $contact_info['phone_no'] ?? '+84123456789' }}</a>
          @endisset
          @isset($contact_info['email'])
            <a href="mail:{{ $contact_info['email'] ?? 'admin@webmaster.com' }}" class="btn btn-sm btn-outline-secondary !text-white"><i class="fa-solid fa-inbox"></i>
              {{ $contact_info['email'] ?? 'admin@webmaster.com' }}</a>
          @endisset
        </div>
      </div>
    </div>
  </div>
  <div class="site-footer bg-[#151212] px-6 py-3 text-slate-500 ltr:ml-[248px] rtl:mr-[248px] dark:bg-slate-800 dark:text-slate-300 hidden md:block">
    <div class="flex justify-between font-medium text-white">
      <div>{{ __('Phát triển bởi') }} <a href="#!" target="_blank">{{ strtoupper(Helper::getDomain()) }}</a></div>
      <div>Phiên bản: {{ currentVersion() }}</div>
    </div>
  </div>
</footer>
<!-- END: Footer For Desktop and tab -->

<div
  class="custom-dropshadow footer-bg bothrefm-0 fixed bottom-0 left-0 z-[9999] flex w-full items-center justify-around bg-white bg-no-repeat px-4 py-[12px] backdrop-blur-[40px] backdrop-filter dark:bg-slate-700 md:hidden sm:mt-5">
  <a href="{{ route('account.deposits.index') }}">
    <div>
      <span class="relative mb-1 flex cursor-pointer flex-col items-center justify-center rounded-full text-[20px] text-slate-900 dark:text-white">
        <iconify-icon icon="icon-park:dollar"></iconify-icon>
      </span>
      <span class="block text-[11px] font-bold text-slate-600 dark:text-slate-300">
        {{ __('Số Dư') }}: <span class="text-red-600">{{ Helper::formatCurrency(Auth::user()->balance ?? 0) }}</span>
      </span>
    </div>
  </a>
  <a href="{{ route('account.profile.index') }}"
    class="footer-bg relative z-[-1] -mt-[40px] flex h-[65px] w-[65px] items-center justify-center rounded-full bg-white bg-no-repeat backdrop-blur-[40px] backdrop-filter dark:bg-slate-700">
    <div class="hrefp-[0px] custom-dropshadow relative left-[0px] h-[50px] w-[50px] rounded-full">
      <img src="{{ Auth::user()->avatar ?? '/images/avatar/av-1.svg' }}" alt="" class="h-full w-full rounded-full border-2 border-slate-100">
    </div>
  </a>
  @if (Auth::check())
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">
        <span class="relative mb-1 flex cursor-pointer flex-col items-center justify-center rounded-full text-[20px] text-slate-900 dark:text-white">
          <iconify-icon icon="heroicons-outline:logout"></iconify-icon>
        </span>
        <span class="block text-[11px] text-slate-600 dark:text-slate-300">
          {{ __('Đăng Xuất') }}
        </span>
      </button>
    </form>
  @else
    <a href="{{ route('login') }}">
      <div>
        <span class="relative mb-1 flex cursor-pointer flex-col items-center justify-center rounded-full text-[20px] text-slate-900 dark:text-white">
          <iconify-icon icon="icon-park:user"></iconify-icon>
        </span>
        <span class="block text-[11px] font-bold text-slate-600 dark:text-slate-300">
          {{ __('Đăng Nhập') }}
        </span>
      </div>
    </a>
  @endif
</div>
