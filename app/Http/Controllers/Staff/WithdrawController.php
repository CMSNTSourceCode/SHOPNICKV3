<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\CollaTransaction;
use App\Models\CollaWithdraw;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
  public function store(Request $request)
  {
    $message = [
      'amount.required'      => 'Vui lòng nhập số tiền muốn rút.',
      'amount.integer'       => 'Số tiền muốn rút phải là số.',
      'withdraw_to.required' => 'Vui lòng chọn kênh rút tiền.',
      'withdraw_to.string'   => 'Kênh rút tiền không hợp lệ.',
      'withdraw_to.in'       => 'Kênh rút tiền không hợp lệ.',
    ];
    $payload = $request->validate([
      'amount'         => 'required|integer',
      'bank_name'      => 'required|string|max:12',
      'user_note'      => 'nullable|string|max:255',
      'account_name'   => 'required|string|max:128',
      'account_number' => 'required|string|max:128',
    ], $message);

    $user = User::findOrFail(auth()->user()->id);
    $bank = Helper::getListBank($payload['bank_name']);

    if ($bank === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy ngân hàng mà bạn đã chọn, hãy xem lại.',
      ], 400);
    }

    if ($user->colla_balance < $payload['amount']) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số dư hoa hồng không đủ để thực hiện giao dịch này.',
      ], 400);
    }

    if (!$user->decrement('colla_balance', $payload['amount'])) {
      return response()->json([
        'status'  => 400,
        'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
      ], 400);
    }

    $paymentInfo = [
      'bank_code'      => $payload['bank_name'],
      'bank_name'      => $bank['shortName'],
      'account_number' => $payload['account_number'],
      'account_name'   => $payload['account_name'],
    ];

    $trans = CollaTransaction::create([
      'type'           => 'withdraw',
      'user_id'        => $user->id,
      'username'       => $user->username,
      'amount'         => $payload['amount'],
      'status'         => 'Completed',
      'reference'      => 'WD-' . Helper::randomString(7, true),
      'description'    => 'Tạo yêu cầu rút tiền về ngân hàng #' . $paymentInfo['bank_code'] . '|' . $paymentInfo['account_number'],
      'balance_before' => $user->colla_balance + $payload['amount'],
      'balance_after'  => $user->colla_balance,
    ]);

    $order = CollaWithdraw::create([
      'type'         => 'bank',
      'user_id'      => $user->id,
      'username'     => $user->username,
      'amount'       => $payload['amount'],
      'status'       => 'Pending',
      'description'  => $payload['user_note'] ?? '',
      'reference'    => $trans->reference,
      'payment_info' => $paymentInfo,
    ]);

    return response()->json([
      'status'  => 200,
      'message' => 'Yêu cầu rút tiền thành công, vui lòng đợi.',
    ], 200);
  }
}
