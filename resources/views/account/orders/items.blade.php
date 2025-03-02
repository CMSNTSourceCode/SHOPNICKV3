@section('title', __t($pageTitle))
<x-app-layout>
  <section class="mb-3">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
      <div class="relative flex items-center rounded-[6px] bg-cover bg-center bg-no-repeat px-5 py-8" style="background-image: url(/images/all-img/widget-bg-6.png)">
        <div class="flex-1">
          <div class="max-w-[180px]">
            <h4 class="mb-2 text-2xl font-medium text-white">
              <span class="block text-sm">{{ __t('Vật Phẩm Đã Mua,') }}</span>
              <span class="block">{{ $stats['total'] }} <small>{{ __t('vật phẩm') }}</small></span>
            </h4>
          </div>
        </div>
        <div class="flex-none">
          <a href="{{ route('home') }}" class="btn-light btn-sm btn bg-white">{{ __t('MUA THÊM') }}</a>
        </div>
      </div>
      <!--  end Single -->
      <div class="relative flex items-center rounded-[6px] bg-cover bg-center bg-no-repeat px-5 py-8" style="background-image: url(/images/all-img/widget-bg-6.png)">
        <div class="flex-1">
          <div class="max-w-[180px]">
            <h4 class="mb-2 text-2xl font-medium text-white">
              <span class="block text-sm">
                {{ __t('Số Tiền Đã Tiêu,') }}
              </span>
              <span class="block">{{ Helper::formatCurrency($stats['payment']) }}</span>
            </h4>
          </div>
        </div>
        <div class="flex-none">
          <a href="{{ route('home') }}" class="btn-light btn-sm btn bg-white">{{ __t('MUA THÊM') }}</a>
        </div>
      </div>
      <!--  end Single -->
      <div class="relative flex items-center rounded-[6px] bg-cover bg-center bg-no-repeat px-5 py-8" style="background-image: url(/images/all-img/widget-bg-6.png)">
        <div class="flex-1">
          <div class="max-w-[180px]">
            <h4 class="mb-2 text-2xl font-medium text-white">
              <span class="block text-sm">
                {{ __t('Đã Tiêu Trong Tháng,') }}
              </span>
              <span class="block">{{ Helper::formatCurrency($stats['payment_in_month']) }}</span>
            </h4>
          </div>
        </div>
        <div class="flex-none">
          <a href="{{ route('home') }}" class="btn-light btn-sm btn bg-white">{{ __t('MUA THÊM') }}</a>
        </div>
      </div>
    </div>
    <hr class="mb-3 mt-3 h-[10px]" />
  </section>

  @if ($items->count() === 0)
    <div class="space-y-6">
      <div>
        <img src="{{ asset('/images/svg/empty.svg') }}" class="mx-auto h-[100px] w-[150px] object-cover" alt="empty">
      </div>
      <div class="text-center">
        <h1 class="mt-3 text-2xl font-semibold">{{ __t('Bạn chưa mua vật phẩm nào!') }}</h1>
      </div>
      <div class="text-center">
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">{{ __t('Mua Ngay') }}</a>
      </div>
    </div>
  @endif
  <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
    @foreach ($items as $account)
      <div class="grid grid-cols-3 items-center rounded-lg bg-transparent bg-cover text-white cursor-pointer" style="background-image: url(/images/all-img/widget-bg-6.png)"
        onclick="window.location.href='{{ route('account.orders.items', ['code' => $account->code]) }}'">
        <a href="{{ route('account.orders.items', ['code' => $account->code]) }}" class="p-2">
          <img src="{{ asset('/images/svg/spinner.svg') }}" data-src="/images/svg/package.svg" class="lazyload h-[50px] w-full rounded-lg border border-green-300 object-fill md:h-[90px]" alt="{{ $account->name }}" />
        </a>
        <a href="{{ route('account.orders.items', ['code' => $account->code]) }}" class="flex flex-col justify-between whitespace-nowrap p-2 font-bold">
          <div>{{ __t('Tên:') }} <span class="text-[#A8DF8E]">{{ $account->name }}</span></div>
          <div>{{ __t('Mã Đơn:') }} <span class="text-[#FF6969]">{{ $account->code }} / {{ Helper::formatCurrency($account->payment) }}</span></div>
          <div>{{ __t('Tài Khoản:') }} <span class="text-[#40F8FF]">{{ $account->input_user }}</span></div>
          <div>{{ __t('Ngày Mua:') }} <span class="text-[#85E6C5]">{{ $account->created_at }}</span></div>
        </a>
      </div>
    @endforeach
  </div>
  <div class="mt-3 flex justify-center">
    {{ $items->links() }}
  </div>

  @push('scripts')
    <script type="module">
      const onClickToCode = (e) => {
        e.preventDefault();
      }
    </script>
  @endpush
</x-app-layout>
