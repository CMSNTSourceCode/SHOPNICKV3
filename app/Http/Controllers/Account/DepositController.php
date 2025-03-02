<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Invoice;
use Helper;

class DepositController extends Controller
{
  public function index()
  {
    $banks        = BankAccount::where('status', true)->get();
    $card_configs = $config = Helper::getApiConfig('charging_card');

    $info           = Helper::getConfig('deposit_info');
    $deposit_prefix = $info['prefix'] ?? 'hello ';
    $deposit_prefix .= auth()->user()->id;
    $deposit_amount = 10000;

    $fees = $card_configs['fees'] ?? [];

    $cardOn = true;
    if (!isset($card_configs['api_url']) || !isset($card_configs['partner_id']) || !isset($card_configs['partner_key'])) {
      $cardOn = false;
    }

    return view('account.deposits.index', [
      'pageTitle' => 'Nạp Tiền Tài Khoản',
    ], compact('banks', 'deposit_prefix', 'deposit_amount', 'card_configs', 'fees', 'cardOn'));
  }

  public function crypto()
  {
    $config = Helper::getApiConfig('fpayment');

    if (!isset($config['address_wallet'])) {
      return redirect()->back()->with('error', 'Chưa cấu hình ví tiền điện tử.');
    }

    $invoices = Invoice::where('user_id', auth()->id())->where('type', 'fpayament')->simplePaginate(10);

    return view('account.deposits.crypto', [
      'pageTitle' => 'Nạp Tiền Tài Khoản Bằng Crypto',
    ], compact('config', 'invoices'));
  }

  public function invoice()
  {
    return view('account.deposits.invoice', [
      'pageTitle' => 'Tạo hóa đơn nạp tiền',
    ]);
  }

  public function paypal()
  {
    $config = Helper::getApiConfig('paypal');

    if (!isset($config['client_id'])) {
      return redirect()->back()->with('error', 'Chưa cấu hình ví tiền điện tử.');
    }

    $invoices = Invoice::where('user_id', auth()->id())->where('type', 'paypal')->simplePaginate(10);

    return view('account.deposits.paypal', [
      'pageTitle' => 'Nạp Tiền Tài Khoản Bằng Paypal',
    ], compact('config', 'invoices'));
  }

  public function perfectMoney()
  {
    $config = Helper::getApiConfig('perfect_money');

    if (!isset($config['account_id'])) {
      return redirect()->back()->with('error', 'Chưa cấu hình tài khoản Perfect Money.');
    }

    $user      = auth()->user();
    $invoice   = Invoice::where('user_id', auth()->id())->where('type', 'perfect_money')->where('status', 'processing')->first();
    $requestId = Helper::randomString(10);

    if ($invoice === null) {
      $invoice = Invoice::create([
        'code'        => 'PM-' . Helper::randomString(7, true),
        'type'        => 'perfect_money',
        'status'      => 'processing',
        'amount'      => 0,
        'user_id'     => auth()->id(),
        'username'    => auth()->user()->username,
        'currency'    => 'USD',
        'request_id'  => $requestId,
        'description' => 'Nạp tiền tài khoản bằng Perfect Money',
      ]);
    }

    $params = [
      'API_URL'        => 'https://perfectmoney.is/api/step1.asp',
      'PAYMENT_ID'     => $invoice->request_id,
      // mã giao dịch không trùng lặp để lưu lên hệ thống
      'PAYEE_ACCOUNT'  => $config['account_id'],
      // mã tài khoản Perfect Money
      'PAYMENT_UNITS'  => 'USD',
      // đơn vị tiền tệ,
      'PAYEE_NAME'     => $user->username,
      // tên người thanh toán
      'PAYMENT_URL'    => route('account.deposits.perfect-money'),
      // URL của hoá đơn
      'NOPAYMENT_URL'  => route('account.deposits.perfect-money'),
      // URL của hoá đơn
      'STATUS_URL'     => route('cron.deposit.pm-callback'),
      // Webhook callback
      'SUGGESTED_MEMO' => 'Payment - ' . $invoice->code
    ];

    $invoices = Invoice::where('user_id', auth()->id())->where('type', 'perfect_money')->where('status', 'completed')->simplePaginate(10);

    return view('account.deposits.perfect_money', [
      'pageTitle' => 'Nạp Tiền Tài Khoản Bằng Perfect Money',
    ], compact('config', 'invoices', 'invoice', 'params'));
  }
}
