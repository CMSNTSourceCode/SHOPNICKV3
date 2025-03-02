@extends('admin.layouts.master')
@section('title', 'Admin: Nạp Tiền Bằng Thẻ Cào')
@section('css')
  <style>
    .card-stats h3 {
      color: #00b44b;
      font-size: 36px;
    }

    .card-stats h5 {
      color: #54030e;
      font-size: 18px;
    }
  </style>
@endsection
@section('content')
  <div class="row">
    @foreach ($stats['cards'] as $key => $value)
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <div class="text-center card-stats">
              <h3>{{ number_format($value) }}</h3>
              <h5>{{ $stats['t_cards'][$key] ?? $key }}</h5>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div>
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Danh sách thẻ cào</div>
      </div>
      <div class="card-body">
        <div class="table-responsive theme-scrollbar p-2">
          <table class="display table table-bordered table-stripped text-center datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>API ID</th>
                <th>Tài khoản</th>
                <th>Loại thẻ</th>
                <th>Mã thẻ</th>
                <th>Serial thẻ</th>
                <th>Mệnh giá</th>
                <th>Thực nhận</th>
                <th>Nội dung</th>
                <th>Kênh gạch</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cards as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->order_id }}</td>
                  <td>{{ $item->username }}</td>
                  <td>{{ $item->type }}</td>
                  <td>{{ $item->code }}</td>
                  <td>{{ $item->serial }}</td>
                  <td>{{ Helper::formatCurrency($item->value) }}</td>
                  <td>{{ Helper::formatCurrency($item->amount) }}</td>
                  <td>{{ $item->content }}</td>
                  <td>{{ $item->channel_charge }}</td>
                  <td>{!! Helper::formatStatus($item->status) !!}</td>
                  <td>{{ $item->created_at }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
