@extends('admin.layouts.master')
@section('title', 'Admin: Create Accounts Group')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Sửa nhóm "{{ $group->name }}"</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.accountsv2.groups.update', ['id' => $group->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
        @csrf
        <div class="mb-3">
          <label for="priority" class="form-label">Ưu tiên</label>
          <input type="number" id="priority" name="priority" class="form-control" value="{{ $group->priority }}" required>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Ảnh bìa</label>
          <input type="file" id="image" name="image" class="form-control">
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Tên nhóm</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên nhóm" value="{{ $group->name }}" required>
        </div>
        <div class="mb-3">
          <label for="descr" class="form-label">Mô tả</label>
          <textarea class="form-control ckeditor" id="descr" name="descr" rows="3" placeholder="Nhập ghi chú">{{ $group->descr }}</textarea>
        </div>
        <div class="mb-3">
          <label for="descr_seo" class="form-label">Mô tả (gốc dưới)</label>
          <textarea class="form-control ckeditor" id="descr_seo" name="descr_seo" rows="3" placeholder="Nhập ghi chú">{{ $group->descr_seo }}</textarea>
        </div>
        <div class="mb-3 row">
          <div class="col-md-6">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="meta_seo[title]" placeholder="Nhập tiêu đề" value="{{ $group->meta_seo['title'] ?? '' }}">
          </div>
          <div class="col-md-6">
            <label for="keywords" class="form-label">Từ khóa</label>
            <input type="text" class="form-control" id="keywords" name="meta_seo[keywords]" placeholder="Nhập từ khóa" value="{{ $group->meta_seo['keywords'] ?? '' }}">
          </div>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Trạng thái</label>
          <select class="form-control" id="status" name="status" required>
            <option value="1" @if ($group->status == 1) selected @endif>Hoạt động</option>
            <option value="0" @if ($group->status == 0) selected @endif>Tạm đóng</option>
          </select>
        </div>
        <div class="mb-3">
          <button class="btn btn-primary w-100" type="submit">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
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
