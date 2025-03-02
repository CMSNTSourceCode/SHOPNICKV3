@extends('admin.layouts.master')
@section('title', 'Admin: Apis Settings')
@section('content')

  @if (feature_enabled('dp_apisieuthicode'))
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">AutoBank | STC | Thesieure</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.settings.apis.update', ['type' => 'stc_thesieure']) }}" method="POST" class="axios-form">
              @csrf
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="account_number" class="form-label">Số Tài Khoản</label>
                  <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $stc_thesieure['account_number'] ?? '' }}" readonly>
                </div>
                <div class="col-md-4">
                  <label for="account_password" class="form-label">Mật khẩu Bank</label>
                  <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $stc_thesieure['account_password'] ?? '' }}" readonly>
                </div>
                <div class="col-md-4">
                  <label for="api_token" class="form-label">API Token</label>
                  <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $stc_thesieure['api_token'] ?? '' }}">
                </div>
              </div>
              <div class="mb-3">
                <label for="link_cron" class="form-label">Link Cron (manual)</label>
                <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check-c1', ['dp_type' => 'check', 'type' => 'thesieure']) }}" readonly>
              </div>
              <div class="mb-3 text-end">
                <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">AutoBank | STC | Vietinbank</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.settings.apis.update', ['type' => 'stc_vietinbank']) }}" method="POST" class="axios-form">
              @csrf
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="account_number" class="form-label">Số Tài Khoản</label>
                  <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $stc_vietinbank['account_number'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="account_password" class="form-label">Mật khẩu Bank</label>
                  <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $stc_vietinbank['account_password'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="api_token" class="form-label">API Token</label>
                  <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $stc_vietinbank['api_token'] ?? '' }}">
                </div>
              </div>
              <div class="mb-3">
                <label for="link_cron" class="form-label">Link Cron (manual)</label>
                <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check-c1', ['dp_type' => 'check', 'type' => 'vietinbank']) }}" readonly>
              </div>
              <div class="mb-3 text-end">
                <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">AutoBank | STC | MBBank</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.settings.apis.update', ['type' => 'stc_mbbank']) }}" method="POST" class="axios-form">
              @csrf
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="account_number" class="form-label">Số Tài Khoản</label>
                  <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $stc_mbbank['account_number'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="account_password" class="form-label">Mật khẩu Bank</label>
                  <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $stc_mbbank['account_password'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="api_token" class="form-label">API Token</label>
                  <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $stc_mbbank['api_token'] ?? '' }}">
                </div>
              </div>
              <div class="mb-3">
                <label for="link_cron" class="form-label">Link Cron (manual)</label>
                <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check-c1', ['dp_type' => 'check', 'type' => 'mbbank']) }}" readonly>
              </div>
              <div class="mb-3 text-end">
                <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">AutoBank | STC | BIDV</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.settings.apis.update', ['type' => 'stc_bidv']) }}" method="POST" class="axios-form">
              @csrf
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="account_number" class="form-label">Số Tài Khoản</label>
                  <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $stc_bidv['account_number'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="account_password" class="form-label">Mật khẩu Bank</label>
                  <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $stc_bidv['account_password'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="api_token" class="form-label">API Token</label>
                  <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $stc_bidv['api_token'] ?? '' }}">
                </div>
              </div>
              <div class="mb-3">
                <label for="link_cron" class="form-label">Link Cron (manual)</label>
                <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check-c1', ['dp_type' => 'check', 'type' => 'bidv']) }}" readonly>
              </div>
              <div class="mb-3 text-end">
                <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="card custom-card">
          <div class="card-header justify-content-between">
            <div class="card-title">AutoBank | STC | Vietcombank</div>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.settings.apis.update', ['type' => 'stc_vietcombank']) }}" method="POST" class="axios-form">
              @csrf
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="account_number" class="form-label">Số Tài Khoản</label>
                  <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $stc_vietcombank['account_number'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="account_password" class="form-label">Mật khẩu Bank</label>
                  <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $stc_vietcombank['account_password'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label for="api_token" class="form-label">API Token</label>
                  <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $stc_vietcombank['api_token'] ?? '' }}">
                </div>
              </div>
              <div class="mb-3">
                <label for="link_cron" class="form-label">Link Cron (manual)</label>
                <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check-c1', ['dp_type' => 'check', 'type' => 'vietcombank']) }}" readonly>
              </div>
              <div class="mb-3 text-end">
                <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | Vietcombank</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_vietcombank']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_vietcombank['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_vietcombank['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_vietcombank['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['dp_type' => 'check', 'type' => 'vietcombank']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | Vietinbank</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_vietinbank']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_vietinbank['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_vietinbank['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_vietinbank['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['dp_type' => 'check', 'type' => 'vietinbank']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | TPBank</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_tpbank']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_tpbank['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_tpbank['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_tpbank['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['dp_type' => 'check', 'type' => 'tpbank']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | ACB</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_acb']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_acb['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_acb['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_acb['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['dp_type' => 'check', 'type' => 'acb']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | MBBank</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_mbbank']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="account_number" class="form-label">Số Tài Khoản</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $web2m_mbbank['account_number'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="account_password" class="form-label">Mật khẩu Bank</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="{{ $web2m_mbbank['account_password'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="api_token" class="form-label">API Token Web2m</label>
                <input type="password" class="form-control" id="api_token" name="api_token" value="{{ $web2m_mbbank['api_token'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['dp_type' => 'check', 'type' => 'mbbank']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | MOMO</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_momo']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3">
              <label for="api_token" class="form-label">API Token</label>
              <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_momo['api_token'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['dp_type' => 'check', 'type' => 'momo']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">AutoBank | Web2m | TheSieuRe</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'web2m_tsr']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3">
              <label for="api_token" class="form-label">API Token</label>
              <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $web2m_tsr['api_token'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Cron (manual)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.check', ['dp_type' => 'check', 'type' => 'thesieure']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">AutoBank | Perfect Money | USDT</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'perfect_money']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3">
              <label for="account_id" class="form-label">Mã tài khoản</label>
              <input type="text" class="form-control" id="account_id" name="account_id" value="{{ $perfect_money['account_id'] ?? '' }}">
              <small>Vào đây để lấy mật mã tài khoản và đơn vị tiền tệ: <a href="https://perfectmoney.com/profile.html" target="_blank">https://perfectmoney.com/profile.html</a></small>
            </div>
            <div class="mb-3">
              <label for="passphrase" class="form-label">Mật khẩu Thay thế (Alternate Passphrase)</label>
              <input type="text" class="form-control" id="passphrase" name="passphrase" value="{{ $perfect_money['passphrase'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="exchange" class="form-label">Tỷ giá quy đổi 1$</label>
              <input type="text" class="form-control" id="exchange" name="exchange" value="{{ $perfect_money['exchange'] ?? 24000 }}">
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">AutoBank | FPayment | USDT</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'fpayment']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="address_wallet" class="form-label">Address Wallet</label>
                <input type="text" class="form-control" id="address_wallet" name="address_wallet" value="{{ $fpayment['address_wallet'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="token_wallet" class="form-label">Token Wallet</label>
                <input type="text" class="form-control" id="token_wallet" name="token_wallet" value="{{ $fpayment['token_wallet'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="exchange" class="form-label">Exchange VND</label>
                <input type="text" class="form-control" id="exchange" name="exchange" value="{{ $fpayment['exchange'] ?? 23000 }}">
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">AutoBank | Paypal | USD</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'paypal']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="client_id" class="form-label">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" value="{{ $paypal['client_id'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="client_secret" class="form-label">Client Secret</label>
                <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ $paypal['client_secret'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="exchange" class="form-label">Exchange VND</label>
                <input type="text" class="form-control" id="exchange" name="exchange" value="{{ $paypal['exchange'] ?? 23000 }}">
              </div>
              <input type="hidden" id="token" value="{{ auth()->user()->access_token }}" />
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">API Gạch Thẻ | CARD24H.COM hoặc <a href="https://documenter.getpostman.com/view/5740908/TVYJ5Ggr" target="_blank">Hoặc tương tự</a></div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'charging_card']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="api_url" class="form-label">API Url</label>
                <input type="text" class="form-control" id="api_url" name="api_url" value="{{ $charging_card['api_url'] ?? 'https://card24h.com/' }}" placeholder="https://card24h.com/">
              </div>
              <div class="col-md-4">
                <label for="partner_id" class="form-label">Partner ID</label>
                <input type="text" class="form-control" id="partner_id" name="partner_id" value="{{ $charging_card['partner_id'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="partner_key" class="form-label">Partner Key</label>
                <input type="text" class="form-control" id="partner_key" name="partner_key" value="{{ $charging_card['partner_key'] ?? '' }}">
              </div>

            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="fees_viettel" class="form-label">Phí Thẻ Viettel</label>
                <input type="text" class="form-control" id="fees_viettel" name="fees[VIETTEL]" value="{{ $charging_card['fees']['VIETTEL'] ?? 20 }}" placeholder="30">
              </div>
              <div class="col-md-4">
                <label for="fees_vinaphone" class="form-label">Phí Thẻ Vinaphone</label>
                <input type="text" class="form-control" id="fees_vinaphone" name="fees[VINAPHONE]" value="{{ $charging_card['fees']['VINAPHONE'] ?? 20 }}" placeholder="30">
              </div>
              <div class="col-md-4">
                <label for="fees_mobifone" class="form-label">Phí Thẻ Mobifone</label>
                <input type="text" class="form-control" id="fees_mobifone" name="fees[MOBIFONE]" value="{{ $charging_card['fees']['MOBIFONE'] ?? 20 }}" placeholder="30">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="fees_zing" class="form-label">Phí Thẻ Zing</label>
                <input type="text" class="form-control" id="fees_zing" name="fees[ZING]" value="{{ $charging_card['fees']['ZING'] ?? 20 }}" placeholder="30">
              </div>
              <div class="col-md-4">
                <label for="fees_garena" class="form-label">Phí Thẻ GARENA</label>
                <input type="text" class="form-control" id="fees_garena" name="fees[GARENA]" value="{{ $charging_card['fees']['GARENA'] ?? 20 }}" placeholder="30">
              </div>
              <div class="col-md-4">
                <label for="fees_vnmobi" class="form-label">Phí Thẻ VNMOBI</label>
                <input type="text" class="form-control" id="fees_vnmobi" name="fees[VNMOBI]" value="{{ $charging_card['fees']['VNMOBI'] ?? 20 }}" placeholder="30">
              </div>
            </div>
            <div class="mb-3">
              <label for="link_cron" class="form-label">Link Callback (GET/POST)</label>
              <input type="text" class="form-control" id="link_cron" name="link_cron" value="{{ route('cron.deposit.card-callback') }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">SMTP Mailer | <a href="https://www.cmsnt.co/2022/12/huong-dan-cach-cau-hinh-smtp-e-gui.html" target="_blank">Lấy thông tin SMTP</a></div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'smtp_detail']) }}" method="POST" class="axios-form">
            @csrf
            <div class="row mb-3">
              <div class="col-lg-6">
                <label for="host" class="form-label">SMTP Host</label>
                <input type="text" class="form-control" id="host" name="host" value="{{ $smtp_detail['host'] ?? '' }}">
              </div>
              <div class="col-lg-6">
                <label for="port" class="form-label">SMTP Port</label>
                <input type="number" class="form-control" id="port" name="port" value="{{ $smtp_detail['port'] ?? '' }}">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-lg-6">
                <label for="user" class="form-label">SMTP User</label>
                <input type="text" class="form-control" id="user" name="user" value="{{ $smtp_detail['user'] ?? '' }}">
              </div>
              <div class="col-lg-6">
                <label for="pass" class="form-label">SMTP Pass</label>
                <input type="text" class="form-control" id="pass" name="pass" value="{{ $smtp_detail['pass'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Authentication | Google App</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'auth_google']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3">
              <label for="client_status" class="form-label">Trạng Thái</label>
              <select class="form-select" id="client_status" name="client_status">
                <option value="1" {{ ($auth_google['client_status'] ?? 0) == 1 ? 'selected' : '' }}>Bật</option>
                <option value="0" {{ ($auth_google['client_status'] ?? 0) == 0 ? 'selected' : '' }}>Tắt</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="client_key" class="form-label">Client Key</label>
              <input type="text" class="form-control" id="client_key" name="client_key" value="{{ $auth_google['client_key'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="client_secret" class="form-label">Client Secret</label>
              <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ $auth_google['client_secret'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="redirect_url">Redirect URL</label>
              <input type="url" class="form-control" id="redirect_url" name="redirect_url" value="{{ route('auth.social.callback', ['provider' => 'google']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <div class="card-title">Authentication | Facebook App</div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'auth_facebook']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3">
              <label for="client_status" class="form-label">Trạng Thái</label>
              <select class="form-select" id="client_status" name="client_status">
                <option value="1" {{ ($auth_google['client_status'] ?? 0) == 1 ? 'selected' : '' }}>Bật</option>
                <option value="0" {{ ($auth_google['client_status'] ?? 0) == 0 ? 'selected' : '' }}>Tắt</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="client_key" class="form-label">Client Key</label>
              <input type="text" class="form-control" id="client_key" name="client_key" value="{{ $auth_facebook['client_key'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="client_secret" class="form-label">Client Secret</label>
              <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ $auth_facebook['client_secret'] ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="redirect_url">Redirect URL</label>
              <input type="url" class="form-control" id="redirect_url" name="redirect_url" value="{{ route('auth.social.callback', ['provider' => 'facebook']) }}" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-success-gradient mt-2" type="submit">Cập nhật ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">

    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">UPLOAD FILE | <a href="https://aws.amazon.com/s3/" target="_blank">S3 Amazon</a></h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 's3aws']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="AWS_ACCESS_KEY_ID" class="form-label">AWS_ACCESS_KEY_ID</label>
                <input type="text" class="form-control" id="AWS_ACCESS_KEY_ID" name="AWS_ACCESS_KEY_ID" value="{{ $s3aws['AWS_ACCESS_KEY_ID'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="AWS_SECRET_ACCESS_KEY" class="form-label">AWS_SECRET_ACCESS_KEY</label>
                <input type="text" class="form-control" id="AWS_SECRET_ACCESS_KEY" name="AWS_SECRET_ACCESS_KEY" value="{{ $s3aws['AWS_SECRET_ACCESS_KEY'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="AWS_DEFAULT_REGION" class="form-label">AWS_DEFAULT_REGION</label>
                <input type="text" class="form-control" id="AWS_DEFAULT_REGION" name="AWS_DEFAULT_REGION" value="{{ $s3aws['AWS_DEFAULT_REGION'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="AWS_BUCKET" class="form-label">AWS_BUCKET</label>
                <input type="text" class="form-control" id="AWS_BUCKET" name="AWS_BUCKET" value="{{ $s3aws['AWS_BUCKET'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="AWS_URL" class="form-label">AWS_URL</label>
              <input type="text" class="form-control" id="AWS_URL" name="AWS_URL" value="{{ $s3aws['AWS_URL'] ?? '' }}" placeholder="AUTO_GEN" readonly>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">UPLOAD FILE | <a href="https://m.do.co/c/9b0d7560bc9e" target="_blank">Digital Ocean Spaces</a></h4>
          <small>$5 per month (250 GiB, 1 TiB outbond)</small>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'do_spaces']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="DO_SPACES_KEY" class="form-label">DO_SPACES_KEY</label>
                <input type="text" class="form-control" id="DO_SPACES_KEY" name="DO_SPACES_KEY" value="{{ $do_spaces['DO_SPACES_KEY'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="DO_SPACES_SECRET" class="form-label">DO_SPACES_SECRET</label>
                <input type="text" class="form-control" id="DO_SPACES_SECRET" name="DO_SPACES_SECRET" value="{{ $do_spaces['DO_SPACES_SECRET'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="DO_SPACES_REGION" class="form-label">DO_SPACES_REGION</label>
                <input type="text" class="form-control" id="DO_SPACES_REGION" name="DO_SPACES_REGION" value="{{ $do_spaces['DO_SPACES_REGION'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="DO_SPACES_BUCKET" class="form-label">DO_SPACES_BUCKET</label>
                <input type="text" class="form-control" id="DO_SPACES_BUCKET" name="DO_SPACES_BUCKET" value="{{ $do_spaces['DO_SPACES_BUCKET'] ?? '' }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="DO_SPACES_URL" class="form-label">DO_SPACES_URL</label>
              <input type="text" class="form-control" id="DO_SPACES_URL" name="DO_SPACES_URL" value="{{ $do_spaces['DO_SPACES_URL'] ?? '' }}">
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card custom-card">
        <div class="card-header justify-content-between">
          <h4 class="card-title">UPLOAD FILE | IMG BB</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.settings.apis.update', ['type' => 'imgbb']) }}" method="POST" class="axios-form">
            @csrf
            <div class="mb-3 row">
              <div class="col-md-6">
                <label for="client_key" class="form-label">Client Key</label>
                <input type="text" class="form-control" id="client_key" name="client_key" value="{{ $imgbb['client_key'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label for="client_secret" class="form-label">Client Secret</label>
                <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ $imgbb['client_secret'] ?? '' }}" readonly>
              </div>
            </div>
            <div class="mb-3 text-end">
              <button class="btn btn-danger-gradient" type="submit">Cập Nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
