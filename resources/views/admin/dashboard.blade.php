@extends('admin.layouts.master')
@section('title', 'Admin: Dashboard')
@section('content')
  <style>
    .card-stats h3 {
      color: #1b619f;
      font-size: 36px;
    }

    .card-stats h5 {
      color: #6a6767;
      font-size: 18px;
    }
  </style>
  <section class="mb-3">
    <div class="mb-3 alert alert-secondary alert-dismissible fade show custom-alert-icon shadow-sm" role="alert">
      @php $rsl = checkLicenseKey(env('CLIENT_SECRET_KEY')) @endphp
      @if ($rsl['status'] === false)
        <h5>Thông báo: <strong style="color:red;">{{ $rsl['message'] ?? 'KHÔNG XÁC ĐỊNH - THỬ TẢI LẠI TRANG' }}</strong></h5>
        <div class="alert alert-danger">
          <ul>
            <li>** Lưu ý: Vui lòng sử dụng mã nguồn chính hãng từ CMSNT để tránh gặp lỗi không mong muốn.</li>
          </ul>
        </div>
      @endif
      <h5>SHOPNICK-V3 Version: <strong style="color:blue;">{{ currentVersion() }}</strong></h5>
      <small>Hệ thống sẽ tự động cập nhật phiên bản mới khi bạn truy cập trang này</small>
      <br><br>
      <h6>Giấy phép kích hoạt website của bạn là: <strong style="color:red;" id="copyKey">{{ env('CLIENT_SECRET_KEY') }}</strong>
        <button class="btn btn-info-gradient btn-sm shadow-sm btn-wave copy waves-effect waves-light" data-clipboard-target="#copyKey" onclick="copy()">Copy</button>
        <button class="btn btn-danger-gradient shadow-sm btn-sm wave waves-effect waves-light" onclick="fixUpdate()">NẾU LỖI HÃY BẤM VÀO ĐÂY</button>
      </h6>
      <small>Vui lòng bảo mật giấy phép của bạn, chỉ cung cấp cho <strong>CMSNT</strong> khi cần hỗ trợ.</small>
      <br>
      <hr>
      <p>Cộng đồng Suppliers của chúng tôi:</p>
      <ul>
        @if (env('PRJ_DEMO_MODE', true) === true)
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại CMSNT</strong></li>
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại CMSNT</strong></li>
          <li>Nhóm Telegram: <strong>chỉ áp dụng khi mua website chính hãng tại CMSNT</strong></li>
        @else
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại <a href="https://zalo.me/g/idapcx933" target="_blank">[CMSNT] Changelog - Notification</a></strong></li>
          <li>Nhóm Zalo: <strong>chỉ áp dụng khi mua website chính hãng tại <a href="https://zalo.me/g/eululb377" target="_blank">[CMSNT] Trao đổi API - Suppliers</a></strong></li>
          <li>Nhóm Telegram: <strong>chỉ áp dụng khi mua website chính hãng tại <a href="https://t.me/+LVON7y2BKWU3ZDY9" target="_blank">[CMSNT] Notification - API - Suppliers</a></strong></li>
        @endif
      </ul>
      <p class="text-danger">Những thay đổi trong phiên bản này:</p>
      <ul>
        @foreach (get_change_logs() as $changed)
          <li class="fw-bold text-blue">{!! $changed !!}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
    </div>
  </section>
  @if (auth()->user()->role === 'partner')
    <section>
      <h3>Thống Kê Đơn Tài Khoản</h3>
      <div class="row">
        @foreach ($stats['accounts'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_accounts'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <h3>Thống Kê Đơn Tài Khoản V2</h3>
      <div class="row">
        @foreach ($stats['accounts_v2'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_accounts_v2'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <h3>Thống Kê Đơn Vật Phẩm</h3>
      <div class="row">
        @foreach ($stats['items'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_items'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <h3>Thống Kê Đơn Cày Thuê</h3>
      <div class="row">
        @foreach ($stats['boostings'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_items'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @else
    <section>
      <h3>Thống Kê Thành Viên</h3>
      <div class="row">
        @foreach ($stats['users'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_users'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <h3>Thống Kê Đơn Tài Khoản</h3>
      <div class="row">
        @foreach ($stats['accounts'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_accounts'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <h3>Thống Kê Đơn Tài Khoản V2</h3>
      <div class="row">
        @foreach ($stats['accounts_v2'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_accounts_v2'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <h3>Thống Kê Đơn Vật Phẩm</h3>
      <div class="row">
        @foreach ($stats['items'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_items'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <h3>Thống Kê Đơn Cày Thuê</h3>
      <div class="row">
        @foreach ($stats['boostings'] as $key => $value)
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="text-center card-stats">
                  <h3>{{ number_format($value) }}</h3>
                  <h5>{{ $stats['t_items'][$key] ?? $key }}</h5>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif
@endsection
@section('scripts')
  <script>
    const fixUpdate = () => {
      axios.get('/cron/artisan/fix-update').then(r => {
        console.log(r.data);

        Swal.fire('Thành Công', 'Đã sửa lỗi cập nhật!', 'success')
      }).catch(e => {
        console.log(e);

        Swal.fire('Thất Bại', 'Có lỗi xảy ra!', 'error')
      })
    }

    // fixUpdate();

    $(document).ready(() => {
      const callApi = async (force = 0) => {
        try {
          const {
            data: result
          } = await axios.get('/admin/update', {
            params: {
              run: force
            }
          });

          if (force === 0) return result.data?.can_update || false
          else return result
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: $catchMessage(error),
          })
        }
      }
      const runUpdate = async () => {
        try {
          const canUpdate = await callApi(0)

          if (canUpdate) {
            $showLoading('Đang cập nhật, vui lòng đợi...')

            const result = await callApi(1)

            if (result.data?.version_code !== undefined) {
              return Swal.fire({
                icon: 'success',
                title: 'Đã cập nhật!',
                text: result.message
              }).then(() => {
                location.reload()
              })
            }

          }

          $hideLoading()

          console.log('Bạn đang dùng phiên bản mới nhất rồi keke')
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Cập nhật thất bại!',
            text: $catchMessage(error),
          })
        }
      }

      runUpdate();
    })
  </script>
@endsection
