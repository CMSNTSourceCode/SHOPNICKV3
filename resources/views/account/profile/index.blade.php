@section('title', __t($pageTitle))
<x-app-layout>
  <section class="space-y-6">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div class="card">
        <div class="card-body flex flex-col p-6">
          <header class="-mx-6 mb-5 flex items-center border-b border-slate-100 px-6 pb-5 dark:border-slate-700">
            <div class="flex-1">
              <div class="card-title text-slate-900 dark:text-white">{{ __t('Thông Tin Tài Khoản') }}</div>
            </div>
          </header>
          <div class="card-text h-full space-y-4">
            <form class="space-y-3">
              <div class="grid grid-cols-2 gap-3">
                <div class="input-area">
                  <label for="username" class="form-label">{{ __t('Tên Đăng Nhập') }}</label>
                  <input id="username" name="username" type="text" class="form-control" value="{{ $user->username }}" disabled>
                </div>
                <div class="input-area">
                  <label for="email" class="form-label">{{ __t('Địa chỉ e-mail') }}</label>
                  <input id="email" name="email" type="text" class="form-control" value="{{ $user->email }}" disabled>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div class="input-area">
                  <label for="created_at" class="form-label">{{ __t('Ngày Đăng Ký') }}</label>
                  <input id="created_at" name="created_at" type="text" class="form-control" value="{{ $user->created_at }}" disabled>
                </div>
                <div class="input-area">
                  <label for="updated_at" class="form-label">{{ __t('Ngày Cập Nhật') }}</label>
                  <input id="updated_at" name="updated_at" type="text" class="form-control" value="{{ $user->updated_at }}" disabled>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div class="input-area">
                  <label for="balance" class="form-label">{{ __t('Số Tiền Hiện Có') }}</label>
                  <input id="balance" name="balance" type="text" class="form-control" value="{{ Helper::formatCurrency($user->balance) }}" disabled>
                </div>
                <div class="input-area">
                  <label for="total_deposit" class="form-label">{{ __t('Tổng Tiền Đã Nạp') }}</label>
                  <input id="total_deposit" name="total_deposit" type="text" class="form-control" value="{{ Helper::formatCurrency($user->total_deposit) }}" disabled>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
      <div class="card">
        <header class="card-header noborder">
          <h4 class="card-title">{{ __t('Thay đổi mật khẩu') }}</h4>
        </header>
        <div class="card-body px-6 pb-6">
          <form action="{{ route('accounts.profile.update-password') }}" method="POST" class="space-y-3">
            @csrf
            <div class="input-area">
              <label for="old_password" class="form-label">{{ __t('Mật Khẩu Cũ') }}</label>
              <input type="password" class="form-control @error('old_password') !border !border-red-500 @enderror py-2" id="old_password" name="old_password" placeholder="{{ __t('Nhập mật khẩu cũ') }}" required>
              <x-input-error :messages="$errors->get('old_password')" class="mt-2" />
            </div>
            <div class="input-area">
              <label for="new_password" class="form-label">{{ __t('Mật Khẩu Mới') }}</label>
              <input type="password" class="form-control @error('new_password') !border !border-red-500 @enderror py-2" id="new_password" name="new_password" placeholder="{{ __t('Nhập mật khẩu mới') }}" required>
              <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
            </div>
            <div class="input-area">
              <label for="confirm_password" class="form-label">{{ __t('Xác Nhận Mật Khẩu') }}</label>
              <input type="password" class="form-control @error('confirm_password') !border !border-red-500 @enderror py-2" id="confirm_password" name="confirm_password" placeholder="{{ __t('Nhập lại mật khẩu mới') }}"
                required>
              <x-input-error :messages="$errors->get('confirm_password')" class="mt-2" />
            </div>
            <div class="input-area">
              <button type="submit" class="btn btn-sm btn-primary w-full">{{ __t('Đổi Mật Khẩu') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="app" class="card">
      <header class="card-header noborder">
        <h4 class="card-title">{{ __t('Nhật Ký Hoạt Động') }}</h4>
      </header>
      <div class="card-body px-6 pb-6">
        <account-history />
      </div>
    </div>
  </section>

  @push('scripts')
    @vite(['resources/js/modules/account/profile/index.js'])
  @endpush
</x-app-layout>
