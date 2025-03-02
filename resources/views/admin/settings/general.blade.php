@extends('admin.layouts.master')
@section('title', 'Admin: General Settings')
@section('css')
  <link rel="stylesheet" href="{{ asset('/plugins/codemirror/codemirror.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/codemirror/theme/monokai.css') }}">
@endsection
@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Cài Đặt Hệ Thống</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.general.update', ['type' => 'general']) }}" method="POST" class="default-form" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', setting('title')) }}">
              </div>
              <div class="col-md-6">
                <label for="description" class="form-label">Mô tả</label>
                <input type="text" class="form-control" id="description" name="description" value="{{ old('description', setting('description')) }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="keywords" class="form-label">Từ khoá</label>
                <input type="text" class="form-control" id="keywords" name="keywords" value="{{ old('keywords', setting('keywords')) }}">
              </div>
              <div class="col-md-6">
                <label for="primary_color" class="form-label">Màu chính - <span style="color: {{ setting('primary_color') }}">MÀU HIỆN TẠI</span></label>
                <input type="color" class="form-control mb-1" id="primary_color" name="primary_color" value="{{ old('primary_color', setting('primary_color')) }}" style="height: 36.4px">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="email" class="form-label">Email Admin</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', setting('email')) }}">
              </div>
              <div class="col-md-6">
                <div class="mb-2">
                  <label for="captcha" class="form-label">Bật xác thực captcha (<a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCaptcha</a>)</label>
                  <select class="form-select" id="captcha" name="captcha">
                    <option value="1" {{ setting('captcha') == 1 ? 'selected' : '' }}>Bật</option>
                    <option value="0" {{ setting('captcha') == 0 ? 'selected' : '' }}>Tắt</option>
                  </select>
                </div>
                {{-- site-key --}}
                <div class="mb-2 group_recaptcha">
                  <label for="captcha_site_key" class="form-label">Captcha Site Key</label>
                  <input type="text" class="form-control" id="captcha_site_key" name="captcha_site_key" value="{{ old('captcha_site_key', setting('captcha_site_key')) }}">
                </div>

                {{-- secret-key --}}
                <div class="mb-2 group_recaptcha">
                  <label for="captcha_secret_key" class="form-label">Captcha Secret Key</label>
                  <input type="text" class="form-control" id="captcha_secret_key" name="captcha_secret_key" value="{{ old('captcha_secret_key', setting('captcha_secret_key')) }}">
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="time_wait_free" class="form-label">Giới hạn thời gian mua mỗi lượt</label>
                <input type="number" class="form-control" id="time_wait_free" name="time_wait_free" value="{{ old('time_wait_free', setting('time_wait_free', 10)) }}">
                <small>Ví dụ nhập vào số 10: tức sau khi mua hàng, user đó phải đợi 10 giây mới có thể thực hiện tiếp giao dịch mua</small>
              </div>
              <div class="col-md-6">
                <label for="max_ip_reg" class="form-label">Giới hạn số tài khoản đăng ký trên 1 IP</label>
                <input type="number" class="form-control" id="max_ip_reg" name="max_ip_reg" value="{{ old('max_ip_reg', setting('max_ip_reg', 5)) }}">
                <small>VD: 5 => mỗi IP chỉ được tạo tối đa 5 tài khoản</small>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="rate_robux" class="form-label">Rate Robux - Shop Items</label>
                <input type="number" class="form-control" id="rate_robux" name="rate_robux" value="{{ old('rate_robux', setting('rate_robux', 100)) }}">
                <small>VD: Áp dụng cho shop items, tổng tiền = Robux * Rate</small>
              </div>
              <div class="col-md-6">
                <label for="comm_percent" class="form-label">% Hoa hồng</label>
                <input type="number" class="form-control" id="comm_percent" name="comm_percent" value="{{ old('comm_percent', setting('comm_percent', 10)) }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="upload_provider" class="form-label">Upload Hình Ảnh Đến</label>
                <select class="form-select" id="upload_provider" name="upload_provider">
                  <option value="imgbb" {{ setting('upload_provider') == 'imgbb' ? 'selected' : '' }}>Img BB</option>
                  <option value="s3" {{ setting('upload_provider') == 's3' ? 'selected' : '' }}>S3 Amazon</option>
                  <option value="do_spaces" {{ setting('upload_provider') == 'do_spaces' ? 'selected' : '' }}>DO Spaces</option>
                  <option value="public" {{ setting('upload_provider', 'public') == 'public' ? 'selected' : '' }}>Your Hosting</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="default_theme" class="form-label">Theme Mặc Định</label>
                <select class="form-select" id="default_theme" name="default_theme">
                  <option value="light" {{ setting('default_theme') == 'light' ? 'selected' : '' }}>Light</option>
                  <option value="dark" {{ setting('default_theme') == 'dark' ? 'selected' : '' }}>Dark</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-3">
                <label for="logo_light" class="form-label">Logo Light</label>
                <input type="file" class="form-control" id="logo_light" name="logo_light">
                <div class="mb-2 mt-2 text-center">
                  <img src="{{ asset(setting('logo_light')) }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
                </div>
              </div>
              <div class="col-md-3">
                <label for="logo_dark" class="form-label">Logo Dark</label>
                <input type="file" class="form-control" id="logo_dark" name="logo_dark">
                <div class="mb-2 mt-2 text-center">
                  <img src="{{ asset(setting('logo_dark')) }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
                </div>
              </div>
              <div class="col-md-3">
                <label for="favicon" class="form-label">Favicon</label>
                <input type="file" class="form-control" id="favicon" name="favicon">
                <div class="mb-2 mt-2 text-center">
                  <img src="{{ asset(setting('favicon')) }}" alt="Favicon" class="img-fluid" style="max-height: 100px;">
                </div>
              </div>
              <div class="col-md-3">
                <label for="logo_share" class="form-label">Logo Share</label>
                <input type="file" class="form-control" id="logo_share" name="logo_share">
                <div class="mb-2 mt-2 text-center">
                  <img src="{{ asset(setting('logo_share')) }}" alt="logo_share" class="img-fluid" style="max-height: 100px;">
                </div>
              </div>
            </div>

            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Rút Thưởng Miễn Phí Vật Phẩm Trong Trò Chơi [GỐC TRÁI WEBSITE]</div>
        </div>
        <div class="card-body">
          <div class="alert alert-danger">* Nếu có 4 vòng quay (4 vật phẩm) thì khách sẽ nhận được random 4 vật phẩm (theo số lượng vòng quay hiện tại)</div>
          @php $get_gift = Helper::getConfig('get_gift'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'get_gift']) }}" method="POST" class="default-form" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="status" class="form-label">Trạng Thái</label>
                <select name="status" id="status" class="form-control">
                  <option value="1" {{ ($get_gift['status'] ?? 0) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($get_gift['status'] ?? 0) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="balance" class="form-label">Yêu Cầu Số Dư</label>
                <input type="number" class="form-control" id="balance" name="balance" value="{{ $get_gift['balance'] ?? 0 }}" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="min" class="form-label">Tối Thiểu</label>
                <input type="number" class="form-control" id="min" name="min" value="{{ $get_gift['min'] ?? 0 }}" required>
              </div>
              <div class="col-md-6">
                <label for="max" class="form-label">Tối Đa</label>
                <input type="number" class="form-control" id="max" name="max" value="{{ $get_gift['max'] ?? 0 }}" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="width" class="form-label">Width: Ảnh</label>
                <input type="number" class="form-control" id="width" name="width" value="{{ $get_gift['width'] ?? 0 }}" required>
              </div>
              <div class="col-md-6">
                <label for="height" class="form-label">Height: Ảnh</label>
                <input type="number" class="form-control" id="height" name="height" value="{{ $get_gift['height'] ?? 0 }}" required>
              </div>
            </div>
            <div class="mb-">
              <label for="up_image" class="form-label">Hình Ảnh</label>
              <input type="file" class="form-control" id="up_image" name="up_image">
              <input type="url" name="image" class="form-control mt-2" placeholder="Nhập link hoặc chọn ảnh để upload" value="{{ $get_gift['image'] ?? '' }}">
              <div class="mb-2 mt-2 text-center">
                <img src="{{ asset($get_gift['image'] ?? '') }}" alt="Gift" class="img-fluid" style="max-height: 100px;">
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông Tin Rút Thưởng Chung MiniGame</div>
        </div>
        <div class="card-body">
          @php $mng_withdraw = Helper::getConfig('mng_withdraw'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'mng_withdraw']) }}" method="POST" class="default-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="unit" class="form-label">Đơn Vị</label>
                <input type="text" class="form-control" id="unit" name="unit" value="{{ $mng_withdraw['unit'] ?? 'Robux' }}" required>
              </div>
              <div class="col-md-6">
                <label for="youtube_id" class="form-label">ID Youtube</label>
                <input type="text" class="form-control" id="youtube_id" name="youtube_id" value="{{ $mng_withdraw['youtube_id'] ?? '' }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="min_withdraw" class="form-label">Tối Thiểu</label>
                <input type="number" class="form-control" id="min_withdraw" name="min_withdraw" value="{{ $mng_withdraw['min_withdraw'] ?? 0 }}" required>
              </div>
              <div class="col-md-6">
                <label for="max_withdraw" class="form-label">Tối Đa / Lần</label>
                <input type="number" class="form-control" id="max_withdraw" name="max_withdraw" value="{{ $mng_withdraw['max_withdraw'] ?? 0 }}" required>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông Tin Nạp Tiền</div>
        </div>
        <div class="card-body">
          @php $deposit_port = Helper::getConfig('deposit_port'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'deposit_port']) }}" method="POST" class="default-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="card" class="form-label">Thẻ Cào</label>
                <select name="value[card]" id="card" class="form-control">
                  <option value="1" {{ ($deposit_port['card'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($deposit_port['card'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="bank" class="form-label">Ngân Hàng</label>
                <select name="value[bank]" id="bank" class="form-control">
                  <option value="1" {{ ($deposit_port['bank'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($deposit_port['bank'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="invoice" class="form-label">Ngân hàng [Hóa đơn]</label>
                <select name="value[invoice]" id="invoice" class="form-control">
                  <option value="1" {{ ($deposit_port['invoice'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($deposit_port['invoice'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="crypto" class="form-label">Tiền Mã Hoá</label>
                <select name="value[crypto]" id="crypto" class="form-control">
                  <option value="1" {{ ($deposit_port['crypto'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($deposit_port['crypto'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="paypal" class="form-label">Paypal</label>
                <select name="value[paypal]" id="paypal" class="form-control">
                  <option value="1" {{ ($deposit_port['paypal'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($deposit_port['paypal'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="perfect_money" class="form-label">Perfect Money</label>
                <select name="value[perfect_money]" id="perfect_money" class="form-control">
                  <option value="1" {{ ($deposit_port['perfect_money'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($deposit_port['perfect_money'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông Tin Giới Thiệu</div>
        </div>
        <div class="card-body">
          @php $shop_info = Helper::getConfig('shop_info'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'shop_info']) }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="footer_text_1" class="form-label">Footer Text 1</label>
              <input type="text" class="form-control" id="footer_text_1" name="footer_text_1" value="{{ $shop_info['footer_text_1'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="footer_text_2" class="form-label">Footer Text 2</label>
              <input type="text" class="form-control" id="footer_text_2" name="footer_text_2" value="{{ $shop_info['footer_text_2'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="dashboard_text_1" class="form-label">Dashboard Text 1</label>
              <input type="text" class="form-control" id="dashboard_text_1" name="dashboard_text_1" value="{{ $shop_info['dashboard_text_1'] ?? '' }}">
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông Tin Nạp Tiền</div>
        </div>
        <div class="card-body">
          @php $deposit_info = Helper::getConfig('deposit_info'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'deposit_info']) }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="prefix" class="form-label">Cú Pháp</label>
              <input type="text" class="form-control" id="prefix" name="prefix" value="{{ $deposit_info['prefix'] ?? 'hello ' }}" required>
            </div>
            <div class="mb-3">
              <label for="discount" class="form-label">Khuyến Mãi [+ %]</label>
              <input type="text" class="form-control" id="discount" name="discount" value="{{ $deposit_info['discount'] ?? 0 }}" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Dashboard Text 1</label>
              <input type="text" class="form-control" id="description" name="description" value="{{ $deposit_info['description'] ?? '' }}" disabled>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông Báo Đơn Hàng Về Telegram</div>
        </div>
        <div class="card-body">
          @php $telegram_config = Helper::getConfig('telegram_config'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'telegram_config']) }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="bot_token" class="form-label">BOT Token</label>
                <input type="text" class="form-control" id="bot_token" name="bot_token" value="{{ $telegram_config['bot_token'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="chat_id" class="form-label">ChatID (ID Group or ID User)</label>
                <input type="text" class="form-control" id="chat_id" name="chat_id" value="{{ $telegram_config['chat_id'] ?? '' }}">
              </div>

            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Cấu hình Affiliate Program</div>
        </div>
        <div class="card-body">
          @php $affiliate_config = Helper::getConfig('affiliate_config'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'affiliate_config']) }}" method="POST" class="default-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="min_withdraw" class="form-label">Tối Thiểu</label>
                <input type="number" class="form-control" id="min_withdraw" name="min_withdraw" value="{{ $affiliate_config['min_withdraw'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="max_withdraw" class="form-label">Tối Đa</label>
                <input type="number" class="form-control" id="max_withdraw" name="max_withdraw" value="{{ $affiliate_config['max_withdraw'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="withdraw_status" class="form-label">Trạng Thái</label>
                <select class="form-control" id="withdraw_status" name="withdraw_status">
                  <option value="1" {{ ($affiliate_config['withdraw_status'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($affiliate_config['withdraw_status'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Tuỳ chỉnh giao diện</div>
        </div>
        <div class="card-body">
          @php $bconfig = Helper::getConfig('theme_custom'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'theme_custom']) }}" method="POST" class="default-form" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="card_stats" class="form-label">Thẻ Thống Kê</label>
                <select class="form-select" id="card_stats" name="card_stats">
                  <option value="1" {{ ($bconfig['card_stats'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($bconfig['card_stats'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="product_info_type" class="form-label">Thẻ Thống Kê</label>
                <select class="form-select" id="product_info_type" name="product_info_type">
                  <option value="0" {{ ($bconfig['product_info_type'] ?? null) == 0 ? 'selected' : '' }}>Chỉ hiện nick còn lại</option>
                  <option value="1" {{ ($bconfig['product_info_type'] ?? null) == 1 ? 'selected' : '' }}>Hiện đã bán và Nick còn lại</option>
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="buy_button_img" class="form-label">Link ảnh nút mua</label>
                <input type="text" class="form-control" id="buy_button_img" name="buy_button_img" value="{{ $bconfig['buy_button_img'] ?? '_assets/images/stores/view-all.gif' }}">
              </div>
              <div class="col-md-6">
                <label for="enable_custom_theme" class="form-label">Cho Phép Tuỳ Chỉnh Theme</label>
                <select class="form-select" id="enable_custom_theme" name="enable_custom_theme">
                  <option value="1" {{ ($bconfig['enable_custom_theme'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($bconfig['enable_custom_theme'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="show_thongbao" class="form-label">Hiện Thông Báo Chạy</label>
                <select class="form-select" id="show_thongbao" name="show_thongbao">
                  <option value="1" {{ ($bconfig['show_thongbao'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($bconfig['show_thongbao'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="show_lsmua" class="form-label">Hiện Lịch Sử Mua Nick</label>
                <select class="form-select" id="show_lsmua" name="show_lsmua">
                  <option value="1" {{ ($bconfig['show_lsmua'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($bconfig['show_lsmua'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="show_banner" class="form-label">Hiện Banner và TOP Nạp</label>
                <select class="form-select" id="show_banner" name="show_banner">
                  <option value="1" {{ ($bconfig['show_banner'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($bconfig['show_banner'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="show_all_account_img" class="form-label">Tắt Slide ảnh sản phẩm (Account Info)</label>
                <select class="form-select" id="show_all_account_img" name="show_all_account_img">
                  <option value="1" {{ ($bconfig['show_all_account_img'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($bconfig['show_all_account_img'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label for="youtube" class="form-label">Youtube ID</label>
                <input type="text" class="form-control" id="youtube" name="youtube" value="{{ $bconfig['youtube'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="banner" class="form-label">Banner - <a href="{{ asset($bconfig['banner'] ?? '') }}" target="_blank">xem ảnh</a></label>
                <input type="file" class="form-control" id="banner" name="banner">
              </div>
              <i>* Nếu nhập Youtube ID thì ảnh sẽ không hoạt động</i>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="background_image" class="form-label">Link Ảnh Nền - <a href="{{ asset($bconfig['background_image'] ?? '') }}" target="_blank">xem ảnh</a></label>
                <input type="text" class="form-control" id="background_image" name="background_image" value="{{ $bconfig['background_image'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="minigame_pos" class="form-label">Vị Trí Hiện MiniGame</label>
                <select class="form-select" id="minigame_pos" name="minigame_pos">
                  <option value="0" {{ ($bconfig['minigame_pos'] ?? null) == 0 ? 'selected' : '' }}>Không Hiện</option>
                  <option value="top" {{ ($bconfig['minigame_pos'] ?? null) == 'top' ? 'selected' : '' }}>Bên Trên</option>
                  <option value="bottom" {{ ($bconfig['minigame_pos'] ?? null) == 'bottom' ? 'selected' : '' }}>Bên Dưới</option>
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="minigame_show_value" class="form-label">Hiển thị chi tiết phần thưởng</label>
                <select class="form-select" id="minigame_show_value" name="minigame_show_value">
                  <option value="1" {{ ($bconfig['minigame_show_value'] ?? null) == 1 ? 'selected' : '' }}>Bật</option>
                  <option value="0" {{ ($bconfig['minigame_show_value'] ?? null) == 0 ? 'selected' : '' }}>Tắt</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="product_cover" class="form-label">Link Ảnh Viền - <a href="{{ asset($bconfig['product_cover'] ?? '') }}" target="_blank">xem ảnh</a></label>
                <input type="text" class="form-control" id="product_cover" name="product_cover" value="{{ $bconfig['product_cover'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="pin_type" class="form-label">Kiểu hiển thị nhóm GHIM</label>
                <select class="form-select" id="pin_type" name="pin_type">
                  <option value="slide" {{ ($bconfig['pin_type'] ?? null) == 'slide' ? 'selected' : '' }}>Slide</option>
                  <option value="grid" {{ ($bconfig['pin_type'] ?? null) == 'grid' ? 'selected' : '' }}>Grid</option>
                </select>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Thông tin liên hệ</div>
        </div>
        <div class="card-body">
          @php $contact = Helper::getConfig('contact_info'); @endphp
          <form action="{{ route('admin.settings.general.update', ['type' => 'contact_info']) }}" method="POST" class="default-form" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="facebook" class="form-label">Facebook</label>
                <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $contact['facebook'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="telegram" class="form-label">Telegram</label>
                <input type="text" class="form-control" id="telegram" name="telegram" value="{{ $contact['telegram'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="twitter" class="form-label">Twitter</label>
                <input type="text" class="form-control" id="twitter" name="twitter" value="{{ $contact['twitter'] ?? '' }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="phone_no" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $contact['phone_no'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ $contact['email'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="discord" class="form-label">Discord</label>
                <input type="text" class="form-control" id="discord" name="discord" value="{{ $contact['discord'] ?? '' }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $contact['instagram'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Header Code</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.general.update', ['type' => 'header_script']) }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="code" class="form-label">Code</label>
              <textarea class="form-control" name="code" id="editor1" rows="10">{{ Helper::getNotice('header_script') }}</textarea>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Footer Code</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.general.update', ['type' => 'footer_script']) }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="code" class="form-label">Code</label>
              <textarea class="form-control" name="code" id="editor2" rows="10">{{ Helper::getNotice('footer_script') }}</textarea>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('/plugins/codemirror/codemirror.js') }}"></script>
  <script src="{{ asset('/plugins/codemirror/mode/css/css.js') }}"></script>
  <script src="{{ asset('/plugins/codemirror/mode/xml/xml.js') }}"></script>
  <script src="{{ asset('/plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>

  <script>
    $(function() {
      // CodeMirror
      CodeMirror.fromTextArea(document.getElementById("editor1"), {
        mode: "htmlmixed",
        theme: "monokai"
      });
      CodeMirror.fromTextArea(document.getElementById("editor2"), {
        mode: "htmlmixed",
        theme: "monokai"
      });

      $('#captcha').change(function() {
        if ($(this).val() == 1) {
          $('.group_recaptcha').show();
        } else {
          $('.group_recaptcha').hide();
        }
      })

      $('#captcha').trigger('change');
    })
  </script>
@endsection
