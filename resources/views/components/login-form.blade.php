<form method="POST" action="{{ route('login') }}" class="space-y-4">
  @csrf
  {{-- Email --}}
  <div class="fromGroup">
    <label for="username" class="form-label block capitalize">{{ __('Tài Khoản') }}</label>
    <div class="relative">
      <input type="text" name="username" id="username" class="form-control @error('username') !border !border-red-500 @enderror py-2" placeholder="{{ __('Nhập Tên Tài Khoản') }}" autofocus value="{{ old('username') }}">
      <x-input-error :messages="$errors->get('username')" class="mt-2" />
    </div>
  </div>

  {{-- Password --}}
  <div class="fromGroup">
    <label for="password" class="form-label block capitalize">{{ __('Mật Khẩu') }}</label>
    <div class="relative">
      <input type="password" name="password" class="form-control @error('password') !border !border-red-500 @enderror py-2" placeholder="{{ __('Nhập Mật Khẩu Của Bạn') }}" id="password" autocomplete="current-password">
      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
  </div>

  {{-- Remember Me checkbox --}}
  <div class="flex justify-between">
    <div class="checkbox-area">
      <label class="inline-flex cursor-pointer items-center" for="remember_me">
        <input type="checkbox" class="hidden" name="remember" id="remember_me">
        <span class="relative inline-flex h-4 w-4 flex-none rounded border border-slate-100 bg-slate-100 transition-all duration-150 ltr:mr-3 rtl:ml-3 dark:border-slate-800 dark:bg-slate-900">
          <img src="images/icon/ck-white.svg" alt="" class="m-auto block h-[10px] w-[10px] opacity-0"></span>
        <span class="text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('Ghi Nhớ Tài Khoản') }}</span>
      </label>
    </div>
    <a href="{{ route('password.request') }}" class="text-sm font-medium leading-6 text-slate-800 dark:text-slate-400">
      {{ __('Bạn Quên Mật Khẩu?') }}
    </a>
  </div>

  <button type="submit" class="btn btn-dark block w-full text-center">
    {{ __('Đăng Nhập') }}
  </button>
</form>
