@extends('admin.layouts.master')
@section('title', 'Admin: Accounts Item')
@section('css')
  <link rel="stylesheet" href="/_admin/libs/dropzone/dropzone.css">
@endsection
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý sản phẩm của nhóm "{{ $group->name }}"</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table-bordered table-stripped text-nowrap datatable table">
          <thead>
            <tr>
              <th>#</th>
              <th>Ưu Tiên</th>
              <th>Thao tác</th>
              <th>Tên sản phẩm</th>
              <th>Mã sản phẩm</th>
              <th>Giá bán</th>
              <th>Giá nhập</th>
              <th>% Giảm giá</th>
              <th>Ảnh sản phẩm</th>
              <th>Trạng thái</th>
              <th>Doanh thu</th>
              <th>Ngày thêm</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($group->items as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->priority }}</td>
                <td>
                  <a href="{{ route('admin.accountsv2.items.show', ['id' => $item->id]) }}" class="badge bg-primary-gradient text-white me-1"><i class="fa fa-pencil"></i> Sửa</a>
                  <a href="{{ route('admin.accountsv2.resources', ['id' => $item->id]) }}" class="badge bg-info-gradient text-white me-1"><i class="fa fa-pencil"></i> Data</a>
                  <a href="javascript:deleteRow({{ $item->id }})" class="badge bg-danger-gradient text-white me-1"><i class="fa fa-trash"></i> xoá</a>
                </td>
                <td>{{ $item->name }}</td>
                <td>#{{ $item->code }}</td>
                <td>{{ Helper::formatCurrency($item->price) }}</td>
                <td>{{ Helper::formatCurrency($item->cost) }}</td>
                <td>{{ $item->discount }}%</td>
                <td>
                  <a href="{{ asset($item->image) }}" target="_blank">
                    <img src="{{ asset($item->image) }}" width="22" height="22">
                  </a>
                </td>
                <td>
                  @if ($item->status)
                    @if ($item->amount === 0)
                      <span class="text-danger">Hết Hàng</span>
                    @else
                      <span class="text-success">Còn {{ $item->amount }} nick</span>
                    @endif
                  @else
                    <span class="text-danger">Ngưng bán</span>
                  @endif
                </td>
                <td>{{ Helper::formatCurrency($item->revenue) }}</td>
                <td>{{ $item->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <a href="{{ route('admin.accountsv2.groups', ['id' => $group->category_id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Quay lại danh sách nhóm</a>
    </div>
  </div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thêm sản phẩm vào nhóm</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.accountsv2.items.store', ['id' => $group->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Tên sản phẩm</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Tên sản phẩm cần bán" required>
        </div>
        <div class="mb-3">
          <label for="priority" class="form-label">Ưu tiên</label>
          <input type="number" id="priority" name="priority" class="form-control" value="0" required>
          <i>Số ưu tiên lớn thì nó hiện ở đầu</i>
        </div>
        <div class="mb-3">
          <label for="code" class="form-label">Mã sản phẩm</label>
          <input type="number" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
          <i>Mã này chỉ có hiệu lực khi bạn nhập 1 tài khoản, nếu lớn hơn 1 tài khoản hệ thống sẽ random tất cả</i>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Ảnh sản phẩm</label>
          <input type="file" class="form-control" id="image" name="image" required>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="price" class="form-label">Giá bán</label>
            <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
          </div>
          <div class="col-md-4">
            <label for="cost" class="form-label">Giá nhập</label>
            <input type="text" class="form-control" id="cost" name="cost" value="{{ old('cost') }}" required>
          </div>
          <div class="col-md-4">
            <label for="discount" class="form-label">% Giảm giá</label>
            <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount', 0) }}" required>
          </div>
        </div>
        <div class="mb-3 row">
          <div class="col-md-4">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-control" id="status" name="status" required>
              <option value="1">Đang bán</option>
              <option value="0">Ngưng bán</option>
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
              <input type="number" class="form-control" id="is_bulk" name="is_bulk" value="{{ old('is_bulk', 1) }}" required>
            </div>
          @endif
        </div>
        <div class="mb-3">
          <label for="list_item" class="form-label">Danh sách tài khoản</label>
          <textarea class="form-control" id="list_item" name="list_item" rows="3" required>{{ old('list_item') }}</textarea>
          <i>* Nhập nhiều hơn 1 tài khoản thì nó sẽ nhân bản sản phẩm giống nhau, chủ yếu dùng cho mua random</i>
          <br />
          <i>* Mỗi dòng là 1 sản phẩm, có thể nhập USERNAME|PASSWORD|2FA, nội dung này sẽ hiển thị cho người mua</i>
        </div>
        <div class="mb-3">
          <label for="highlights" class="form-label">Chi tiết sản phẩm</label>
          <textarea class="form-control" id="highlights" name="highlights" rows="3" required>{{ old('highlights') }}</textarea>
          <i>Chi tiết nhập như sau: NAME:VALUE => Cấp độ:20, hoặc => Cấp độ 20</i>
        </div>

        {{-- <div class="mb-3">
          <label for="description" class="form-label">Mô tả sản phẩm</label>
          <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
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
        </div> --}}
        <div class="image-uploaded mb-3"></div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary">Tạo sản phẩm</button>
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

    const deleteRow = async (id) => {
      const confirmDelete = await Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa?',
        text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
      });

      if (!confirmDelete.isConfirmed) return;

      $showLoading();

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.accountsv2.items.delete') }}', {
          id
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }
  </script>
@endsection
