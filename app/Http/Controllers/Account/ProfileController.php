<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function index()
  {
    $user = User::find(auth()->user()->id);

    return view('account.profile.index', [
      'pageTitle' => 'Thông tin tài khoản',
    ], compact('user'));
  }

  public function transactions()
  {
    $user = User::find(auth()->user()->id);

    $stats = [
      'balance'          => Helper::formatCurrency($user->balance),
      'total_spent'      => Helper::formatCurrency($user->total_deposit - $user->balance),
      'total_deposit'    => Helper::formatCurrency($user->total_deposit),
      'deposit_in_month' => Helper::formatCurrency(Transaction::where('user_id', $user->id)->where('type', 'deposit')->whereMonth('created_at', date('m'))->sum('amount')),
    ];

    return view('account.profile.transactions', [
      'pageTitle' => 'Lịch Sử Giao Dịch',
    ], compact('stats'));
  }

  public function updatePassword(Request $request)
  {
    if (env('APP_DEMO', false)) {
      return redirect()->back()->with('error', 'Chức năng này không khả dụng trong chế độ demo');
    }

    $payload = $request->validate([
      'old_password'     => 'required|string|min:6',
      'new_password'     => 'required|string|min:6',
      'confirm_password' => 'required|string|min:6',
    ]);

    $user = User::find(auth()->user()->id);

    if (! password_verify($payload['old_password'], $user->password)) {
      return redirect()->back()->withErrors([
        'old_password' => 'Mật khẩu cũ không chính xác',
      ]);
    }

    if ($payload['new_password'] !== $payload['confirm_password']) {
      return redirect()->back()->withErrors([
        'confirm_password' => 'Mật khẩu xác nhận không chính xác',
      ]);
    }

    $user->password = bcrypt($payload['new_password']);

    $user->save();

    Helper::addHistory('Thay đổi mật khẩu thành công');

    return redirect()->back()->with('success', 'Cập nhật mật khẩu thành công');
  }
}