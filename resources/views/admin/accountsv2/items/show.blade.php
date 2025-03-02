@extends('admin.layouts.master')
@section('title', 'Admin: Accounts Item')
@section('css')
  <link rel="stylesheet" href="/_admin/libs/dropzone/dropzone.css">
@endsection
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Chỉnh sửa sản phẩm "#{{ $item->code }}"</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.accountsv2.items.update', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Tên sản phẩm</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $item->name) }}" placeholder="Có thể bỏ trống">
        </div>
        <div class="mb-3">
          <label for="priority" class="form-label">Ưu tiên</label>
          <input type="number" id="priority" name="priority" class="form-control" value="{{ old('priority', 0) }}" required>
          <i>Số ưu tiên lớn thì nó hiện ở đầu</i>
        </div>
        <div class="mb-3">
          <label for="code" class="form-label">Mã sản phẩm</label>
          <input type="number" class="form-control" id="code" name="code" value="{{ old('code', $item->code) }}" required>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Ảnh sản phẩm - <a href="{{ asset($item->image) }}" target="_blank">xem ảnh</a></label>
          <input type="file" class="form-control" id="image" name="image">
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="price" class="form-label">Giá bán</label>
            <input type="text" class="form-control" id="price" name="price" value="{{ old('price', $item->price) }}" required>
          </div>
          <div class="col-md-4">
            <label for="cost" class="form-label">Giá nhập</label>
            <input type="text" class="form-control" id="cost" name="cost" value="{{ old('cost', $item->cost) }}" required>
          </div>
          <div class="col-md-4">
            <label for="discount" class="form-label">% Giảm giá</label>
            <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount', $item->discount) }}" required>
          </div>
        </div>
        <div class="mb-3 row">
          <div class="col-md-4">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-control" id="status" name="status" required>
              <option value="1" @if ($item->status === true) selected @endif>Đang bán</option>
              <option value="0" @if ($item->status === false) selected @endif>Ngưng bán</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="type" class="form-label">Loại sản phẩm</label>
            <select name="type" id="type" class="form-control" required>
              <option value="account">Mặc Định</option>
            </select>
          </div>
          @if (feature_enabled('bulk-orders'))
            <div class="col-md-4">
              <label for="is_bulk" class="form-label">Số lượng mua / lần</label>
              <input type="number" class="form-control" id="is_bulk" name="is_bulk" value="{{ old('is_bulk', $item->is_bulk) }}" required>
            </div>
          @endif
        </div>
        @if ($item->type === 'account')
          <div class="mb-3 row">
            <div class="col-md-4">
              <label for="username" class="form-label">Tài khoản</label>
              <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $item->username) }}" required>
            </div>
            <div class="col-md-4">
              <label for="password" class="form-label">Mật khẩu</label>
              <input type="text" class="form-control" id="password" name="password" value="{{ old('password', $item->password) }}" required>
            </div>
            <div class="col-md-4">
              <label for="extra_data" class="form-label">Tuỳ chọn</label>
              <input type="text" class="form-control" id="extra_code" name="extra_code" value="{{ old('extra_data', $item->extra_data) }}">
            </div>
          </div>
        @endif
        <div class="mb-3">
          <label for="highlights" class="form-label">Chi tiết sản phẩm</label>
          @php
            $highlights = '';
            if (is_array($item->highlights)) {
                foreach ($item->highlights as $key => $value) {
                    if (!isset($value['name']) && !isset($value['value'])) {
                        if (is_string($value)) {
                            $highlights .= $value . "\n";
                        } elseif (is_array($value)) {
                            if (isset($value[0])) {
                                $highlights .= $value[0] . "\n";
                            } else {
                                $highlights .= $key . "\n";
                            }
                        }
                    } else {
                        $highlights .= ($value['name'] ?? '') . ':' . ($value['value'] ?? '') . "\n";
                    }
                }
            }
          @endphp
          <textarea class="form-control" id="highlights" name="highlights" rows="3" required>{{ old('highlights', $highlights) }}</textarea>
          <i>Chi tiết nhập như sau: Cấp độ 20, 30 Tướng, Rank Đồng...</i>
        </div>

        {{-- <div class="mb-3">
          <label for="description" class="form-label">Mô tả sản phẩm</label>
          <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $item->description) }}</textarea>
        </div>
        <div class="mb-3">
          <label for="list_image" class="form-label">Hình ảnh sản phẩm</label>

          <i>Có thể nhiều hình ảnh cho sản phẩm</i>
          <br />
          <i>Hệ thống sẽ upload ảnh lên hosting</i>
        </div>

        <div class="dropzone dropzone-info" id="fileTypeValidation" action="/api/admin/tools/upload">
          <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
            <h6>Drop files here or click to upload.</h6><span class="note needsclick">(The system will automatically upload after you have finished selecting the photo.)</span>
          </div>
        </div>

        <div class="image-uploaded mb-3 mt-3">
          <div class="row">
            @foreach ($item->list_image as $key => $img)
              <div class="col-md-3 mb-4 text-center" data-file-id="img-{{ $key }}">
                <img src="{{ asset($img) }}" style="width: 100%; height: 100%;">
                <input type="hidden" name="list_image[]" value="{{ $img }}">
                <a href="javascript:deleteImage('img-{{ $key }}')" class="text-danger">Xóa</a>
              </div>
            @endforeach
          </div>
        </div> --}}

        <div class="mb-3 text-center">
          <button class="btn btn-primary"><i class="fa fa-save"></i> Cập nhật ngay</button>
          <br />
          <br />
          <a href="{{ route('admin.accountsv2.items', ['id' => $item->group_id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Quay lại ngay</a>
        </div>
      </form>
    </div>
  </div>

