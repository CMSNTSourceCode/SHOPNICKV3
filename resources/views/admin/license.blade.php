@extends('admin.layouts.master')
@section('title', 'Admin: Dashboard')
@section('content')
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
        <button class="btn btn-info btn-sm shadow-sm btn-wave copy waves-effect waves-light" data-clipboard-target="#copyKey" onclick="copy()">Copy</button>
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
  <div class="text-center">
    <h4>Thông tin giấy phép của bạn không hợp lệ, vui lòng kiểm tra lại!</h4>
    <code>Mã kích hoạt: {{ Helper::hideUsername(env('CLIENT_SECRET_KEY', ''), 20) }}</code>
    <br />
    <code>Chi tiết lỗi: {{ $check['msg'] }}</code>
    <br />
    <p>Hướng dẫn khắc phục: truy cập vào vps/hosting kiểm tra và cập nhật
      "CLIENT_SECRET_KEY=" tại file .env trong thư mục cài đặt website, nếu chưa có giấy phép vui lòng liên hệ mua bản quyền tại <a href="https://www.cmsnt.co/2023/09/thiet-ke-website-ban-nick-game-mau-3.html"
      target="_blank">đây</a></p>
  </div>
@endsection
