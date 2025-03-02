@extends('admin.layouts.master')
@section('title', 'Admin: Items Category')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý chuyên mục</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table table-bordered table-stripped text-center datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Ưu tiên</th>
              <th>Thao tác</th>
              <th>Tên chuyên mục</th>
              <th>Số nhóm con</th>
              <th>Người tạo</th>
              <th>Trạng thái</th>
              <th>Thời gian</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($categories as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->priority }}</td>
                <td>
                  <div class="d-flex">
                    <a href="javascript:void(0)" class="badge bg-primary-gradient text-white me-1" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $item->id }}"><i class="fa fa-edit"></i> sửa</a>
                    <a href="{{ route('admin.items.groups', ['id' => $item->id]) }}" class="badge bg-success-gradient text-white me-1"><i class="fa fa-eye"></i> xem</a>
                    <a href="javascript:deleteRow({{ $item->id }})" class="badge bg-danger-gradient me-1"><i class="fa fa-trash"></i> xoá</a>
                  </div>
                </td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->groups()->count() }} nhóm</td>
                <td>{{ $item->username }}</td>
                <td>
                  @if ($item->status == 1)
                    <span class="text-success">Hoạt động</span>
                  @else
                    <span class="text-danger">Tạm đóng</span>
                  @endif
                </td>
                <td>{{ $item->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create"><i class="fa fa-edit"></i> Thêm chuyên mục mới</button>
    </div>
  </div>

  @foreach ($categories as $item)
    <div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cập nhật chuyên mục #{{ $item->id }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.items.categories.update', ['id' => $item->id]) }}" method="POST" class="default-form">
              @csrf
              <div class="mb-3">
                <label for="priority" class="form-label">Ưu tiên</label>
                <input type="number" class="form-control" id="priority" name="priority" value="{{ $item->priority }}">
                <i>Số ưu tiên lớn thì nó hiện ở đầu</i>
              </div>
              <div class="mb-3">
                <label for="name" class="form-label">Tên chuyên mục</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên chuyên mục" value="{{ $item->name }}" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" id="status" name="status" required>
                  <option value="1">Hoạt động</option>
                  <option value="0">Tạm đóng</option>
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

  <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm thông tin mới</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.items.categories.store') }}" method="POST" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="priority" class="form-label">Ưu tiên</label>
              <input type="number" class="form-control" id="priority" name="priority" value="0">
              <i>Số ưu tiên lớn thì nó hiện ở đầu</i>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Tên chuyên mục</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên chuyên mục" required>
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
        } = await axios.post('{{ route('admin.items.categories.delete') }}', {
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