@endsection
@section('scripts')
  <script src="/_admin/libs/dropzone/dropzone-min.js"></script>
  <script>
    $(document).ready(function() {
      let queue = [];

      Dropzone.options.fileTypeValidation = {
        paramName: "file",
        maxFiles: 100,
        maxFilesize: 30,
        acceptedFiles: "image/*",
        headers: {
          'Authorization': 'Bearer {{ auth()->user()->access_token }}',
        },
        dictRemoveFile: 'Xóa',
        addRemoveLinks: true,
        autoProcessQueue: false,
        init: function() {
          let myDropzone = this;

          myDropzone.on("addedfiles", function(files) {
            queue.push(...files);
            processQueue();
          });

          myDropzone.on("complete", function(file) {
            if (file.xhr) {
              let response = file.xhr.response;

              if (isJson(response)) {
                let obj = JSON.parse(response);
                if (file.xhr.status == 200 && obj.data) {
                  let info = obj.data;

                  let elm = document.querySelector('#list_image');
                  elm.value += `${info.path}\n`;

                  let count = document.querySelector('#image-count');
                  count.textContent = parseInt(count.textContent) + 1;
                } else {
                  Swal.fire('Thất bại', obj.message || 'Unknown error', 'error');
                  setFileError(file);
                }
              } else {
                console.log('Invalid JSON response');
                setFileError(file);
              }
            }
            queue.shift();
            processQueue();
          });

          myDropzone.on("removedfile", function(file) {
            document.querySelector(`.image-uploaded [data-file-id="${file.upload.uuid}"]`).remove();
            let _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
          });

          myDropzone.on("error", function(file, message) {
            console.log(message);
            setFileError(file, message);
          });

          function processQueue() {
            if (queue.length > 0 && myDropzone.getUploadingFiles().length === 0) {
              myDropzone.processFile(queue[0]);
            }
          }

          function isJson(str) {
            try {
              JSON.parse(str);
            } catch (e) {
              return false;
            }
            return true;
          }

          function setFileError(file, message = 'Invalid JSON or Server Error') {
            file.previewElement.classList.add("dz-error");
            let errorMsgElement = file.previewElement.querySelector("[data-dz-errormessage]");
            if (errorMsgElement) {
              errorMsgElement.textContent = message;
            }
          }
        }
      };

      let myDropzone = new Dropzone(".dropzone");
    });
  </script>
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

    const deleteImage = (id) => {
      $(`[data-file-id="${id}"]`).remove();
    }
  </script>
@endsection
