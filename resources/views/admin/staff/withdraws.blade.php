@extends('admin.layouts.master')
@section('title', 'Admin: Withdraws Management')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý yêu cầu rút tiền cộng tác viên</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table table-bordered table-stripped text-center datatable">
          <thead>
            <tr>
              <td>#</td>
              <th>-</th>
              <td>Số tiền</td>
              <td>Ngân hàng</td>
              <td>Số tài khoản</td>
              <td>Trạng thái</td>
              <td>Thời gian</td>
              <td>Ghi chú</td>
            </tr>
          </thead>
          <tbody>
            @foreach ($withdraws as $value)
              <tr>
                <td>{{ $value->id }}</td>
                <td class="text-center">
                  <a href="javscript:void(0)" class="text-danger" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $value->id }}">Chi Tiết</a>
                </td>
                <td>{{ Helper::formatCurrency($value->amount) }}</td>
                <td>{{ $value->payment_info['bank_name'] ?? '' }}</td>
                <td>{{ $value->payment_info['account_name'] ?? '' }}</td>
                <td>{!! Helper::formatStatus($value->status) !!}</td>
                <td>{{ $value->created_at }}</td>
                <td>{{ $value->user_note }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @foreach ($withdraws as $value)
    @php
      $payment = $value->payment_info;
    @endphp
    <div class="modal fade" id="modal-edit-{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin #{{ $value->id }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.staff.withdraws.update') }}" method="POST" class="default-form">
              @csrf
              <input type="hidden" name="id" value="{{ $value->id }}">

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="bank_name" class="form-label">Ngân hàng</label>
                  <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $payment['bank_name'] ?? '-' }}" disabled>
                </div>
                <div class="col-md-6">
                  <label for="account_number" class="form-label">Số tài khoản</label>
                  <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $payment['account_number'] ?? '-' }}" disabled>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="account_name" class="form-label">Chủ tài khoản</label>
                  <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $payment['account_name'] ?? '-' }}" disabled>
                </div>
                <div class="col-md-6">
                  <label for="amount" class="form-label">Số tiền cần rút</label>
                  <input type="text" class="form-control" id="amount" name="amount" value="{{ Helper::formatCurrency($value->amount) }}" disabled>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="user_note" class="form-label">Ghi Chú</label>
                  <textarea class="form-control" id="user_note" name="user_note" rows="3">{{ $value->user_note }}</textarea>
                </div>
                <div class="col-md-6">
                  <label for="sys_note" class="form-label">Ghi Chú Hệ Thống</label>
                  <textarea class="form-control" id="sys_note" name="sys_note" rows="3" disabled>{{ $value->sys_note }}</textarea>
                </div>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Trạng Thái</label>
                <select class="form-control" id="status" name="status">
                  <option value="Pending" @if ($value->status === 'Pending') selected @endif>Đang Chờ</option>
                  <option value="Completed" @if ($value->status === 'Completed') selected @endif>Hoàn Thành</option>
                  <option value="Cancelled" @if ($value->status === 'Cancelled') selected @endif>Hủy Bỏ</option>
                </select>
              </div>
              <div class="text-center mb-3">
                @if ($payment)
                  <img
                    src="https://api.vietqr.io/{{ $payment['bank_name'] }}/{{ $payment['account_number'] }}/{{ $value->amount }}/tt hoa don {{ $value->order_id }}/vietqr_net_2.jpg?accountName={{ $payment['account_name'] }}"
                    width="300" alt="">
                @endif
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
@endsection
@section('scripts')

  <script>
    const setStatus = async (id, type) => {
      let simpleText = type === 'paid' ? 'đã thanh toán' : 'đã hủy'

      const confirm = await Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: `Chuyển trạng thái hoá đơn này sang ${simpleText}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy',
      })

      if (confirm.isConfirmed !== true)
        return

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.invoices.update') }}', {
          id,
          type
        })

        Swal.fire("Thành công!", result.message, "success").then(() => {
          window.location.reload()
        })

      } catch (error) {
        Swal.fire("Thất bại", $catchMessage(error), "error")
      }

    }
  </script>
@endsection
