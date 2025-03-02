@extends('admin.layouts.master')
@section('title', 'Admin: Spin Quest Management')
@section('content')
  <div class="alert alert-danger">* Đơn vị phần thưởng cấu hình tại <a href="{{ route('admin.settings.general') }}" target="_blank">đây</a></div>
  <div class="alert alert-danger">* Loại phần thưởng cấu hình tại <a href="{{ route('admin.inventories.vars') }}" target="_blank">đây</a></div>
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Danh sách vòng quay</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table table-bordered table-stripped text-center datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Thao Tác</th>
              <th>Tên Vòng</th>
              <th>Loại Thưởng</th>
              <th>Giá 1 / Lượt</th>
              <th>Ảnh Bìa</th>
              <th>Ảnh Vòng Quay</th>
              <th>Trạng thái</th>
              <th>Thời gian</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($spinQuests as $spinQuest)
              <tr>
                <td>{{ $spinQuest->id }}</td>
                <td>
                  <a class="badge bg-danger-gradient" href="{{ route('admin.games.spin-quest.show', $spinQuest->id) }}">Sửa</a>
                </td>
                <td>{{ $spinQuest->name }}</td>
                <td>{{ $spinQuest->inventoryVar->name ?? '-' }}</td>
                <td>{{ Helper::formatCurrency($spinQuest->price) }}</td>
                <td>
                  <a href="{{ asset($spinQuest->cover) }}" target="_blank">XEM</a>
                </td>
                <td>
                  <a href="{{ asset($spinQuest->image) }}" target="_blank">XEM</a>
                </td>
                <td>
                  @if ($spinQuest->status == 1)
                    <span class="badge bg-success">Hoạt động</span>
                  @else
                    <span class="badge bg-danger">Không hoạt động</span>
                  @endif
                </td>
                <td>{{ $spinQuest->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-primary-gradient" data-bs-toggle="modal" data-bs-target="#modal-create">Thêm vòng quay mới</button>
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
          <form action="{{ route('admin.games.spin-quest.store') }}" method="POST" enctype="multipart/form-data" class="default-form">
            @csrf
            <div class="mb-3">
              <label for="cover" class="form-label">Ảnh Bìa</label>
              <input class="form-control" type="file" id="cover" name="cover" required>
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Ảnh Vòng Quay</label>
              <input class="form-control" type="file" id="image" name="image" required>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Tên Vòng Quay</label>
              <input class="form-control" type="text" id="name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="price" class="form-label">Giá Mỗi Lượt</label>
              <input class="form-control" type="text" id="price" name="price" required>
            </div>
            <div class="mb-3">
              <label for="invar_id" class="form-label">Loại Thưởng</label>
              <select class="form-control" id="invar_id" name="invar_id">
                @foreach ($inventoryVars as $inventory)
                  <option value="{{ $inventory->id }}">ID {{ $inventory->id }}: {{ $inventory->name }} - {{ $inventory->unit }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="category_id" class="form-label">Danh Mục Trò Chơi</label>
              <select class="form-control" id="category_id" name="category_id">
                <option value="">Hiện Riêng Ở Đầu/Cuối</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">ID {{ $category->id }}: {{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select class="form-control" id="status" name="status">
                <option value="1">Hoạt động</option>
                <option value="0">Không hoạt động</option>
              </select>
            </div>
            <div class="mb-3">
              <button class="btn btn-danger-gradient w-100" type="submit">Thêm mới</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
