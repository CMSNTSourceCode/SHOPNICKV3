@extends('admin.layouts.master')
@section('title', 'Admin: Accounts Item In Stock')
@section('css')
  <link rel="stylesheet" href="/_admin/libs/dropzone/dropzone.css">
@endsection
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Danh sách tài khoản trong kho - <a href="?sold=1">Đã Bán</a> / <a href="?sold=0">Chưa Bán</a> / <a href="?">Tất Cả</a></div>
    </div>
    <div class="card-body">
      <div>
        <form id="filter" method="GET">
          <div class="mb-3 row">
            <div class="col-md-3">
              <label for="group" class="form-label">Nhóm</label>
              <select name="group" id="group" class="form-select">
                <option value="">Tất cả</option>
                @foreach ($groups as $group)
                  <option value="{{ $group->code }}" @if (request()->input('group') == $group->id) selected @endif>{{ $group->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label for="sold" class="form-label">Trạng thái</label>
              <select name="sold" id="sold" class="form-select">
                <option value="">Tất cả</option>
                <option value="1" @if (request()->input('sold') == 1) selected @endif>Đã bán</option>
                <option value="0" @if (request()->input('sold') == 0) selected @endif>Chưa bán</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="buyer_name" class="form-label">Người mua</label>
              <input type="text" class="form-control" id="buyer_name" name="buyer_name" value="{{ request()->input('buyer_name') }}">
            </div>
            <div class="col-md-3">
              <label for="username" class="form-label">Tài khoản</label>
              <input type="text" class="form-control" id="username" name="username" value="{{ request()->input('username') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="start_date" class="form-label">Ngày bắt đầu</label>
              <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request()->input('start_date') }}">
            </div>
            <div class="col-md-3">
              <label for="end_date" class="form-label">Ngày kết thúc</label>
              <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->input('end_date') }}">
            </div>
            <div class="col-md-3">
              <label for="domain" class="form-label">Tên miền</label>
              <input type="text" class="form-control" id="domain" name="domain" value="{{ request()->input('domain') }}">
            </div>
          </div>
          <div class="text-center mb-3">
            <button type="submit" class="btn btn-primary">Lọc dữ liệu</button>
            <button type="button" class="btn btn-danger ids-action" onclick="deleteList()"><i class="fa fa-trash"></i> Xoá <span class="checked-count">0</span> sản phẩm</button>
          </div>
        </form>
      </div>

      <div class="table-responsive theme-scrollbar p-2">
        <table class="display table-bordered table-stripped text-nowrap datatable1_2 table text-center">
          <thead>
            <tr>
              <th>#</th>
              <th>Sản phẩm</th>
              <th>Thao tác</th>
              <th>Tài khoản</th>
              <th>Người mua</th>
              <th>Ngày mua</th>
              <th>Thanh toán</th>
              <th>Mã đơn hàng</th>
              <th data-orderable="false">Trạng thái</th>
              <th>Ngày thêm</th>
              <th>Tên miền</th>
            </tr>
          </thead>
          <tbody>
            @foreach ([] as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>
                  <a href="{{ route('admin.accountsv2.items.show', ['id' => $item->parent?->id ?? '-1']) }}">#{{ $item->parent?->name }}</a>
                </td>
                <td>
                  <a href="{{ route('admin.accountsv2.resources', ['id' => $item->parent?->id]) }}" class="badge bg-primary-gradient text-white me-1"><i class="fa fa-pencil"></i> sửa</a>
                </td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->buyer_name ?? '-' }}</td>
                <td>{{ $item->buyer_date ?? '-' }}</td>
                <td><span class="text-danger">{{ Helper::formatCurrency($item->buyer_paym ?? 0) }}</span></td>
                <td>{{ $item->buyer_code ?? '-' }}</td>
                <td>{!! $item->buyer_name !== null ? '<span class="text-success">Đã bán</span>' : '<span class="text-danger">Chưa bán</span>' !!}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->domain }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    // checkbox table
    $("#check_all").click(function() {
      if ($(this).is(":checked")) {
        $(".check_item").prop("checked", true);
      } else {
        $(".check_item").prop("checked", false);
      }
    });

    $(".check_item").click(function() {
      if ($(".check_item:checked").length == $(".check_item").length) {
        $("#check_all").prop("checked", true);
      } else {
        $("#check_all").prop("checked", false);
      }
    });

    const getIds = () => {
      let ids = [];
      $(".check_item:checked").each(function() {
        ids.push($(this).val());
      });
      return ids;
    }

    // event to class checked-count, checked-ids
    const updateChecked = () => {
      let ids = getIds();
      $(".checked-count").text(ids.length);

      if (ids.length > 0) {
        $(".ids-action").removeClass('disabled btn-disabled')
      } else {
        $(".ids-action").addClass('disabled btn-disabled')
      }
    }

    $(".check_item").click(function() {
      updateChecked();
    });

    $("#check_all").click(function() {
      updateChecked();
    });

    updateChecked();

    const deleteList = async () => {
      const ids = getIds()

      if (ids.length === 0) {
        Swal.fire('Thất bại', 'Vui lòng chọn ít nhất 1 tài khoản để xoá', 'error')
        return
      }

      const confirm = await Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa?',
        text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
      })

      if (!confirm.isConfirmed) return

      $showLoading()

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.accounts.items.delete-list') }}', {
          ids: ids
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }

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
        } = await axios.post('{{ route('admin.accounts.items.delete') }}', {
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

  <script>
    $(document).ready(function() {
      //DataTable
      $(".datatable1_2").DataTable({
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
          url: '/api/admin/data/accounts-v2',
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
            payload.group = $("#group").val()
            payload.sold = $("#sold").val()
            payload.buyer_name = $("#buyer_name").val()
            payload.username = $("#username").val()
            payload.start_date = $("#start_date").val()
            payload.end_date = $("#end_date").val()
            payload.domain = $("#domain").val()


            // set params
            payload.page = data.start / data.length + 1;
            payload.limit = data.length;
            payload.search = data.search.value || '{{ request()->input('search', '') }}';
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
          data: 'id'
        }, {
          data: 'parent.name'
        }, {
          data: 'id',
          render: function(data, type, row) {
            return `<a href="/admin/accountsv2/resources/edit/${row.id}" class="badge bg-primary-gradient text-white me-1"><i class="fa fa-pencil"></i> sửa</a>`
          }
        }, {
          data: 'username',
          render: function(data, type, row) {
            return data !== null ? data : '-'
          }
        }, {
          data: 'buyer_name',
          render: function(data, type, row) {
            return data !== null ? data : '-'
          }
        }, {
          data: 'buyer_date',
          render: function(data, type, row) {
            return data !== null ? $formatDate(data) : '-'
          }
        }, {
          data: 'buyer_paym',
          render: function(data, type, row) {
            return `<span class="text-danger">${$formatCurrency(data)}</span>`
          }
        }, {
          data: 'buyer_code',
          render: function(data, type, row) {
            return data !== null ? data : '-'
          }
        }, {
          data: 'buyer_name',
          render: function(data, type, row) {
            return data !== null ? '<span class="text-success">Đã bán</span>' : '<span class="text-danger">Chưa bán</span>'
          }
        }, {
          data: 'created_at',
          render: function(data, type, row) {
            return data !== null ? $formatDate(data) : '-'
          }
        }, {
          data: 'domain',
          render: function(data, type, row) {
            return data !== null ? data : '-'
          }
        }],
        columnDefs: [{
          orderable: false,
          targets: [0]
        }],
      })

      // filter changed
      $("#filter").on('submit', function(e) {
        e.preventDefault();
        $(".datatable1_2").DataTable().ajax.reload();
      })
    })
  </script>
@endsection
