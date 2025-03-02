@extends('admin.layouts.master')
@section('title', 'Admin: Items Group')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý nhóm của chuyên mục "{{ $category->name }}"</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table table-bordered table-stripped text-center datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Ưu tiên</th>
              <th>Thao tác</th>
              <th>Ảnh / Icon</th>
              <th>Tên nhóm</th>
              <th>Sản phẩm</th>
              <th>Trạng thái</th>
              <th>Người tạo</th>
              <th>Thời gian</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($category->groups as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->priority }}</td>
                <td>
                  <a href="javascript:void(0)" class="shadow btn btn-primary btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $item->id }}"><i class="fa fa-edit"></i> sửa</a>
                  <a href="{{ route('admin.boosting.packages', ['id' => $item->id]) }}" class="shadow btn btn-info btn-xs sharp me-1"><i class="fa fa-eye"></i> Xem</a>
                  <a href="javascript:deleteRow({{ $item->id }})" class="shadow btn btn-danger btn-xs sharp me-1"><i class="fa fa-trash"></i> Xoá</a>
                </td>
                <td><img src="{{ $item->image }}" width="40"></td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->packages()->count() }}</td>
                <td>
                  @if ($item->status == 1)
                    <span class="text-success">Hoạt động</span>
                  @else
                    <span class="text-danger">Tạm đóng</span>
                  @endif
                </td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->created_at }}</td>
              </tr>

              <div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Cập nhật nhóm #{{ $item->id }}</h5>
                      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('admin.boosting.groups.update', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
                        @csrf
                        <div class="mb-3">
                          <label for="priority" class="form-label">Ưu tiên</label>
                          <input type="number" id="priority" name="priority" class="form-control" value="{{ $item->priority }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="image" class="form-label">Ảnh bìa</label>
                          <input type="file" id="image" name="image" class="form-control">
                        </div>
                        <div class="mb-3">
                          <label for="name" class="form-label">Tên nhóm</label>
                          <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên nhóm" value="{{ $item->name }}" required>
                        </div>
                        <div class="mb-3">
                          <label for="descr" class="form-label">Mô tả</label>
                          <textarea class="form-control" id="descr" name="descr" rows="3" placeholder="Nhập ghi chú">{{ $item->descr }}</textarea>
                        </div>
                        <div class="mb-3">
                          <label for="status" class="form-label">Trạng thái</label>
                          <select class="form-control" id="status" name="status" required>
                            <option value="1" @if ($item->status == 1) selected @endif>Hoạt động</option>
                            <option value="0" @if ($item->status == 0) selected @endif>Tạm đóng</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <button class="btn btn-primary w-100" type="submit">Cập nhật</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create"><i class="fa fa-edit"></i> Thêm nhóm mới</button>
      <a href="{{ route('admin.boosting.categories') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Quay lại danh sách chuyên mục</a>
    </div>
  </div>

  <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm thông tin mới</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.boosting.groups.store', ['id' => $category->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="category" class="form-label">Chuyên mục</label>
              <input type="text" id="category" class="form-control" value="{{ $category->name }}" readonly>
            </div>
            <div class="mb-3">
              <label for="priority" class="form-label">Ưu tiên</label>
              <input type="number" id="priority" name="priority" class="form-control" value="0" required>
              <i>Số ưu tiên lớn thì nó hiện ở đầu</i>
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Ảnh bìa</label>
              <input type="file" id="image" name="image" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Tên nhóm</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên nhóm" required>
            </div>
            <div class="mb-3">
              <label for="descr" class="form-label">Mô tả</label>
              <textarea class="form-control" id="descr" name="descr" rows="3" placeholder="Nhập ghi chú"></textarea>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select class="form-control" id="status" name="status" required>
                <option value="1">Hoạt động</option>
                <option value="0">Tạm đóng</option>
              </select>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary w-100" type="submit">Thêm mới</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
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
        } = await axios.post('{{ route('admin.boosting.groups.delete') }}', {
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
