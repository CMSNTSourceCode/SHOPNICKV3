@extends('admin.layouts.master')
@section('title', 'Admin: Withdraw Management')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Danh sách yêu cầu rút thưởng</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table table-bordered table-stripped text-center datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Thao Tác</th>
              <th>Người Rút</th>
              <th>Số Lượng</th>
              <th>Trạng Thái</th>
              <th>Ghi Chú</th>
              <th>Thời gian</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($histories as $history)
              <tr>
                <td>{{ $history->id }}</td>
                <td>
                  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $history->id }}" class="badge bg-danger-gradient text-white me-1"><i class="fa fa-edit"></i> sửa</a>
                </td>
                <td>{{ $history->username }}</td>
                <td>{{ number_format($history->value) }} {{ $history->unit }}</td>
                <td>{!! Helper::formatStatus($history->status) !!}</td>
                <td>{{ $history->user_note }}</td>
                <td>{{ $history->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @foreach ($histories as $history)
    <div class="modal fade" id="modal-edit-{{ $history->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cập nhật thông tin #{{ $history->id }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.games.withdraws.update', ['id' => $history->id]) }}" method="POST" class="default-form">
              @csrf
              <div class="mb-3">
                <label for="username" class="form-label">Người Rút</label>
                <input class="form-control" type="text" id="username" name="username" value="{{ $history->username }}" disabled>
              </div>
              <div class="mb-3">
                <label for="value" class="form-label">Số Lượng</label>
                <input class="form-control" type="text" id="value" name="value" value="{{ number_format($history->value) }} {{ $history->unit }}" disabled>
              </div>
              <div class="mb-3">
                <label for="user_note" class="form-label">Ghi Chú Khách</label>
                <textarea class="form-control" id="user_note" name="user_note" rows="3">{{ $history->user_note }}</textarea>
              </div>
              <div class="mb-3">
                <label for="admin_note" class="form-label">Ghi Chú Admin</label>
                <textarea class="form-control" id="admin_note" name="admin_note" rows="3">{{ $history->admin_note }}</textarea>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Trạng Thái</label>
                <select class="form-control" id="status" name="status">
                  <option value="Pending" {{ $history->status === 'Pending' ? 'selected' : '' }}>Đang chờ</option>
                  <option value="Completed" {{ $history->status === 'Completed' ? 'selected' : '' }}>Thành công</option>
                  <option value="Cancelled" {{ $history->status === 'Cancelled' ? 'selected' : '' }}>Huỷ bỏ</option>
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
@endsection
