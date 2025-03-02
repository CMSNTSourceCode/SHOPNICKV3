@section('title', __t($pageTitle))
<x-app-layout>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div class="card">
      <div class="card-body flex flex-col p-6">
        <header class="-mx-6 mb-5 flex items-center border-b border-slate-100 px-6 pb-5 dark:border-slate-700">
          <div class="flex-1">
            <div class="card-title text-slate-900 dark:text-white">{{ __t('Nạp Tiền Bằng Paypal') }}</div>
          </div>
        </header>
        <div class="card-text h-full space-y-4">
          <div class="flex justify-center">
            <img src="/images/svg/paypal.svg" style="width: 200px">
          </div>
          <div>
            <form action="/api/accounts/invoices" method="POST" id="form">
              <input type="hidden" name="channel" id="channel" value="fpayment">
              <div class="mb-3">
                <label for="amount" class="form-label" data-key="dp-nhap-so-tien">{{ __t('Nhập số tiền') }}: (USD)</label>
                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', 1) }}" required>
              </div>
              <div class="mb-3 text-center">
                <script src="https://www.paypal.com/sdk/js?client-id={{ $config['client_id'] ?? '' }}&currency=USD"></script>

                <div id="paypal-button-container"></div>
                {{-- <button class="btn btn-primary w-100" type="submit"><i class="fas fa-share"></i> <span data-key="dp-thuc-hien-ngay">{{ __t('Thanh Toán Ngay') }}</span></button> --}}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body flex flex-col p-6">
        <header class="-mx-6 mb-5 flex items-center border-b border-slate-100 px-6 pb-5 dark:border-slate-700">
          <div class="flex-1">
            <div class="card-title text-slate-900 dark:text-white">{{ __t('Lưu Ý Khi Nạp') }}</div>
          </div>
        </header>
        <div class="card-text h-full space-y-4">
          {!! Helper::getNotice('page_deposit_paypal') !!}
        </div>
      </div>
    </div>
    <div class="card col-span-2">
      <header class=" card-header noborder">
        <h4 class="card-title">{{ __t('Danh Sách Hoá Đơn') }}</h4>
      </header>
      <div class="card-body px-6 pb-6">
        <div class="overflow-x-auto -mx-6 dashcode-data-table">
          <span class=" col-span-8  hidden"></span>
          <span class="  col-span-4 hidden"></span>
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
              <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 whitespace-nowrap" id="data-table">
                <thead class=" border-t border-slate-100 dark:border-slate-800">
                  <tr>
                    <th scope="col" class=" table-th ">
                      Id
                    </th>

                    <th scope="col" class=" table-th ">
                      {{ __t('Thao tác') }}
                    </th>

                    <th scope="col" class=" table-th ">
                      {{ __t('Mã Giao Dịch') }}
                    </th>

                    <th scope="col" class=" table-th ">
                      {{ __t('Số Tiền') }}
                    </th>

                    <th scope="col" class=" table-th ">
                      {{ __t('Ghi Chú') }}
                    </th>

                    <th scope="col" class=" table-th ">
                      {{ __t('Trạng Thái') }}
                    </th>

                    <th scope="col" class=" table-th ">
                      {{ __t('Thời Gian') }}
                    </th>

                    <th scope="col" class=" table-th ">
                      {{ __t('Cập Nhật') }}
                    </th>

                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                  @foreach ($invoices as $item)
                    <tr>
                      <td class="table-td">{{ $item->id }}</td>
                      <td class="table-td">
                        @if ($item->status === 'processing')
                          <a class="text-primary" href="{{ $item->payment_details['url_payment'] ?? '#!' }}" target="_blank"><i class="fas fa-share"></i> <span>{{ __t('Thanh Toán') }}</span></a>
                        @endif
                      </td>
                      <td class="table-td">{{ $item->code }}</td>
                      <td class="table-td">{{ Helper::formatCurrency($item->amount) }}</td>
                      <td class="table-td">
                        <span class="text-wrap">{{ $item->description }}</span>
                      </td>
                      <td class="table-td">{!! Helper::formatStatus($item->status) !!}</td>
                      <td class="table-td">{{ $item->created_at }}</td>
                      <td class="table-td">{{ $item->updated_at }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>
        <div class="p-4">
          {{ $invoices->links() }}
        </div>
      </div>
    </div>
    @push('scripts')
      <script>
        (function($) {
          paypal.Buttons({

            // Sets up the transaction when a payment button is clicked
            createOrder: function(data, actions) {
              return actions.order.create({
                purchase_units: [{
                  amount: {
                    value: $('#amount')
                      .val() // Can reference variables or functions. Example: `value: document.getElementById('...').value`
                  }
                }]
              });
            },

            // Finalize the transaction after payer approval
            onApprove: function(data, actions) {
              return actions.order.capture().then(function(orderData) {
                axios.post('/api/deposit/paypal-confirm', orderData).then(({
                  data: result
                }) => {

                  Swal.fire('Thành công', result.message, 'success').then(() => {
                    window.location.reload();
                  })
                }).catch(error => {
                  Swal.fire('Thất bại', $catchMessage(error), 'error')
                })
              });
            }
          }).render('#paypal-button-container');
        })(jQuery)
      </script>
    @endpush
</x-app-layout>
