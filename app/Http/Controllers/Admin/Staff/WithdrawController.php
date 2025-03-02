<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\CollaTransaction;
use App\Models\CollaWithdraw;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
  public function index(Request $request)
  {
    $withdraws = CollaWithdraw::orderBy('id', 'desc')->limit(5000)->get();

    return view('admin.staff.withdraws', compact('withdraws'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'        => 'required|integer',
      'status'    => 'required|in:Pending,Completed,Cancelled',
      'user_note' => 'nullable|string|max:255',
    ]);

    $withdraw = CollaWithdraw::findOrFail($payload['id']);

    $withdraw->update([
      'status'    => $payload['status'],
      'user_note' => $payload['user_note'],
    ]);

    $user = User::find($withdraw->user_id);
    if ($payload['status'] === 'Cancelled') {
      if ($user !== NULL) {
        $user->increment('colla_balance', $withdraw->amount);
        CollaTransaction::create([
          'type'           => 'withdraw-cancel',
          'user_id'        => $user->id,
          'username'       => $user->username,
          'amount'         => $withdraw->amount,
          'status'         => 'Completed',
          'reference'      => $withdraw->reference,
          'description'    => 'Rút hoa hồng #' . $withdraw->id . ' bị hủy',
          'balance_before' => $user->colla_balance - $withdraw->amount,
          'balance_after'  => $user->colla_balance,
        ]);
      }
    } else if ($payload['status'] === 'Completed') {
      if ($user !== NULL) {
        $user->increment('colla_withdraw', $withdraw->amount);
      }
    }

    Helper::addHistory("Cập nhật trạng thái rút hoa hồng #" . $withdraw->id . " thành " . $payload['status'] . " thành công");

    return redirect()->back()->with('success', 'Cập nhật thành công');
  }
}
