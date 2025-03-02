@extends('admin.layouts.master')
@section('title', 'Admin: Notices Settings')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Trang chủ</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'home_dashboard']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $home_dashboard ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Nổi ở trang chủ</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'modal_dashboard']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $modal_dashboard ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Trang nạp tiền qua thẻ / ngân hàng</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'page_deposit']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $page_deposit ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Trang nạp tiền bằng hóa đơn qua ngân hàng</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'page_deposit_invoice']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $page_deposit_invoice ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Trang nạp tiền bằng crypto</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'page_deposit_crypto']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $page_deposit_crypto ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Trang nạp tiền qua perfect money</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'page_deposit_pmoney']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $page_deposit_pmoney ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Trang nạp tiền qua paypal</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'page_deposit_paypal']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $page_deposit_paypal ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Trang thông tin tài khoản v1/v2</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'page_account_info']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $page_account_info ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thông báo | Hướng dẫn rút thưởng v2</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.settings.notices.update', ['type' => 'withdraw_v2_tut']) }}" method="POST" class="default-form">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="content" rows="5">{{ $withdraw_v2_tut ?? '' }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary" type="submit">Cập nhật ngay</button>
        </div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="/plugins/ckeditor/ckeditor.js"></script>

  <script>
    $(function() {
      const editors = document.querySelectorAll('[name=content]');

      for (const editor of editors) {
        const ed = CKEDITOR.replace(editor, {
          extraPlugins: 'notification',
          clipboard_handleImages: false,
          filebrowserImageUploadUrl: '/api/admin/tools/upload?form=ckeditor'
        });

        ed.on('fileUploadRequest', function(evt) {
          var xhr = evt.data.fileLoader.xhr;

          xhr.setRequestHeader('Cache-Control', 'no-cache');
          xhr.setRequestHeader('Authorization', 'Bearer ' + userData.access_token);
        })
      }

    })
  </script>
@endsection
