@section('title', __t($pageTitle))
<x-app-layout>
  <section class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

      @php $contact_info = Helper::getConfig('contact_info'); @endphp
      <div class="col-span-2">
        <div class="alert alert-danger">
          {{ __t('- Liên hệ hỗ trợ qua :') }}
          <a href="{{ $contact_info['facebook'] ?? '#!' }}" target="_blank"><i class="fa-brands fa-square-facebook me-2"></i> Facebook</a> \
          <a href="tel:{{ $contact_info['phone_no'] ?? '+84123456789' }}" target="blank"><i class="fa-solid fa-phone me-2"></i> {{ $contact_info['phone_no'] ?? '+84123456789' }}</a>
          {{-- - Không đổi thông tin tài khoản, nếu đổi sẽ không được hỗ trợ. --}}
        </div>
      </div>

      <div class="card">
        <header class=" card-header noborder">
          <h4 class="card-title">{{ __t('Thông Tin Giao Dịch') }} <span class="text-danger-500">{{ $account->buyer_code }}</span></h4>
        </header>
        <div class="card-body px-6 pb-6">
          <form class="space-y-3">

            <div class="grid grid-cols-2 gap-3">
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Sản Phẩm') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ $account->group?->name ?? ($account->parent?->group?->name ?? '-') }}" disabled>
              </div>
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Thanh Toán') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ Helper::formatCurrency($account->buyer_paym ?? 0) }}" disabled>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Ngày Mua') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ $account->buyer_date }}" disabled>
              </div>
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Ngày Cập Nhật') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ $account->updated_at }}" disabled>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <header class=" card-header noborder">
          <h4 class="card-title">{{ __t('Thông Tin Tài Khoản') }} <span class="text-green-500">{{ $account->code }}</span></h4>
        </header>
        <div class="card-body px-6 pb-6">
          <div class="mb-2">
            {!! Helper::getNotice('page_account_info') !!}
          </div>
          <form class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Tài Khoản') }}</label>
                <div class="relative">
                  <input type="text" class="form-control !pr-12" value="{{ $account->username }}" disabled>
                  <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center copy"
                    data-clipboard-text="{{ $account->username }}" type="button">
                    <iconify-icon icon="heroicons-solid:save"></iconify-icon>
                  </button>
                </div>
              </div>
              <div class="input-area">
                <label for="password" class="form-label">{{ __t('Mật Khẩu') }}</label>
                <div class="relative">
                  <input type="text" class="form-control !pr-12" value="{{ $account->password }}" disabled>
                  <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center copy"
                    data-clipboard-text="{{ $account->password }}" type="button">
                    <iconify-icon icon="heroicons-solid:save"></iconify-icon>
                  </button>
                </div>
              </div>
            </div>
            <div class="input-area">
              <label for="password" class="form-label">Cookie / 2FA</label>
              <div class="relative">
                <input type="text" class="form-control !pr-12" value="{{ $account->extra_data }}" disabled>
                <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center copy"
                  data-clipboard-text="{{ $account->extra_data }}" type="button">
                  <iconify-icon icon="heroicons-solid:save"></iconify-icon>
                </button>
              </div>
            </div>
            @if (is_valid_2fa_secret($account->extra_data))
              <div class="input-area">
                <div class="alert alert-danger">
                  Mã Đăng Nhập <i class="fas fa-spin fa-spinner"></i> : <span id="code_2fa" style="background: black; color: yellow; padding: 2px">{{ generate_code_2fa($account->extra_data) }}</span> (Mã mới sau mỗi
                  30s)
                  <a href="javascript:void(0)" class="copy" data-clipboard-target="#code_2fa"><i class="fas fa-copy"></i></a>
                </div>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>

    <div class="text-center">
      <a href="{{ route('account.orders.accounts-v2') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>{{ __t(' Quay Lại') }}</a>
    </div>
  </section>

  @push('scripts')
    <script>
      $(document).ready(function() {
        const interval = setInterval(() => {
          axios.post('/api/tools/get-current-otp', {
            secret: '{{ $account->extra_data }}'
          }).then((result) => {
            $('#code_2fa').text(result.data.data)
          })
        }, 1000 * 5);
      })
    </script>
  @endpush
</x-app-layout>
