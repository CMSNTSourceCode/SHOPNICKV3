<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\ItemOrder;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function index()
  {
    $orders = ItemOrder::all();

    return view('admin.items.orders.index', compact('orders'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'         => 'required|exists:item_orders,id',
      'status'     => 'required|in:Pending,Processing,Completed,Cancelled',
      'admin_note' => 'nullable|string|max:255',
      'order_note' => 'nullable|string|max:255',
    ]);

    $order = ItemOrder::findOrFail($payload['id']);

    if (in_array($order->status, ['Completed', 'Cancelled'])) {
      return redirect()->back()->with('error', 'Đơn hàng đã hoàn thành hoặc đã hủy');
    }

    $order->update($payload);

    if ($payload['status'] === 'Cancelled') {
      $client = User::find($order->user_id);

      if ($client) {
        $client->increment('balance', $order->payment);

        $client->transactions()->create([
          'code'           => $order->code,
          'amount'         => $order->payment,
          'balance_after'  => $client->balance,
          'balance_before' => $client->balance - $order->payment,
          'type'           => 'item-refund',
          'extras'         => [],
          'status'         => 'paid',
          'content'        => 'Hoàn tiền đơn vật phẩm ' . $order->name,
          'user_id'        => $client->id,
          'username'       => $client->username,
        ]);

        $order->update([
          'payment' => 0,
        ]);
      }
    }

    Helper::addHistory('Cập nhật đơn hàng ' . $order->id . ' trạng thái ' . $payload['status']);

    return redirect()->back()->with('success', 'Cập nhật đơn hàng thành công');
  }
}