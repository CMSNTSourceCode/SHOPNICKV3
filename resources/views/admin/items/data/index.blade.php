@extends('admin.layouts.master')
@section('title', 'Admin: Items Data')
@section('content')
  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Thêm sản phẩm vào nhóm</div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.items.data.store', ['id' => $group->id]) }}" method="POST" enctype="multipart/form-data" class="default-form">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Tên sản phẩm</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Tên sản phẩm cần bán" required>
        </div>
        <div class="mb-3">
          <label for="code" class="form-label">Mã sản phẩm</label>
          <input type="number" class="form-control" id="code" name="code" value="{{ old('code') }}">
          <i>Mã này chỉ có hiệu lực khi bạn nhập 1 sản phẩm, nếu lớn hơn 1 sản phẩm hệ thống sẽ random tất cả</i>
        </div>
        <div class="mb-3">
          <label for="type" class="form-label">Thông tin cần</label>
          <select name="type" id="type" class="form-control" required>
            <option value="">- Chọn Thông Tin -</option>
            <option value="addfriend">Kết Bạn Với Shop</option>
            <option value="user_pass">Tài Khoản + Mật Khẩu</option>
          </select>
        </div>
        <div class="mb-3" id="ingame_group">
          <label for="ingame_id" class="form-label">Danh sách in-games</label>
          <select name="ingame_id" id="ingame_id" class="form-control" readonly>
            <option value="">- Chọn Ingame -</option>
            @foreach ($ingames as $item)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
          </select>
          <small>Hệ thống tự động</small>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Ảnh sản phẩm</label>
          <input type="file" class="form-control" id="image" name="image" required>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="price" class="form-label">Giá sản phẩm</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
          </div>
          <div class="col-md-4">
            <label for="discount" class="form-label">% Giảm giá</label>
            <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount', 0) }}" required>
          </div>
          <div class="col-md-4">
            <label for="robux" class="form-label">Giá trị robux</label>
            <input type="number" class="form-control" id="robux" name="robux" value="{{ old('robux', 0) }}" required>
            <small class="text-danger">* Nếu nhập robux thì không cần nhập giá | tổng tiền = rate * robux</small>
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
          <label for="highlights" class="form-label">Chi tiết sản phẩm</label>
          <textarea class="form-control" id="highlights" name="highlights" rows="3" required>{{ old('highlights') }}</textarea>
          <i>Có thể nhập danh sách nick game cần kết bạn trước khi giao dịch...</i>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Mô tả sản phẩm</label>
          <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
        </div>
        <div class="mb-3 text-center">
          <button class="btn btn-primary">Tạo sản phẩm</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Quản lý sản phẩm của nhóm "{{ $group->name }}"</div>
    </div>
    <div class="card-body">
      <div class="table-responsive theme-scrollbar">
        <table class="display table-bordered table-stripped text-nowrap datatable1 table text-center">
          <thead>
            <tr>
              <th data-orderable="false" width="10">
                <input type="checkbox" name="checked_all">
              </th>
              <th>ID</th>
              <th>Thao tác</th>
              <th>Yêu cầu</th>
              <th>Mã sản phẩm</th>
              <th>Tên Sản Phẩm</th>
              <th>Giá bán</th>
              <th>% Giảm giá</th>
              <th>$R Robux</th>
              <th>Ảnh sản phẩm</th>
              <th>Trạng thái</th>
              <th>Ngày thêm</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($group->data as $item)
              <tr>
                <th>
                  <input type="checkbox" name="checked_ids[]" value="{{ $item->id }}">
                </th>
                <td>{{ $item->id }}</td>
                <td>
                  <a href="{{ route('admin.items.data.show', ['id' => $item->id]) }}" class="badge bg-primary-gradient me-1"><i class="fa fa-pencil"></i> sửa</a>
                  <a href="javascript:deleteRow({{ $item->id }})" class="badge bg-danger-gradient me-1"><i class="fa fa-trash"></i> xoá</a>
                </td>
                <td>{{ $item->type }}|{{ $item->ingames?->name ?? '-' }}</td>
                <td>#{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ Helper::formatCurrency($item->price) }}</td>
                <td>{{ $item->discount }}%</td>
                <td>{{ $item->robux ? '$R' . $item->robux : '-' }}</td>
                <td>
                  <a href="{{ asset($item->image) }}" target="_blank"><img src="{{ asset($item->image) }}" width="40" height="20"></a>
                </td>
                <td>{!! $item->status === true ? '<span class="text-success">Đang Bán</span>' : '<span class="text-danger">Ngưng Bán</span>' !!}</td>
                <td>{{ $item->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
      <div class="text-start">
        <button class="btn btn-primary action-ids" disabled onclick="updateList()"><i class="fa fa-pencil"></i> Cập nhật <span class="checked-ids">0</span> sản phẩm</button>
      </div>
      <div class="text-end">
        <a href="{{ route('admin.items.groups', ['id' => $group->category_id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Quay lại danh sách nhóm</a>
      </div>
    </div>
  </div>

@endsection
@section('scripts')
  <script>
    $("[name=checked_all]").change(function(e) {
      if ($(this).is(":checked")) {
        $("[name='checked_ids[]']").prop("checked", true)
      } else {
        $("[name='checked_ids[]']").prop("checked", false)
      }
    })

    function getIds() {
      let ids = []
      $("[name='checked_ids[]']:checked").each(function() {
        ids.push($(this).val())
      })
      return ids
    }

    // find class actions-ids set disabled with getIds() <script 0, and set length checked-ids
    function setActions() {
      let ids = getIds()
      console.log(ids)
      if (ids.length > 0) {
        $(".action-ids").prop("disabled", false)
        $(".checked-ids").text(ids.length)
      } else {
        $(".action-ids").prop("disabled", true)
        $(".checked-ids").text(0)
      }
    }

    $(document)
      .ready(function() {
        setActions();
      })
      .on('change', 'input[name="checked_all"]:enabled', function() {
        setActions();
      })
      .on('change', 'input[name="checked_ids[]"]:enabled', function() {
        setActions();
      })
  </script>
  <script>
    const updateList = () => {
      let ids = getIds();

      if (ids.length === 0) {
        return Swal.fire('Thông báo', 'Vui lòng chọn ít nhất 1 sản phẩm để cập nhật', 'warning');
      }

      Swal.fire({
        title: 'Cập nhật sản phẩm',
        html: `
      <div class="row mb-3">
          <div class="col-md-6">
          <label class="form-label">Giá</label>
          <input type="number" id="swal-input-price" class="form-control" placeholder="Nhập giá">
        </div>
        <div class="col-md-6">
          <label class="form-label">Giảm giá (%)</label>
          <input type="number" id="swal-input-discount" class="form-control" min="0" max="100" placeholder="Nhập % giảm giá">
        </div>
      </div>
    `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Cập nhật',
        cancelButtonText: 'Hủy',
        preConfirm: () => {
          const rate = Swal.getPopup().querySelector('#swal-input-rate').value;
          const price = Swal.getPopup().querySelector('#swal-input-price').value;
          const discount = Swal.getPopup().querySelector('#swal-input-discount').value;

          if (!price && !discount && !rate) {
            Swal.showValidationMessage('Vui lòng nhập ít nhất một trường');
            return false;
          }

          return {
            rate: rate ? parseInt(rate) : null,
            price: price ? parseInt(price) : null,
            discount: discount ? parseInt(discount) : null
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const {
            rate,
            price,
            discount
          } = result.value;

          // Show loading
          Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // Tạo payload với dữ liệu đã lấy được
          const payload = {
            ids: ids
          };

          if (rate !== null) payload.rate = rate;
          if (price !== null) payload.price = price;
          if (discount !== null) payload.discount = discount;

          console.log('Sending payload:', payload);

          // Send request to server
          axios.post('/admin/items/data/update-list', payload)
            .then(response => {
              if (response.data.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Thành công',
                  text: response.data.message,
                  confirmButtonText: 'Đóng'
                }).then(() => {
                  if (typeof table !== 'undefined') {
                    table.ajax.reload();
                  } else {
                    window.location.reload();
                  }
                });
              } else {
                throw new Error(response.data.message);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: error.response?.data?.message || error.message || 'Đã có lỗi xảy ra',
                confirmButtonText: 'Đóng'
              });
            });
        }
      });
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
        } = await axios.post('{{ route('admin.items.data.delete') }}', {
          id
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }

    $(document).ready(function() {
      $('#robux').change((value) => {
        if (value && value.target.value > 0) {
          $('#price').val(0).attr('readonly', true);
        } else {
          $('#price').val(0).attr('readonly', false);
        }
      })

      $("#type").change(value => {
        if (value.target.value === 'addfriend') {
          $('#ingame_group').show();
        } else {
          $('#ingame_group').hide();
        }
      })

      $("#type").trigger('change');
    })

    $('.datatable1').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      response: false,
      order: [
        [1, 'desc']
      ],
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, 100, 500, 1000, 5000, -1],
        [10, 25, 50, 100, 500, 1000, 5000, 'All']
      ]
    });
  </script>
@endsection
