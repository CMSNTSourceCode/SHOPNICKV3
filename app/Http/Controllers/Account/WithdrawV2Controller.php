<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\User;
use App\Models\WithdrawLog;
use Helper;
use Illuminate\Http\Request;

class WithdrawV2Controller extends Controller
{
  public function index()
  {
    $inventories = Inventory::where('user_id', auth()->user()->id)->with('inventory_var')->get();

    return view('account.withdraw.index-v2', [
      'user'        => User::findOrFail(auth()->user()->id),
      'config'      => Helper::getConfig('mng_withdraw'),
      'histories'   => WithdrawLog::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->limit(1000)->get(),
      'pageTitle'   => 'Rút Thưởng Trò Chơi',
      'inventories' => $inventories,
    ]);
  }

  public function forms(Request $request)
  {
    $payload   = $request->validate([
      'id' => 'required|integer',
    ]);
    $inventory = Inventory::where('user_id', auth()->user()->id)->where('id', $payload['id'])->with('inventory_var')->firstOrFail();

    return view('account.withdraw.forms-v2', [
      'user'      => User::findOrFail(auth()->user()->id),
      'inventory' => $inventory,
      'pageTitle' => 'Rút Thưởng Trò Chơi',
    ]);
  }
}
