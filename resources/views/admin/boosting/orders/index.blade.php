@extends('admin.layouts.master')
@section('title', 'Admin: Items Orders')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý đơn hàng cày thuê</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table table-bordered table-stripped text-center datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Thao tác</th>
              <th>Mã đơn</th>
              <th>Dịch vụ</th>
              <th>Trong nhóm</th>
              <th>Tài khoản</th>
              <th>Thanh toán</th>
              <th>Trạng thái</th>
              <th>Ghi chú</th>
              <th>Thời gian</th>
              <th>Cập nhật</th>

              <th>CTV Nhận</th>
              <th>Ngày nhận</th>
              <th>Ngày xong</th>
              <th>Tiền nhận</th>
              <th>-</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>
                  <a href="javascript:void(0)" class="shadow btn btn-primary btn-xs sharp me-1" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $item->id }}"><i class="fa fa-edit"></i> sửa</a>
                </td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->group?->name ?? '-' }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ Helper::formatCurrency($item->payment) }}</td>
                <td>{!! Helper::formatStatus($item->status) !!}</td>
                <td>{{ $item->order_note }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->updated_at }}</td>

                <td>{{ $item->assigned_to ?? '0 có' }}</td>
                <td>{{ $item->assigned_at }}</td>
                <td>{{ $item->assigned_completed ?? '-' }}</td>
                <td>{{ $item->assigned_payment > 0 ? Helper::formatCurrency($item->assigned_payment) : '-' }}</td>
                <td>
                  @if ($item->assigned_status === 'Completed')
                    <span class="badge bg-success">Đã nhận</span>
                  @elseif ($item->assigned_status === 'WaitPayment')
                    <span class="badge bg-warning">Chờ duyệt</span>
                  @else
                    <span class="badge bg-danger">Chưa nhận</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer"></div>
  </div>

  @foreach ($orders as $item)
    <div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cập nhật nhóm #{{ $item->id }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.boosting.orders.update', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
              @csrf
              <div class="mb-3">
                <label for="name" class="form-label">Dịch vụ</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $item->name }}" disabled>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="code" class="form-label">Mã đơn</label>
                  <input type="text" id="code" name="code" class="form-control" value="{{ $item->code }}" disabled>
                </div>
                <div class="col-md-6">
                  <label for="payment" class="form-label">Thanh toán</label>
                  <input type="text" id="payment" name="payment" class="form-control" value="{{ Helper::formatCurrency($item->payment) }}" disabled>
                </div>
              </div>
              @if ($item->type === 'addfriend')
                @php
                  $ingame = '';
                  foreach ($item->input_ingame as $ig) {
                      $ingame .= $ig . "\n";
                  }
                @endphp
                <div class="mb-3">
                  <label for="input_ingame" class="form-label">Danh sách In-Game</label>
                  <textarea class="form-control" id="input_ingame" name="input_ingame" rows="3" disabled>{{ $ingame }}</textarea>
                </div>
                <div class="mb-3">
                  <label for="input_user" class="form-label">Tài khoản nhận</label>
                  <input type="text" id="input_user" name="input_user" class="form-control" value="{{ $item->input_user ?? '-KHÔNG CÓ-' }}" disabled>
                </div>
              @else
                <div class="mb-3 row">
                  <div class="col-md-4">
                    <label for="input_user" class="form-label">Tài khoản</label>
                    <input type="text" id="input_user" name="input_user" class="form-control" value="{{ $item->input_user ?? '-KHÔNG CÓ-' }}" disabled>
                  </div>
                  <div class="col-md-4">
                    <label for="input_pass" class="form-label">Mật khẩu</label>
                    <input type="text" id="input_pass" name="input_pass" class="form-control" value="{{ $item->input_pass ?? '-KHÔNG CÓ-' }}" disabled>
                  </div>
                  <div class="col-md-4">
                    <label for="input_auth" class="form-label">Đăng nhập</label>
                    <input type="text" id="input_auth" name="input_auth" class="form-control" value="{{ $item->input_auth ?? '-KHÔNG CÓ-' }}" disabled>
                  </div>
                </div>
              @endif
              <div class="mb-3">
                <label for="input_extra" class="form-label">Ghi chú [Cookie / 2FA]</label>
                <textarea class="form-control" id="input_extra" name="input_extra" rows="3" readonly>{{ $item->input_extra }}</textarea>
              </div>
              <div class="mb-3">
                <label for="admin_note" class="form-label">Ghi chú admin</label>
                <textarea class="form-control" id="admin_note" name="admin_note" rows="3">{{ $item->admin_note }}</textarea>
              </div>
              <div class="mb-3">
                <label for="order_note" class="form-label">Ghi chú khách</label>
                <textarea class="form-control" id="order_note" name="order_note" rows="3" disabled>{{ $item->order_note }}</textarea>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status" required>
                  <option value="Pending" {{ $item->status === 'Pending' ? 'selected' : '' }}>Chờ xử lý</option>
                  <option value="Processing" {{ $item->status === 'Processing' ? 'selected' : '' }}>Đang xử lý</option>
                  <option value="Completed" {{ $item->status === 'Completed' ? 'selected' : '' }}>Hoàn thành</option>
                  <option value="Cancelled" {{ $item->status === 'Cancelled' ? 'selected' : '' }}>Đã hủy / Hoàn</option>
                </select>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary">Cập nhật</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endforeach
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
