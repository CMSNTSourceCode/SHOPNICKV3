@section('title', __t($pageTitle))
<x-app-layout>
  <section class="space-y-6">
    <div class="mx-auto grid max-w-5xl grid-cols-1 gap-6 md:grid-cols-3">
      <div class="col-span-1 md:col-span-3 card">
        <div class="card-body flex flex-col p-6">
          <div class="card-text h-full space-y-4">
            {!! Helper::getNotice('page_deposit') !!}
          </div>
        </div>
      </div>
      @if ($cardOn)
        <div>
          <div class="mb-2 rounded-b-none border border-none bg-transparent p-3 hidden md:block">
            <img src="/images/svg/dollar.svg" alt="Nap The Cao" class="mx-auto w-[90px] cursor-pointer object-cover">
          </div>
          <div class="rounded-lg bg-[#025464] p-4 pb-5">
            <form class="space-y-3" id="form-sendcard">
              <div class="input-area">
                <label for="telco" class="form-label !text-white">{{ __t('Loại thẻ') }}</label>
                <select class="form-control" id="telco" name="telco" required>
                  <option value="">{{ __t('Chọn loại thẻ') }}</option>
                  <option value="VIETTEL">Viettel - {{ __t('Phí') }} {{ $fees['VIETTEL'] ?? 20 }}%</option>
                  <option value="VINAPHONE">Vinaphone - {{ __t('Phí') }} {{ $fees['VINAPHONE'] ?? 20 }}%</option>
                  <option value="MOBIFONE">Mobifone - {{ __t('Phí') }} {{ $fees['MOBIFONE'] ?? 20 }}%</option>
                  <option value="ZING">Zing Card - {{ __t('Phí') }} {{ $fees['ZING'] ?? 20 }}%</option>
                  <option value="GARENA">Garena - {{ __t('Phí') }} {{ $fees['GARENA'] ?? 20 }}%</option>
                  <option value="VNMOBI">Vietnamobile - {{ __t('Phí') }} {{ $fees['VNMOBI'] ?? 20 }}%</option>
                </select>
              </div>
              <div class="input-area">
                <label for="amount" class="form-label !text-white">{{ __t('Mệnh giá') }}</label>
                <select class="form-control" id="amount" name="amount" required>
                  <option value="">{{ __t('Chọn mệnh giá') }}</option>
                  <option value="10000">10.000 đ</option>
                  <option value="20000">20.000 đ</option>
                  <option value="30000">30.000 đ</option>
                  <option value="50000">50.000 đ</option>
                  <option value="100000">100.000 đ</option>
                  <option value="200000">200.000 đ</option>
                  <option value="300000">300.000 đ</option>
                  <option value="500000">500.000 đ</option>
                  <option value="1000000">1.000.000 đ</option>
                </select>
              </div>
              <div class="input-area">
                <label for="serial" class="form-label !text-white">{{ __t('Số serial') }}</label>
                <input type="text" class="form-control" id="serial" name="serial" placeholder="{{ __t('Nhập số serial') }}" required>
              </div>
              <div class="input-area">
                <label for="code" class="form-label !text-white">Mã thẻ</label>
                <input type="text" class="form-control" name="code" id="code" placeholder="{{ __t('Nhập mã thẻ') }}" required>
              </div>
              <div class="text-center text-[16px] text-lime-500">
                <i>{{ __t('Nếu Chọn Sai Mệnh Giá Sẽ Bị Mất Thẻ!') }}</i>
              </div>
              <div class="input-area">
                <button class="btn btn-sm btn-success w-full" type="submit">{{ __t('Gửi để nhận') }} <span class="real_amount">0đ</span></button>
              </div>
            </form>
          </div>
        </div>
      @endif
      @foreach ($banks as $bank)
        <div>
          <div class="rounded-b-none border border-none bg-transparent p-4">
            <img src="{{ asset($bank->image) }}" alt="{{ $bank->name }}" class="mx-auto w-[90px] cursor-pointer object-cover">
          </div>
          <div class="space-y-2 rounded-lg bg-[#002B5B] p-4 text-[18px] font-bold text-white">
            <div class="flex flex-wrap justify-between">
              <span>{{ ucfirst($bank->name) }}:</span>
              <span class="copy cursor-pointer" data-clipboard-text="{{ $bank->number }}">{{ $bank->number }}</span>
            </div>
            <div class="flex flex-wrap justify-between">
              <span>{{ __t('Chủ TK') }}</span>
              <span>{{ $bank->owner }}</span>
            </div>
            <div class="flex flex-wrap justify-between">
              <span>{{ __t('Nội Dung') }}</span>
              <span class="copy cursor-pointer" data-clipboard-text="{{ $deposit_prefix }}">{{ $deposit_prefix }}</span>
            </div>
            <div class="text-center">
              {{ __t('Nhập đúng nội dung tiền tự động cộng trong vài phút') }}
            </div>
            <div>
              @if (str_contains(strtolower($bank->name), 'momo'))
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=2|99|{{ $bank->number }}|||0|0|0|{{ $deposit_prefix }}|transfer_myqr" class="mx-auto w-full rounded-lg object-fill">
              @else
                <img src="https://api.vietqr.io/{{ strtolower($bank->name) }}/{{ $bank->number }}/0/{{ $deposit_prefix }}/qronly2.jpg?accountName={{ $bank->owner }}&bankName={{ $bank->name }}"
                  class="mx-auto w-full rounded-lg object-fill">
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  @push('scripts')
    <script type="module">
      const CARD_FEES = @json($fees);

      $("#form-sendcard #amount").change((e) => {
        sumAmount();
      })

      $("#form-sendcard #telco").change((e) => {
        sumAmount();
      })

      const sumAmount = () => {
        const telco = $("#form-sendcard #telco").val();
        const amount = $("#form-sendcard #amount").val();

        if (amount) {
          const real_amount = amount - (amount * CARD_FEES[telco] / 100);

          console.log(real_amount);
          $("#form-sendcard .real_amount").text($formatCurrency(real_amount));
        } else {
          $("#form-sendcard .real_amount").text($formatCurrency(0));
        }
      }

      $("#form-sendcard").submit(async (e) => {
        e.preventDefault();

        const action = e.target.action,
          method = e.target.method,
          payload = $formDataToPayload(new FormData(e.target));

        $showLoading();

        try {
          const {
            data: result
          } = await axios.post('/api/accounts/send-card', payload)

          Swal.fire('Thành công', result.message, 'success').then(() => {
            e.target.reset();
          })
        } catch (error) {
          Swal.fire('Thất bại', error.response.data.message, 'error')
        }
      })
    </script>
  @endpush
</x-app-layout>
