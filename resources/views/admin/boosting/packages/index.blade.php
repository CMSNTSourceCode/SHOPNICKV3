@extends('admin.layouts.master')
@section('title', 'Admin: Boosting Package')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thêm sản phẩm vào nhóm</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.boosting.packages.store', ['id' => $group->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
        @csrf
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Tên sản phẩm cần bán" required>
          </div>
          <div class="col-md-6">
            <label for="price" class="form-label">Giá sản phẩm</label>
            <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Trạng thái</label>
          <select class="form-control" id="status" name="status" required>
            <option value="1">Đang bán</option>
            <option value="0">Chưa bán</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="descr" class="form-label">Mô tả sản phẩm</label>
          <textarea class="form-control" id="descr" name="descr" rows="3" required>{{ old('descr') }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary">Tạo sản phẩm</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h4>Quản lý sản phẩm của nhóm "{{ $group->name }}"</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table-bordered table-stripped text-nowrap datatable table text-center">
          <thead>
            <tr>
              <th>#</th>
              <th>Thao tác</th>
              <th>Giá bán</th>
              <th>Mã sản phẩm</th>
              <th>Tên Sản Phẩm</th>
              <th>Trạng thái</th>
              <th>Ngày thêm</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($group->packages as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>
                  <a href="{{ route('admin.boosting.packages.show', ['id' => $item->id]) }}" class="btn btn-primary btn-xs sharp me-1 shadow"><i class="fa fa-pencil"></i> sửa</a>
                  <a href="javascript:deleteRow({{ $item->id }})" class="btn btn-danger btn-xs sharp me-1 shadow"><i class="fa fa-trash"></i> xoá</a>
                </td>
                <td>{{ Helper::formatCurrency($item->price) }}</td>
                <td>#{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{!! $item->status === true ? '<span class="text-success">Đang Bán</span>' : '<span class="text-danger">Ngưng Bán</span>' !!}</td>
                <td>{{ $item->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <a href="{{ route('admin.boosting.groups', ['id' => $group->category_id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Quay lại danh sách nhóm</a>
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
        } = await axios.post('{{ route('admin.boosting.packages.delete') }}', {
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
