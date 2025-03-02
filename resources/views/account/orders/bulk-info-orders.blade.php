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
                <input type="text" class="form-control !pr-12" value="{{ $account->name ?? ($account->parent?->name ?? '-') }}" disabled>
              </div>
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Thanh Toán') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ Helper::formatCurrency($account->payment ?? 0) }}" disabled>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div class="input-area">
                <label for="username" class="form-label">{{ __t('Ngày Mua') }}</label>
                <input type="text" class="form-control !pr-12" value="{{ $account->created_at }}" disabled>
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
            <div class="input-area">
              <label for="account_list" class="form-label">{{ __t('Danh Sách Tài Khoản') }}</label>
              <div class="relative">
                @php
                  $account_list = '';

                  foreach ($account->orders as $order) {
                      $account_list .= $order->username . '|' . $order->password;
                      if ($order->extra_data) {
                          $account_list .= '|' . $order->extra_data;
                      }
                      $account_list .= "\n";
                  }

                @endphp
                <textarea name="account_list" id="account_list" class="form-control !pr-12" rows="5" disabled>{{ $account_list }}</textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="text-center">
      <a href="{{ route('account.orders.bulk-orders') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>{{ __t(' Quay Lại') }}</a>
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
