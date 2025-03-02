@section('title', __t($pageTitle))
<x-app-layout>
  <section class="space-y-6">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div class="card">
        <div class="card-body flex flex-col p-6">
          <header class="-mx-6 mb-5 flex items-center border-b border-slate-100 px-6 pb-5 dark:border-slate-700">
            <div class="flex-1">
              <div class="card-title text-slate-900 dark:text-white">{{ __t('Yêu Cầu Trả Thưởng') }}</div>
            </div>
          </header>
          <div class="card-text h-full space-y-4">
            <form action="/api/games/withdraws" method="POST" id="form-withdraw" class="space-y-3">
              <div class="input-area">
                <label for="balance_2" class="form-label">{{ __t('Số Dư') }} {{ ucfirst($config['unit'] ?? 'Coin') }}</label>
                <input id="balance_2" name="balance_2" type="text" class="form-control" value="{{ number_format($user->balance_2) }}" disabled>
              </div>
              <div class="input-area">
                <label for="value" class="form-label">{{ __t('Muốn Rút') }} [{{ $config['unit'] ?? 'Coin' }}]</label>
                @if (in_array(Helper::getDomain(), ['typhoshop.com', 'localhost']))
                  <select name="value" id="value" class="form-control">
                    <option value="113">113 Kim Cương</option>
                    <option value="283">283 Kim Cương</option>
                    <option value="566">566 Kim Cương</option>
                    <option value="1132">1132 Kim Cương</option>
                    <option value="2830">2830 Kim Cương</option>
                  </select>
                @else
                  <input id="value" name="value" type="number" class="form-control" value="{{ $config['min_withdraw'] ?? 0 }}" required>
                @endif
              </div>
              <div class="input-area">
                <label for="user_note" class="form-label">{{ __t('Ghi Chú Cho Admin') }}</label>
                <textarea id="user_note" name="user_note" class="form-control" rows="3" placeholder="{{ __t('Ghi chú cho yêu cầu rút thưởng, taikhoan|matkhau|link_gp') }}" required></textarea>
              </div>
              <div class="input-area">
                <button class="btn btn-primary w-full" type="submit">{{ __t('Rút Thưởng Ngay') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card">
        <header class="card-header noborder">
          <h4 class="card-title">{{ __t('Hướng Dẫn Rút Thưởng') }}</h4>
        </header>
        <div class="card-body px-6 pb-6">
          <iframe width="100%" height="350" src="https://www.youtube.com/embed/{{ $config['youtube_id'] ?? null }}" title="{{ __t('Hướng dẫn rút thưởng') }}" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body flex flex-col p-6">
        <header class="-mx-6 mb-5 flex items-center border-b border-slate-100 px-6 pb-5 dark:border-slate-700">
          <div class="flex-1">
            <div class="card-title text-slate-900 dark:text-white">{{ __t('Lịch Sử Rút Thưởng') }}</div>
          </div>
        </header>
        <div class="card-body px-6 pb-6" id="app">
          <withdraw-index />
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
    <script>
      $("#form-withdraw").submit(async (e) => {
        e.preventDefault()

        const form = e.target,
          btn = form.querySelector('button[type="submit"]')
        const payload = $formDataToPayload(new FormData(form))

        $setLoading(btn)

        try {
          const {
            data: result
          } = await axios.post(form.action, payload)

          Swal.fire('Thành công', result.message, 'success').then(() => {
            location.reload()
          })
        } catch (error) {
          Swal.fire('Thất Bại', $catchMessage(error), 'error')
        } finally {
          $removeLoading(btn)
        }
      })
    </script>
    @vite('resources/js/modules/account/withdraw/index.js')
  @endpush
</x-app-layout>
