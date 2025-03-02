<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawLog;
use Helper;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
  public function index()
  {
    return view('account.withdraw.index', [
      'user'      => User::findOrFail(auth()->user()->id),
      'config'    => Helper::getConfig('mng_withdraw'),
      'histories' => WithdrawLog::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->limit(1000)->get(),
      'pageTitle' => 'Rút Thưởng Trò Chơi',
    ]);
  }
}
