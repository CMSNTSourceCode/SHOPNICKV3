<?php

namespace App\Http\Controllers\Admin\Game;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawLog;
use Helper;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
  public function index()
  {
    $histories = WithdrawLog::orderBy('id', 'desc')->get();
    return view('admin.game.withdraw.index', compact('histories'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'         => 'required|integer',
      'status'     => 'required|string|in:Pending,Completed,Cancelled',
      'user_note'  => 'nullable|string|max:255',
      'admin_note' => 'nullable|string|max:255',
    ]);

    $history = WithdrawLog::find($payload['id']);

    if (!$history) {
      return redirect()->back()->with('error', 'Không tìm thấy lịch sử rút thưởng');
    }

    if ($history->status === 'Completed' || $history->status === 'Cancelled') {
      return redirect()->back()->with('error', 'Lịch sử rút thưởng đã được xử lý');
    }

    $history->update([
      'status'     => $payload['status'],
      'user_note'  => $payload['user_note'] ?? null,
      'admin_note' => $payload['admin_note'] ?? null,
    ]);

    if ($payload['status'] === 'Cancelled') {
      $user = User::where('username', $history->username)->first();

      if ($user) {
        $user->increment('balance_2', $history->value);

        $history->update([
          'current_balance' => $history->current_balance + $history->value,
        ]);
      }
    }

    Helper::addHistory("Đã " . strtolower($payload['status']) . " lịch sử rút thưởng của " . $history->username);

    return redirect()->back()->with('success', 'Xử lý lịch sử rút thưởng thành công');
  }
}
