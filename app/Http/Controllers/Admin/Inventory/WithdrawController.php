<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\WithdrawData;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
  public function index(Request $request)
  {
    $records = WithdrawData::where('type', 'withdraw')->orderBy('id', 'desc')->get();

    return view('admin.inventory.withdraw.index', [
      'pageTitle' => 'Admin: Customer Withdraws',
    ], compact('records'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'         => 'required|integer|exists:withdraw_data,id',
      'status'     => 'required|string|in:Pending,Approved,Rejected',
      'admin_note' => 'nullable|string',
    ]);

    $withdraw = WithdrawData::where('id', $payload['id'])->first();

    if ($withdraw->status !== 'Pending') {
      return response()->json([
        'data'    => null,
        'status'  => 400,
        'message' => 'Withdraw status is not pending',
      ], 400);
    }

    $withdraw->update($payload);

    if ($payload['status'] === 'Rejected') {
      $inventory = Inventory::where('id', $withdraw->inv_id)->first();

      if ($inventory) {
        $inventory->increment('value', $withdraw->amount);

        $withdraw->update(array_merge($payload, [
          'amount'       => 0,
          'after_value'  => 0,
          'before_value' => 0,
        ]));
      }
    }

    return response()->json([
      'data'    => $withdraw,
      'status'  => 200,
      'message' => 'Withdraw updated successfully',
    ], 200);
  }
}
