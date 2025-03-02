@extends('admin.layouts.master')
@section('title', 'Admin: Spin Quest Management')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Chi Tiết Vòng Quay "{{ $spinQuest->name }}"</div>
    </div>
    <div class="card-body">

      <form action="{{ route('admin.games.spin-quest.update', ['id' => $spinQuest->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
        @csrf
        <div class="mb-3">
          <label for="cover" class="form-label">Ảnh Bìa</label>
          <input class="form-control" type="file" id="cover" name="cover">
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Ảnh Vòng Quay</label>
          <input class="form-control" type="file" id="image" name="image">
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Tên Vòng Quay</label>
            <input class="form-control" type="text" id="name" name="name" value="{{ $spinQuest->name }}" required>
          </div>
          <div class="col-md-6">
            <label for="price" class="form-label">Giá Mỗi Lượt</label>
            <input class="form-control" type="text" id="price" name="price" value="{{ $spinQuest->price }}" required>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="type" class="form-label">Hình Thức Trả Thưởng</label>
            <select class="form-control" id="type" name="type">
              <option value="custom" @if ($spinQuest->type == 'custom') selected @endif>Tuỳ Chỉnh</option>
              {{-- <option value="account" @if ($spinQuest->type == 'account') selected @endif>Từ Kho Acc</option> --}}
            </select>
          </div>
          <div class="col-md-6">
            <label for="store_id" class="form-label">ID Kho</label>
            <select name="store_id" id="store_id" class="form-control">
              <option value="">Chọn Kho</option>
              @if ($spinQuest->type === 'account')
                @foreach (\App\Models\Group::where('status', true)->get() as $store)
                  <option value="{{ $store->id }}" @if ($spinQuest->store_id == $store->id) selected @endif>{{ $store->name }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="invar_id" class="form-label">Loại Phần Thưởng</label>
            <select class="form-control" id="invar_id" name="invar_id">
              @foreach ($inventoryVars as $inventory)
                <option value="{{ $inventory->id }}" @if ($inventory->id === $spinQuest->invar_id) selected @endif>ID {{ $inventory->id }}: {{ $inventory->name }} - {{ $inventory->unit }}</option>
              @endforeach
              <option value="" @if (!$spinQuest->invar_id) selected @endif>Kho chung (thuật toán cũ)</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="category_id" class="form-label">Danh Mục Trò Chơi</label>
            <select class="form-control" id="category_id" name="category_id">
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" @if ($category->id === $spinQuest->category_id) selected @endif>ID {{ $category->id }}: {{ $category->name }}</option>
              @endforeach
              <option value="" @if (!$spinQuest->category_id) selected @endif>Hiện Riêng Ở Đầu/Cuối</option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label for="descr" class="form-label">Hướng Dẫn Chơi</label>
          <textarea class="form-control ckeditor" id="descr" name="descr" rows="3">{{ $spinQuest->descr }}</textarea>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Trạng thái</label>
          <select class="form-control" id="status" name="status">
            <option value="1">Hoạt động</option>
            <option value="0" @if ($spinQuest->status !== true) selected @endif>Không hoạt động</option>
          </select>
        </div>
        <div class="mb-3">
          <button class="btn btn-primary w-100" type="submit">Cập Nhật</button>
        </div>
      </form>
    </div>
  </div>
  <div class="alert alert-danger">Thứ tự vòng quay bắt đầu theo chiều kim đồng hồ, <img src="https://i.imgur.com/DAHLRjT.png" width="30" alt=""> hướng lên là Phần thưởng #0 -> 1 2 3 4 5 6 7 8... [Nhập giá trị:
    100 là được nhận 100rb, nhập 200-300 là hệ thống random trong khoản 200 đến 300]</div>
  <div class="alert alert-danger">Nếu muốn tạo dữ liệu ảo thì cron link: {{ Helper::getDomain() }}/cron/fake-spin-quest - hệ thống sẽ random 300-2599{unit} vào lịch sử trò chơi (bật hiện số lượng trong cài đặt chung (giao
    diện))</div>
  @if ($spinQuest->type === 'custom')
    <form action="{{ route('admin.games.spin-quest.update-prize', ['id' => $spinQuest->id]) }}" method="POST" class="default-form">
      @csrf
      <div class="row">
        @for ($i = 0; $i < 8; $i++)
          <div class="col-md-3">
            <div class="card custom-card">
              <div class="card-header justify-content-between">
                <div class="card-title">Phần Thưởng #{{ $i }}</div>
              </div>
              <div class="card-body">
                {{-- <div class="mb-3">
              <label for="title" class="form-label">Tên</label>
              <input class="form-control" type="text" id="title" name="prizes[{{ $i }}][title]" value="{{ $spinQuest['prizes'][$i]['title'] ?? '-' }}" required>
            </div> --}}
                <div class="mb-3">
                  <label for="percent" class="form-label">Tỉ Lệ</label>
                  <input class="form-control" type="text" id="percent" name="prizes[{{ $i }}][percent]" value="{{ $spinQuest['prizes'][$i]['percent'] ?? 10 }}" placeholder="10" required>
                </div>
                <div class="mb-3">
                  <label for="value" class="form-label">
                    Giá Trị -
                    <small class="text-danger">
                      {{ $spinQuest['prizes'][$i]['random'] ?? false ? 'Đang Random' : 'Không random' }}
                    </small>
                  </label>
                  <input class="form-control" type="text" id="value" name="prizes[{{ $i }}][value]" value="{{ $spinQuest['prizes'][$i]['value'] ?? 0 }}"
                    placeholder="Nhập giá trị cụ thể, hoặc 100-200 để random trong khoản đó" required>
                </div>
              </div>
            </div>
          </div>
        @endfor
      </div>
      <div class="mb-3">
        <button class="btn btn-primary w-100" type="submit">Cập Nhật</button>
      </div>
    </form>
  @endif
@endsection
@section('scripts')
  <script src="/plugins/ckeditor/ckeditor.js"></script>

  <script>
    $(function() {
      const editor = CKEDITOR.replace('content', {
        extraPlugins: 'notification',
        clipboard_handleImages: false,
        filebrowserImageUploadUrl: '/api/admin/tools/upload?form=ckeditor'
      });

      editor.on('fileUploadRequest', function(evt) {
        var xhr = evt.data.fileLoader.xhr;

        xhr.setRequestHeader('Cache-Control', 'no-cache');
        xhr.setRequestHeader('Authorization', 'Bearer ' + userData.access_token);
      })

    })
  </script>
@endsection
