@props(['categories', 'type' => 'account', 'bconfig' => null])

@foreach ($categories as $category)
  <div class="space-y-6">
    <div class="text-center">
      <h1 class="text-xl md:text-4xl mb-1">{{ $category->name }}</h1>
      <div class="h-[3px] bg-primary w-[170px] mx-auto"></div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      @foreach ($category->groups()->where('status', true)->orderBy('priority', 'desc')->get() as $group)
        @php
          $redirectUrl = '/tai-khoan/' . $group->slug;

          if ($type === 'item') {
              $redirectUrl = '/vat-pham/' . $group->slug;
          } elseif ($type === 'boosting') {
              $redirectUrl = '/cay-thue/' . $group->slug;
          } elseif ($type === 'account_v2') {
              $redirectUrl = '/tai-khoan-v2/' . $group->slug;
          }
        @endphp
        <div class="rounded-lg bg-white dark:bg-black-500 border border-primary max-h-[270px] lg:max-h-[300px]">
          <div class="card-body">
            <a href="{{ $redirectUrl }}">
              <img src="{{ asset('/images/svg/spinner.svg') }}" data-src="{{ $group->image }}" class="lazyload w-full h-[110px] md:h-[140px] lg:h-[180px] rounded-t-lg object-fill" alt="{{ $group->name }}" />
            </a>
            <div class="p-3 cursor-pointer">
              <div class="flex flex-col items-center">
                <div class="text-center">
                  <h2 class="text-sm font-bold">{{ $group->name }}</h2>
                  @if ($type === 'account')
                    @if ($group->in_stock > 0)
                      <h4 class="text-[12px] md:text-[15px] font-bold">
                        @if ($bconfig['product_info_type'] ?? false)
                          {{ __('Đã Bán') }} <span class="text-primary-500">{{ $group->sold_count }}</span> <span class="hidden md:inline-block">{{ __('Nick') }}</span>
                          <span>|</span>
                        @endif
                        {{ __('Còn') }} <span class="text-red-500">{{ $group->in_stock }}</span> <span class="hidden md:inline-block">{{ __('Nick') }}</span>
                      </h4>
                    @elseif($group->total_item !== 0)
                      <h4 class="text-[12px] md:text-[15px] font-bold">{{ __('Bán Hết') }} <span class="text-red-500">{{ $group->sold_count }}</span> Nick</h4>
                    @else
                      <h4 class="text-[12px] md:text-[15px] font-bold">{{ __('Trạng Thái') }}: <span class="text-danger-500">{{ __('Hết Hàng') }}</span></h4>
                    @endif
                  @elseif($type === 'account_v2')
                    <h4 class="text-[12px] md:text-[15px] font-bold">{{ __('Có') }} <span class="text-red-500">{{ $group->items()->count() }}</span> Nhóm</h4>
                  @else
                    <h4 class="text-[12px] md:text-[15px] font-bold">{{ __('Trạng Thái') }}: <span class="text-green-500">{{ __('Sẵn Sàng') }}</span></h4>
                  @endif
                </div>
                <div class="flex justify-center">
                  <a href="{{ $redirectUrl }}">
                    <img src="{{ $bconfig['buy_button_img'] ?? asset('_assets/images/stores/view-all.gif') }}" class="w-full" alt="{{ __('Xem Tất Cả') }}"></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endforeach
