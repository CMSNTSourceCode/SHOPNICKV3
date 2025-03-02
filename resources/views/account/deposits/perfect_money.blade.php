@section('title', __t($pageTitle))
<x-app-layout>
  <section>
    <div class="grid grid-cols-2 gap-3">
      <div class="card">
        <div class="card-body flex flex-col p-6">
          <header class="-mx-6 mb-5 flex items-center border-b border-slate-100 px-6 pb-5 dark:border-slate-700">
            <div class="flex-1">
              <div class="card-title text-slate-900 dark:text-white">{{ __t('Nạp Tiền Bằng Perfect Money') }}</div>
            </div>
          </header>
          <div class="card-text h-full space-y-4">
            <div class="flex justify-center mt-5 mb-3">
              <img src="/images/svg/text.svg" style="width: 200px">
            </div>
            <div>
              <form action="{{ $params['API_URL'] }}" method="POST" id="form">
                <input type="hidden" name="SUGGESTED_MEMO" value="<?= $params['SUGGESTED_MEMO'] ?>">
                <input type="hidden" name="PAYMENT_ID" value="<?= $params['PAYMENT_ID'] ?>" />
                <input type="hidden" name="PAYEE_ACCOUNT" value="<?= $params['PAYEE_ACCOUNT'] ?>" />
                <input type="hidden" name="PAYMENT_UNITS" value="<?= $params['PAYMENT_UNITS'] ?>" />
                <input type="hidden" name="PAYEE_NAME" value="<?= $params['PAYEE_NAME'] ?>" />
                <input type="hidden" name="PAYMENT_URL" value="<?= $params['PAYMENT_URL'] ?>" />
                <input type="hidden" name="PAYMENT_URL_METHOD" value="LINK" />
                <input type="hidden" name="NOPAYMENT_URL" value="<?= $params['NOPAYMENT_URL'] ?>" />
                <input type="hidden" name="NOPAYMENT_URL_METHOD" value="LINK" />
                <input type="hidden" name="STATUS_URL" value="<?= $params['STATUS_URL'] ?>" />
                <div class="mb-3">
                  <label for="PAYMENT_AMOUNT" class="form-label" data-key="dp-nhap-so-tien">{{ __t('Nhập Số Tiền: (USD)') }}</label>
                  <input type="number" class="form-control" id="PAYMENT_AMOUNT" name="PAYMENT_AMOUNT" value="{{ old('PAYMENT_AMOUNT', 1) }}" required>
                </div>
                <div class="mb-3 text-center">
                  <button class="btn btn-primary" type="submit"><i class="fas fa-share"></i> <span data-key="dp-thuc-hien-ngay">{{ __t('Thực Hiện Ngay') }}</span></button>
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
            {!! Helper::getNotice('page_deposit_pmoney') !!}
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
    </div>
  </section>
  @push('scripts')
    <script>
      $(document).ready(() => {
        $("#form").submit(function() {
          $(this).find(":submit").attr('disabled', 'disabled');
        });
      })
    </script>
  @endpush
</x-app-layout>
