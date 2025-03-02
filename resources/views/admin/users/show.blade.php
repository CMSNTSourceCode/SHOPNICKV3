@extends('admin.layouts.master')
@section('title', 'Admin: User Detail')
@section('content')
  <style>
    .card-stats h3 {
      color: #9A3B3B;
      font-size: 36px;
    }

    .card-stats h6 {
      color: #9A3B3B;
      font-size: 18px;
    }
  </style>
  @php
    $affiliate = $user->affiliate;
  @endphp
  <section>
    @if ($affiliate && $affiliate->clicks !== 0)
      <div class="row mb-3">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-center card-stats">
                <h3>{{ number_format($affiliate->clicks) }}</h3>
                <h5>Lượt Clicks</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-center card-stats">
                <h3>{{ number_format($affiliate->signups) }}</h3>
                <h5>Lượt Đăng Ký</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-center card-stats">
                <h3>{{ number_format($affiliate->total_deposit) }}</h3>
                <h5>Tổng Tiền Nạp</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-center card-stats">
                <h3>{{ number_format($affiliate->total_account_buy) }}</h3>
                <h5>Lượt Mua Account</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-center card-stats">
                <h3>{{ number_format($affiliate->total_item_buy) }}</h3>
                <h5>Lượt Mua Item</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-center card-stats">
                <h3>{{ number_format($affiliate->total_boost_buy) }}</h3>
                <h5>Lượt Cày Thuê</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Thông tin chung</div>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
          @csrf
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="username" class="form-label">Tài khoản</label>
              <input type="text" class="form-control" id="username" value="{{ $user->username }}" readonly>
            </div>
            <div class="col-lg-6">
              <label for="access_token" class="form-label">Access Token (API)</label>
              <input type="text" class="form-control" id="access_token" value="{{ $user->access_token }}" readonly>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="created_at" class="form-label">Ngày đăng ký</label>
              <input type="text" class="form-control" id="created_at" value="{{ $user->created_at }}" readonly>
            </div>
            <div class="col-lg-6">
              <label for="ip_address" class="form-label">Địa chỉ IP</label>
              <input type="text" class="form-control" id="ip_address" value="{{ $user->ip_address }}" readonly>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="balance" class="form-label">Số tiền hiện tại</label>
              <input type="text" class="form-control" id="balance" value="{{ Helper::formatCurrency($user->balance) }}" readonly>
            </div>
            <div class="col-lg-6">
              <label for="total_deposit" class="form-label">Tổng tiền đã nạp</label>
              <input type="text" class="form-control" id="total_deposit" value="{{ Helper::formatCurrency($user->total_deposit) }}" readonly>
            </div>
          </div>
          <div class="form-group row  mb-3">
            <div class="col-md-3">
              <label for="role" class="form-label">Loại tài khoản</label>
              <select class="form-control" id="role" name="role" required>
                <option value="member" @if ($user->role == 'member') selected @endif>Thành viên</option>
                <option value="partner" @if ($user->role == 'partner') selected @endif>Đối tác</option>
                <option value="accounting" @if ($user->role == 'accounting') selected @endif>Kế toán </option>
                {{-- <option value="collaborator" @if ($user->role == 'collaborator') selected @endif>Cộng tác viên</option> --}}
                <option value="admin" @if ($user->role == 'admin') selected @endif>Quản trị viên </option>
              </select>
            </div>
            @php
              $collaborators = [
                  'items' => 'Vật phẩm',
                  'account' => 'Tài khoản',
                  'boosting' => 'Cày thuê',
              ];

              $staffGroups = \App\Models\Group::where('status', true)->get();

            @endphp
            <div class="col-md-3">
              <label for="colla_type" class="form-label">Cộng tác viên</label>
              <select name="colla_type" id="colla_type" class="form-control">
                <option value="">Không có</option>
                @foreach ($collaborators as $key => $collaborator)
                  <option value="{{ $key }}" @if ($user->colla_type == $key) selected @endif>{{ $collaborator }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label for="colla_percent" class="form-label">% Hoa hồng mỗi đơn</label>
              <input type="number" class="form-control" id="colla_percent" name="colla_percent" value="{{ $user->colla_percent }}" required>
            </div>
            <div class="col-md-3">
              <label for="colla_balance" class="form-label">Tài khoản CTV</label>
              <input type="text" class="form-control"
                value="DƯ: {{ Helper::formatCurrency($user->colla_balance) }}; GIỮ: {{ Helper::formatCurrency($user->colla_pending) }}; RÚT: {{ Helper::formatCurrency($user->colla_withdraw) }}" readonly>
            </div>
          </div>
          @if ($user->colla_type === 'account')
            <div class="mb-3">
              <label for="staff_group_ids" class="form-label">Danh sách nhóm tài khoản được phép bán</label>
              <select class="form-control" id="staff_group_ids" name="staff_group_ids[]" multiple>
                @foreach ($staffGroups as $staffGroup)
                  <option value="{{ $staffGroup->id }}" @if (in_array($staffGroup->id, $user->staff_group_ids ?? [])) selected @endif>
                    ID {{ $staffGroup->id }}: {{ $staffGroup->name }}
                  </option>
                @endforeach
              </select>
            </div>
          @endif
          <div class="mb-3 row">
            <div class="col-md-6">
              <label for="colla_balance" class="form-label">Số dư cộng tác viên</label>
              <input type="text" class="form-control" id="colla_balance" name="colla_balance" value="{{ Helper::formatCurrency($user->colla_balance) }}" readonly>
            </div>
            <div class="col-md-6">
              <label for="cron_ctv" class="form-label">Link Cron Duyệt Tiền</label>
              <input type="text" class="form-control" id="cron_ctv" value="{{ route('cron.check-payment-staff') }}" readonly>
            </div>
          </div>
          <div class="form-group row mb-3">
            <div class="col-lg-6">
              <label for="status_id" class="form-label">Trạng thái</label>
              <select class="form-control" id="status_id" name="status" required>
                @php $statuses = ['active','locked']; @endphp
                @foreach ($statuses as $status)
                  <option value="{{ $status }}" @if ($user->status == $status) selected @endif>
                    {{ Str::ucfirst($status) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-6">
              <label for="email" class="form-label">Địa chỉ e-mail</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
          </div>
          <div class="form-group mb-3">
            <label for="password" class="form-label">Đặt lại mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu nếu cần thay đổi">
          </div>
          <div class="form-group row mb-3">
            <div class="col-md-6">
              <label for="balance_1" class="form-label">Số dư hoa hồng</label>
              <input type="number" class="form-control" id="balance_1" name="balance_1" value="{{ $user->balance_1 }}" required>
            </div>
            <div class="col-md-6">
              <label for="referrer" class="form-label">Người giới thiệu</label>
              <input type="text" class="form-control" id="referrer" value="{{ $user->referrer?->username ?? 'Không có' }}" readonly>
            </div>
          </div>
          <div class="form-group">
            <button class="btn btn-primary-gradient w-100" type="submit" name="action" value="update-info">
              Cập nhật
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">Cộng tiền thành viên</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
              @csrf
              <div class="form-group mb-3">
                <label for="amount" class="form-label">Số tiền (*)</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Nhập số tiền cần cộng" required>
              </div>
              <div class="form-group mb-3">
                <label for="reason" class="form-label">Lý do (*)</label>
                <textarea class="form-control" id="reason" name="reason" placeholder="Nhập lý do cộng tiền nếu có"></textarea>
              </div>
              <div class="form-group">
                <button class="btn btn-success w-100 btn-block" type="submit" name="action" value="plus-money">Cập nhật</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">Trừ tiền thành viên</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
              @csrf
              <div class="form-group mb-3">
                <label for="amount" class="form-label">Số tiền (*)</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Nhập số tiền cần trừ" required>
              </div>
              <div class="form-group mb-3">
                <label for="reason" class="form-label">Lý do (*)</label>
                <textarea class="form-control" id="reason" name="reason" placeholder="Nhập lý do trừ tiền nếu có"></textarea>
              </div>
              <div class="form-group">
                <button class="btn btn-danger w-100 btn-block" type="submit" name="action" value="sub-money">Cập nhật</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @if ($user->colla_type)
      <div class="row">
        <div class="col-md-6">
          <div class="card custom-card">
            <div class="card-header justify-content-between">
              <div class="card-title">Cộng tiền cộng tác viên</div>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
                @csrf
                <div class="form-group mb-3">
                  <label for="amount" class="form-label">Số tiền (*)</label>
                  <input type="number" class="form-control" id="amount" name="amount" placeholder="Nhập số tiền cần cộng" required>
                </div>
                <div class="form-group mb-3">
                  <label for="reason" class="form-label">Lý do (*)</label>
                  <textarea class="form-control" id="reason" name="reason" placeholder="Nhập lý do cộng tiền nếu có"></textarea>
                </div>
                <div class="form-group">
                  <button class="btn btn-success w-100 btn-block" type="submit" name="action" value="plus-commision">Cập nhật</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card custom-card">
            <div class="card-header justify-content-between">
              <div class="card-title">Trừ tiền cộng tác viên</div>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST" class="default-form">
                @csrf
                <div class="form-group mb-3">
                  <label for="amount" class="form-label">Số tiền (*)</label>
                  <input type="number" class="form-control" id="amount" name="amount" placeholder="Nhập số tiền cần trừ" required>
                </div>
                <div class="form-group mb-3">
                  <label for="reason" class="form-label">Lý do (*)</label>
                  <textarea class="form-control" id="reason" name="reason" placeholder="Nhập lý do trừ tiền nếu có"></textarea>
                </div>
                <div class="form-group">
                  <button class="btn btn-danger w-100 btn-block" type="submit" name="action" value="sub-commision">Cập nhật</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Lịch sử giao dịch [2000 dòng gần nhất]</div>
      </div>
      <div class="card-body">
        <div class="table-responsive theme-scrollbar">
          <table class="display table table-bordered table-stripped text-nowrap datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Tài khoản</th>
                <th>Giao dịch</th>
                <th>Mã giao dịch</th>
                <th>Số tiền</th>
                <th>Số dư trước</th>
                <th>Số dư sau</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($user->transactions()->orderBy('id', 'desc')->limit(2000)->get() as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->username }}</td>
                  <td>{!! Helper::formatTransType($item->type) !!}</td>
                  <td>{{ $item->code }}</td>
                  <td>{{ $item->prefix . ' ' . Helper::formatCurrency($item->amount) }}</td>
                  <td>{{ Helper::formatCurrency($item->balance_before) }}</td>
                  <td>{{ Helper::formatCurrency($item->balance_after) }}</td>
                  <td class="text-wrap">{{ $item->content }} </td>
                  <td>{!! Helper::formatStatus($item->status) !!}</td>
                  <th>{{ $item->created_at }}</th>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    @if ($user->colla_type)
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Danh sách giao dịch hoa hồng</div>
        </div>
        <div class="card-body">
          <div class="table-responsive theme-scrollbar" style="padding: 10px">
            <table class="display table table-bordered table-stripped text-nowrap datatable" id="basic-1">
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
                </tr>
              </thead>
              <tbody>
                @foreach (\App\Models\CollaTransaction::where('user_id', $user->id)->orderBy('id', 'desc')->limit(2000)->get() as $value)
                  <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->username }}</td>
                    <td>{{ $value->type }}</td>
                    <td>{{ $value->reference }}</td>
                    <td>{{ Helper::formatCurrency($value->balance_before) }}</td>
                    <td>{{ Helper::formatCurrency($value->amount) }}</td>
                    <td>{{ Helper::formatCurrency($value->balance_after) }}</td>
                    <td>{{ $value->description }}</td>
                    <td>{!! Helper::formatStatus($value->status) !!}</td>
                    <td>{{ $value->created_at }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif

    <div class="card custom-card">
      <div class="card-header justify-content-between">
        <div class="card-title">Nhật ký hoạt động [2000 dòng gần nhất]</div>
      </div>
      <div class="card-body">
        <div class="table-responsive theme-scrollbar">
          <table class="display table table-bordered table-stripped text-nowrap datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Tài khoản</th>
                <th>Nội dung</th>
                <th>Dữ liệu</th>
                <th>Địa chỉ IP</th>
                <th>Thời gian</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($user->histories()->orderBy('id', 'desc')->limit(2000)->get() as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->username }}</td>
                  <td>{{ $item->content }}</td>
                  <td>-</td>
                  <td>{{ $item->ip_address }}</td>
                  <td>{{ $item->created_at }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </section>
@endsection
