@section('title', __('Đăng nhập tài khoản'))
<x-app-layout>
  <div class="card auth-box flex h-full flex-col justify-center">
    <div class="mb-4 text-center 2xl:mb-10">
      <h4 class="font-medium"> {{ __('Sign In') }}</h4>
      <div class="text-base text-slate-500">
        {{ __('Sign in to system') }}
      </div>
    </div>

    <!-- START::LOGIN FORM -->
    <x-login-form></x-login-form>
    <!-- END::LOGIN FORM -->

    <div class="relative border-b border-b-[#9AA2AF] border-opacity-[16%] pt-6">
      <div class="absolute left-1/2 top-1/2 inline-block min-w-max -translate-x-1/2 transform bg-white px-4 text-sm font-normal text-slate-500 dark:bg-slate-800 dark:text-slate-400">
        {{ __('Or continue with') }}
      </div>
    </div>
    <div class="mx-auto mt-8 w-full max-w-[242px]">
      <x-social-login></x-social-login>
    </div>

    <div class="mx-auto mt-12 text-sm font-normal uppercase text-slate-500 dark:text-slate-400 md:max-w-[345px]">
      {{ __('Bạn Chưa Có Tài Khoản?') }}
      <a href="{{ route('register') }}" class="font-medium text-slate-900 hover:underline dark:text-white">
        {{ __('Đăng Ký Ngay') }}
      </a>
    </div>
  </div>
</x-app-layout>
