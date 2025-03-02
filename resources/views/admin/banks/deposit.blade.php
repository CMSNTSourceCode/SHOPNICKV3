@extends('admin.layouts.master')
@section('title', 'Admin: Nạp Tiền Qua Ngân Hàng')
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
    @foreach ($stats['banks'] as $key => $value)
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <div class="text-center card-stats">
              <h3>{{ number_format($value) }}</h3>
              <h5>{{ $stats['t_banks'][$key] ?? $key }}</h5>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div>
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Danh sách giao dịch</div>
      </div>
      <div class="card-body">
        <div class="mb-2">
          <form id="filter" onsubmit="$('#basic-1').DataTable().ajax.reload(); return false;">
            <div class="mb-3 row justify-content-center">
              <div class="col-md-2">
                <label for="username" class="form-label">Tài khoản</label>
                <input type="text" class="form-control" id="username" name="username">
              </div>
              <div class="col-md-2">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
              </div>
              <div class="col-md-2">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
              </div>
              <div class="col-md-2">
                <label for="domain" class="form-label">Tên miền</label>
                <input type="text" class="form-control" id="domain" name="domain">
              </div>
              <div class="col-md-2 text-center">
                <label for="amount" class="form-label">Tổng Tiền</label>
                <input type="text" class="form-control" disabled id="total_amount">
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-primary">Lọc dữ liệu</button>
            </div>
          </form>
        </div>
        <div class="table-responsive theme-scrollbar p-2">
          <table class="display table table-bordered table-stripped text-nowrap" id="basic-1">
            <thead>
              <tr>
                <th>#</th>
                <th>Tài khoản</th>
                <th>Giao dịch</th>
                <th>Mã giao dịch</th>
                <th>Số dư trước</th>
                <th>Số tiền</th>
                <th>Số dư sau</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
                <th>Tên miền</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      //DataTable
      $("#basic-1").DataTable({
        order: [0, 'desc'],
        responsive: false,
        lengthMenu: [
          [10, 50, 100, 200, 500, 1000, 2000, 10000, -1],
          [10, 50, 100, 200, 500, 1000, 2000, 10000, "All"]
        ],
        language: {
          searchPlaceholder: 'Tìm kiếm...',
          sSearch: '',
          lengthMenu: '_MENU_',
        },
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/admin/transactions',
          async: true,
          type: 'GET',
          dataType: 'json',
          headers: {
            'Authorization': 'Bearer ' + userData.access_token,
            'Accept': 'application/json',
          },
          data: function(data) {
            let payload = {}
            // default params
            payload.type = 'deposit-bank';
            payload.username = $('#username').val();
            payload.start_date = $('#start_date').val();
            payload.end_date = $('#end_date').val();
            payload.domain = $('#domain').val();
            // set params
            payload.page = data.start / data.length + 1;
            payload.limit = data.length;
            payload.search = data.search.value;
            payload.sort_by = data.columns[data.order[0].column].data;
            payload.sort_type = data.order[0].dir;
            // return json
            return payload;
          },
          error: function(xhr) {
            Swal.fire('Thất bại', $catchMessage(xhr), 'error')
          },
          dataFilter: function(data) {
            let json = JSON.parse(data);
            if (json.status) {
              json.recordsTotal = json.data.meta.total
              json.recordsFiltered = json.data.meta.total
              json.data = json.data.data

              $('#total_amount').val($formatCurrency(json.data.reduce((a, b) => parseFloat(a) + parseFloat(b.amount), 0)))

              return JSON.stringify(json); // return JSON string
            } else {
              Swal.fire('Thất bại', json.message, 'error')
              return JSON.stringify({
                recordsTotal: 0,
                recordsFiltered: 0,
                data: []
              }); // return JSON string
            }
          }
        },
        columns: [{
          data: 'id',
        }, {
          data: 'username',
          render: function(data, type, row) {
            return `<a href="/admin/users/edit/${row.user_id}">${data}</a>`
          }
        }, {
          data: 'type',
        }, {
          data: 'code',
        }, {
          data: 'balance_before',
          render: function(data, type, row) {
            return $formatCurrency(data)
          }
        }, {
          data: 'amount',
          render: function(data, type, row) {
            return row.prefix + ' ' + $formatCurrency(data)
          }
        }, {
          data: 'balance_after',
          render: function(data, type, row) {
            return $formatCurrency(data)
          }
        }, {
          data: 'content',
        }, {
          data: 'status',
          className: 'text-center',
          render: function(data, type, row) {
            return `<span class="text-primary">${data?.toUpperCase()}</span>`
          }
        }, {
          data: 'created_at',
          render: function(data, type, row) {
            return $formatDate(data)
          }
        }, {
          data: 'domain'
        }],
        columnDefs: [{
          orderable: false,
          targets: [1]
        }],
      })
    })
  </script>
@endsection
