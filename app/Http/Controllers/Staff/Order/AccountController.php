<?php

namespace App\Http\Controllers\Staff\Order;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\ListItem;
use Illuminate\Http\Request;

class AccountController extends Controller
{
  public function index(Request $request, $id = null)
  {

    if ($id === null) {
      $payload = $request->validate([
        'sold'       => 'nullable|in:0,1',
        'group'      => 'nullable|integer',
        'username'   => 'nullable|string',
        'buyer_name' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date',
        'domain'     => 'nullable|string',
      ]);

      $groups = Group::orderBy('id', 'desc')->whereIn('id', auth()->user()->staff_group_ids)->get();
      $items  = ListItem::query();

      if (isset($payload['sold']) && $payload['sold'] === '1') {
        $items = $items->where('buyer_name', '!=', null);
      } elseif (isset($payload['sold']) && $payload['sold'] === '0') {
        $items = $items->where('buyer_name', null);
      }

      if (isset($payload['group']) && $payload['group'] !== null) {
        $items = $items->where('group_id', $payload['group']);
      }

      if (isset($payload['username']) && $payload['username'] !== null) {
        $items = $items->where('username', 'like', '%' . $payload['username'] . '%');
      }

      if (isset($payload['buyer_name']) && $payload['buyer_name'] !== null) {
        $items = $items->where('buyer_name', 'like', '%' . $payload['buyer_name'] . '%');
      }

      if (isset($payload['start_date']) && $payload['start_date'] !== null) {
        $items = $items->whereDate('created_at', '>=', $payload['start_date']);
      }

      if (isset($payload['end_date']) && $payload['end_date'] !== null) {
        $items = $items->whereDate('created_at', '<=', $payload['end_date']);
      }

      if (isset($payload['domain']) && $payload['domain'] !== null) {
        $items = $items->where('domain', 'like', '%' . $payload['domain'] . '%');
      }

      $items = $items->orderBy('id', 'desc')->where('staff_name', auth()->user()->username)->get();

      return view('staff.orders.accounts', compact('items', 'groups', 'payload'));
    } else {
      // $group = Group::findOrFail($id);

      // return view('staff.orders.accounts', compact('group'));
    }
  }
}
