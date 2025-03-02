<x-app-layout>
  {{-- ẢNH BÌA & TOP NẠP THÁNG --}}
  <section>
    @php $bconfig = Helper::getConfig('theme_custom'); @endphp
    @if (theme_config('show_banner', true))
      <div class="grid gap-3 sm:grid-cols-1 lg:grid-cols-3">
        <div class="lg:col-span-2">
          <div class="cursor-pointer shadow-lg-lg">
            @isset($bconfig['youtube'])
              <iframe width="100%" height="350" class="rounded-lg" src="https://www.youtube.com/embed/{{ $bconfig['youtube'] }}" title="Video Intro" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            @else
              @isset($bconfig['banner'])
                <img src="{{ $bconfig['banner'] }}" class="w-full rounded-lg sm:h-auto sm:object-contain lg:h-[360px] lg:object-fill" alt="" />
              @else
                <img src="{{ asset('/images/svg/spinner.svg') }}" class="w-full rounded-lg sm:h-auto sm:object-contain lg:h-[350px] lg:object-fill" alt="">
              @endisset
            @endisset
          </div>
        </div>
        <div>
          <div class="rounded-lg">
            <div class="card shadow-lg">
              <ul class="nav nav-tabs mb-4 flex list-none flex-col flex-wrap justify-center border-b-0 rounded-t-lg pt-2 pl-0 md:flex-row " style="background-color: var(--primary-color)" id="tabs-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a href="#tabs-home-withIcon"
                    class="flex justify-center !text-[#fff] text-[20px] nav-link active my-2 w-full items-center px-4 pb-2 font-semibold capitalize leading-tight hover:border-transparent focus:border-transparent dark:text-slate-300"
                    id="tabs-home-withIcon-tab" data-bs-toggle="pill" data-bs-target="#tabs-home-withIcon" role="tab" aria-controls="tabs-home-withIcon" aria-selected="true">
                    <iconify-icon class="mr-1" icon="heroicons-outline:share"></iconify-icon>
                    {{ __('TOP Nạp Tháng') }} {{ date('m') }}</a>
                </li>
              </ul>
              <div class="card-body flex flex-col p-6">

                <div class="card-text h-full">
                  <div class="tab-content" id="tabs-tabContent">
                    <div class="tab-pane fade show active" id="tabs-home-withIcon" role="tabpanel" aria-labelledby="tabs-home-withIcon-tab">
                      <ul class="space-y-2">
                        @if (!count($top10UserDeposit))
                          <div class="text-center mb-[40px]">
                            <h6>Chưa có dữ liệu</h6>
                          </div>
                        @endif
                        @foreach ($top10UserDeposit as $deposit)
                          <li class="transition-all hover:scale-105">
                            <button class="btn btn-outline-primary btn-sm w-full">
                              <div class="flex justify-between font-bold">
                                <span>{{ $loop->iteration }}. {{ Helper::hideUsername($deposit->username) }}</span>
                                <span class="text-danger-600">{{ $deposit->prefix }}{{ number_format($deposit->total) }} VNĐ</span>
                              </div>
                            </button>
                          </li>
                        @endforeach
                        <li class="transition-all hover:scale-105">
                          <button onclick="location.href='{{ route('account.deposits.index') }}'" class="btn btn-primary btn-sm w-full">
                            <div class="text-center font-bold">
                              👉 NẠP TIỀN NGAY 👈
                            </div>
                          </button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    @if ($homeNotice = Helper::getNotice('home_dashboard'))
      <div class="bg-white border border-primary p-3 mt-3 rounded-lg">
        {!! $homeNotice !!}
      </div>
    @endif
    @if (theme_config('show_thongbao', true))
      <div class="mt-4 flex items-center rounded-lg bg-white px-3 py-3 shadow-lg">
        <i class="fa-regular fa-bell text-primary me-2"></i>
        <marquee>
          <span class="text-danger-600 font-bold">[{{ strtoupper(Helper::getDomain()) }}]</span> {{ Helper::getConfig('shop_info')['dashboard_text_1'] ?? '-' }}
        </marquee>
      </div>
    @endif
    @if (theme_config('show_lsmua', true))
      <div class="mt-4 flex items-center rounded-lg bg-white px-3 py-3 shadow-lg">
        <i class="fas fa-shopping-cart text-green-600 me-2"></i>
        <marquee>
          {!! $listAccountBuy !!}
        </marquee>
      </div>
    @endif
  </section>

  @if (theme_config('minigame_pos') === 'top')
    <section class="section-product space-y-3">
      <div class="text-center mb-5">
        <h1 class="text-[20px] md:text-[30px] mb-1"> <i class="fas fa-shield text-primary"></i> Trò Chơi - Mini Game <i class="fas fa-shield text-primary"></i></h1>
        <div class="h-[3px] bg-primary w-[170px] mx-auto"></div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach (\App\Models\SpinQuest::where('status', true)->get() as $spinQuest)
          <div class="rounded-lg bg-white dark:bg-black-500 border border-primary max-h-[270px] lg:max-h-[300px]">
            <div class="card-body">
              <a href="{{ route('games.spin-quest', ['id' => $spinQuest->id]) }}">
                <img src="{{ asset('/images/svg/spinner.svg') }}" data-src="{{ $spinQuest->cover }}" class="lazyload w-full h-[110px] md:h-[140px] lg:h-[180px] rounded-t-lg object-fill" alt="{{ $spinQuest->name }}" />
              </a>
              <div class="p-3 cursor-pointer">
                <div class="flex justify-between mb-4">
                  <div class="font-bold text-lg">
                    {{ $spinQuest->name }}
                  </div>
                  <div class="font-bold text-lg text-primary">
                    {{ Helper::formatCurrency($spinQuest->price) }}
                  </div>
                </div>
                <div class="flex flex-col items-center">

                  <div class="flex justify-center">
                    <a href="{{ route('games.spin-quest', ['id' => $spinQuest->id]) }}" class="btn btn-sm btn-primary"> <i class="fas fa-share"></i> Chơi Ngay <i class="fas fa-share"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif

  {{-- DANH MỤC SẢN PHẨM --}}
  <section class="section-product space-y-3">
    <x-category-list :categories="$accountV2Categories" :bconfig="$bconfig" type="account_v2" />
  </section>

  {{-- DANH MỤC SẢN PHẨM --}}
  <section class="section-product space-y-3">
    <x-category-list :categories="$accountCategories" :bconfig="$bconfig" />
  </section>

  {{-- DANH MỤC VẬT PHẨM --}}
  <section class="section-product space-y-3">
    <x-category-list :categories="$itemCategories" :bconfig="$bconfig" type="item" />
  </section>

  {{-- DANH MỤC CÀY THUÊ --}}
  <section class="section-product space-y-3">
    <x-category-list :categories="$boostingCategories" :bconfig="$bconfig" type="boosting" />
  </section>

  @if (theme_config('minigame_pos') === 'bottom')
    <section class="section-product space-y-3">
      <div class="text-center mb-5">
        <h1 class="text-[20px] md:text-[30px] mb-1"> <i class="fas fa-shield text-primary"></i> Trò Chơi - Mini Game <i class="fas fa-shield text-primary"></i></h1>
        <div class="h-[3px] bg-primary w-[170px] mx-auto"></div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach (\App\Models\SpinQuest::where('status', true)->get() as $spinQuest)
          <div class="rounded-lg bg-white dark:bg-black-500 border border-primary max-h-[270px] lg:max-h-[300px]">
            <div class="card-body">
              <a href="{{ route('games.spin-quest', ['id' => $spinQuest->id]) }}">
                <img src="{{ asset('/images/svg/spinner.svg') }}" data-src="{{ $spinQuest->cover }}" class="lazyload w-full h-[110px] md:h-[140px] lg:h-[180px] rounded-t-lg object-fill"
                  alt="{{ $spinQuest->name }}" />
              </a>
              <div class="p-3 cursor-pointer">
                <div class="flex justify-between mb-4">
                  <div class="font-bold text-lg">
                    {{ $spinQuest->name }}
                  </div>
                  <div class="font-bold text-lg text-primary">
                    {{ Helper::formatCurrency($spinQuest->price) }}
                  </div>
                </div>
                <div class="flex flex-col items-center">

                  <div class="flex justify-center">
                    <a href="{{ route('games.spin-quest', ['id' => $spinQuest->id]) }}" class="btn btn-sm btn-primary"> <i class="fas fa-share"></i> Chơi Ngay <i class="fas fa-share"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif

  @if (Helper::getNotice('modal_dashboard'))
    @push('scripts')
      <script type="module">
        $(document).ready(() => {
          Swal.fire({
            title: '<i class="fas fa-bell text-primary me-2"></i> Thông Báo Mới <i class="fas fa-bell text-primary ml-2"></i>',
            html: `{!! Helper::getNotice('modal_dashboard') !!}`
          })
        })
      </script>
    @endpush
  @endif
</x-app-layout>
