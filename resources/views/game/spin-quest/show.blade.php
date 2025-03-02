@section('title', $pageTitle)
@section('css')
  <style>
    @media (max-width: 768px) {
      .v-luckywheel .wheel-wrapper {
        width: 300px;
        height: 300px;
        position: relative;
      }

      .v-luckywheel .wheel-pointer {
        width: 90px;
        height: 90px;
        background: url(https://i.imgur.com/DAHLRjT.png);
        background-size: 75%;
        background-repeat: no-repeat;
        background-position: 50%;
        position: absolute;
        left: 50%;
        top: 49.7%;
        transform: translate(-50%, -50%);
        text-align: center;
        line-height: 60px;
        z-index: 10;
      }

      .v-luckywheel .wheel-bg {
        width: 100%;
        height: 100%;
        border-radius: unset;
        overflow: hidden;
        transition: transform 4s ease-in-out;
        background-size: 100% 100% !important;
      }

      .v-luckywheel .wheel-bg.freeze {
        transition: none;
        background: red;
      }

      .v-luckywheel .prize-list {
        width: 100%;
        height: 100%;
        position: relative;
        text-align: center;
      }

      .v-luckywheel .prize-item-wrapper {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 150px;
        height: 150px;
      }

      .v-luckywheel .prize-item {
        width: 100%;
        height: 100%;
        transform-origin: bottom;
      }

      .v-luckywheel .prize-item .prize-name {
        padding: 14px 0;
        font-weight: 700;
      }
    }

    @media (min-width: 769px) {
      .v-luckywheel .wheel-wrapper {
        width: 750px;
        height: 750px;
        position: relative;
      }

      .v-luckywheel .wheel-pointer {
        width: 122px;
        height: 122px;
        background: url(https://cdns.diongame.com/static/image-8a5e9624-8618-4a5f-9d37-20b6db11ddb4.png);
        background-size: 71%;
        background-repeat: no-repeat;
        background-position: 50%;
        position: absolute;
        left: 50%;
        top: 48.7%;
        transform: translate(-50%, -50%);
        z-index: 10;
      }

      .v-luckywheel .wheel-bg {
        width: 100%;
        height: 100%;
        border-radius: unset;
        overflow: hidden;
        transition: transform 4s ease-in-out;
        background-size: 100% 100% !important;
      }

      .v-luckywheel .wheel-bg.freeze {
        transition: none;
        background: red;
      }

      .v-luckywheel .prize-list {
        width: 100%;
        height: 100%;
        position: relative;
        text-align: center;
      }

      .v-luckywheel .prize-item-wrapper {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 250px;
        height: 250px;
      }

      .v-luckywheel .prize-item {
        width: 100%;
        height: 100%;
        transform-origin: bottom;
      }

      .v-luckywheel .prize-item .prize-name {
        padding: 14px 0;
        font-weight: 700;
      }
    }

    .v-luckywheel img {
      border-radius: unset;
      overflow: hidden;
    }
  </style>
@endsection
<x-app-layout meta-seo="ocean">
  <section>
    <div class="mb-5 text-center">
      <h1 class="mb-1 text-[20px] md:text-[30px]"> <i class="fa-solid fa-dharmachakra text-primary"></i> {{ $pageTitle }} <i class="fa-solid fa-dharmachakra text-primary text-primary"></i></h1>
      <div class="bg-primary mx-auto h-[3px] w-[170px]"></div>
    </div>
    <div class="mt-4 flex items-center rounded-lg bg-white px-3 py-3 shadow-lg mb-3">
      {!! $spinQuest->descr !!}
    </div>
    <div>
      <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
        <div class="border-primary shadow-primary col-span-1 gap-4 rounded-xl border border-b p-4 text-center bg-white shadow-xl sm:col-span-2">
          <div class="v-luckywheel relative flex justify-center">
            <div class="wheel-wrapper">
              <a class="wheel-pointer cursor-pointer opacity-75 hover:opacity-100 start-spin" id="start"></a>
              <img alt="Play" src="{{ $spinQuest->image }}" id="spin">
            </div>

          </div>
        </div>
        <div>
          <div class="shadow-primary border-primary col-span-1 gap-4 rounded-xl border border-b bg-white p-4 text-center shadow-xl sm:col-span-2 max-h-[790px]">
            <div class="mb-2">
              <button class="btn btn-sm btn-primary w-full mb-2 start-spin">Giá {{ Helper::formatCurrency($spinQuest->price) }} / Lượt</button>
              <button onclick="window.location='{{ route('account.withdraws.index') }}'" class="btn btn-sm btn-danger w-full">Rút Thưởng Ngay</button>
            </div>
            <div class="border border-b-black-200"></div>
            <marquee direction="down" class="p-3" height="700" onmouseover="this.stop();" onmouseout="this.start();" scrolldelay="1" behavior="scroll">
              @foreach (\App\Models\SpinQuestLog::where('spin_quest_id', $spinQuest->id)->orderBy('id', 'desc')->limit(15)->get() as $log)
                <button class="btn btn-sm btn-outline-primary w-full mb-2">
                  <span class="text-info-600">
                    {{ Helper::hideUsername($log->username, 3) }}</span> quay trúng <span class="text-success-600">{{ $log->prize }} {{ $log->unit }}</span> vào <span
                    class="text-green-500">{{ Helper::getTimeAgo($log->created_at) }}
                  </span>
                </button>
              @endforeach
            </marquee>
          </div>
        </div>
      </div>
    </div>
  </section>
  @push('scripts')
    <script src="/plugins/rotate/rotate.js"></script>
    <script>
      $(document).ready(function() {

        var bRotate = false;

        function rotateFn(angles, txt, type) {
          bRotate = !bRotate;
          $('#spin').stopRotate();
          $('#spin').rotate({
            angle: 0,
            animateTo: angles + 1800,
            duration: 4000, // tốc độ quay tay
            callback: function() {
              var awar = txt;

              Swal.fire('Thành công !', awar, 'success').then(() => {
                location.reload()
              })

              bRotate = !bRotate;
            }
          })
        }



        $('.start-spin').click(function() {

          if (bRotate) {
            return;
          };

          axios.post('/api/games/spin-quest/turn', {
            id: {{ $spinQuest->id }}
          }).then(({
            data: r
          }) => {
            rotateFn(r.location, r.message, "success")
          }).catch(e => {
            Swal.fire('Oops ...', $catchMessage(e), 'error')
          })


        });

      });
    </script>
  @endpush
</x-app-layout>
