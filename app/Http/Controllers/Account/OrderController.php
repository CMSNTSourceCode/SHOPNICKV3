<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\BulkOrder;
use App\Models\GBOrder;
use App\Models\ItemOrder;
use App\Models\ListItem;
use App\Models\ResourceV2;

use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function items($code = null)
  {
    if ($code !== null) {
      $item = ItemOrder::where('code', $code)->where('user_id', auth()->user()->id)->firstOrFail();

      return view('account.orders.item-info', [
        'pageTitle' => 'Thông Tin Đơn Hàng',
      ], compact('item'));
    }

    $stats = [
      'total'            => ItemOrder::where('user_id', auth()->user()->id)->count(),
      'payment'          => ItemOrder::where('user_id', auth()->user()->id)->where('payment', '!=', null)->sum('payment'),
      'total_in_month'   => ItemOrder::where('user_id', auth()->user()->id)->whereMonth('created_at', date('m'))->count(),
      'payment_in_month' => ItemOrder::where('user_id', auth()->user()->id)->where('payment', '!=', null)->whereMonth('created_at', date('m'))->sum('payment'),
    ];

    $items = ItemOrder::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(12);

    return view('account.orders.items', [
      'pageTitle' => 'Lịch Sử Mua Vật Phẩm',
    ], compact('items', 'stats'));
  }

  public function accounts($code = null)
  {
    if ($code !== null) {
      $account = ListItem::where('code', $code)->where('buyer_name', auth()->user()->username)->firstOrFail();

      return view('account.orders.account-info', [
        'pageTitle' => 'Thông Tin Tài Khoản',
      ], compact('account'));
    }

    $stats = [
      'total'            => ListItem::where('buyer_name', auth()->user()->username)->count(),
      'payment'          => ListItem::where('buyer_name', auth()->user()->username)->where('buyer_paym', '!=', null)->sum('buyer_paym'),
      'total_in_month'   => ListItem::where('buyer_name', auth()->user()->username)->whereMonth('buyer_date', date('m'))->count(),
      'payment_in_month' => ListItem::where('buyer_name', auth()->user()->username)->where('buyer_paym', '!=', null)->whereMonth('buyer_date', date('m'))->sum('buyer_paym'),
    ];

    $accounts = ListItem::where('buyer_name', auth()->user()->username)->orderBy('buyer_date', 'desc')->paginate(12);

    return view('account.orders.accounts', [
      'pageTitle' => 'Lịch Sử Mua Nick',
    ], compact('accounts', 'stats'));
  }


  public function accountsV2($code = null)
  {
    if ($code !== null) {
      $account = ResourceV2::where('buyer_code', $code)->where('is_bulk', false)->where('buyer_name', auth()->user()->username)->firstOrFail();

      return view('account.orders.account-info-v2', [
        'pageTitle' => 'Thông Tin Tài Khoản V2',
      ], compact('account'));
    }

    $stats = [
      'total'            => ResourceV2::where('buyer_name', auth()->user()->username)->where('is_bulk', false)->count(),
      'payment'          => ResourceV2::where('buyer_name', auth()->user()->username)->where('is_bulk', false)->where('buyer_paym', '!=', null)->sum('buyer_paym'),
      'total_in_month'   => ResourceV2::where('buyer_name', auth()->user()->username)->where('is_bulk', false)->whereMonth('buyer_date', date('m'))->count(),
      'payment_in_month' => ResourceV2::where('buyer_name', auth()->user()->username)->where('is_bulk', false)->where('buyer_paym', '!=', null)->whereMonth('buyer_date', date('m'))->sum('buyer_paym'),
    ];

    $accounts = ResourceV2::where('buyer_name', auth()->user()->username)->where('is_bulk', false)->orderBy('buyer_date', 'desc')->paginate(12);

    return view('account.orders.accounts-v2', [
      'pageTitle' => 'Lịch Sử Mua Nick V2',
    ], compact('accounts', 'stats'));
  }

  public function bulkOrders(Request $request, $code = null)
  {
    if ($code !== null) {
      $account = BulkOrder::where('code', $code)->where('user_id', auth()->user()->id)->firstOrFail();

      return view('account.orders.bulk-info-orders', [
        'pageTitle' => 'Thông Tin Đơn Hàng',
      ], compact('account'));
    }

    $stats = [
      'total'            => BulkOrder::where('user_id', auth()->user()->id)->count(),
      'payment'          => BulkOrder::where('user_id', auth()->user()->id)->sum('payment'),
      'total_in_month'   => BulkOrder::where('user_id', auth()->user()->id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count(),
      'payment_in_month' => BulkOrder::where('user_id', auth()->user()->id)->whereMonth('created_at', operator: date('m'))->whereYear('created_at', date('Y'))->sum('payment'),
    ];

    $accounts = BulkOrder::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(12);

    return view('account.orders.bulk-orders', [
      'pageTitle' => 'Lịch Sử Mua Nick V2',
    ], compact('accounts', 'stats'));
  }

  public function boosting($code = null)
  {
    if ($code !== null) {
      $item = GBOrder::where('code', $code)->where('user_id', auth()->user()->id)->firstOrFail();

      return view('account.orders.boosting-info', [
        'pageTitle' => 'Thông Tin Đơn Hàng',
      ], compact('item'));
    }

    $stats = [
      'total'            => GBOrder::where('user_id', auth()->user()->id)->count(),
      'payment'          => GBOrder::where('user_id', auth()->user()->id)->where('payment', '!=', null)->sum('payment'),
      'total_in_month'   => GBOrder::where('user_id', auth()->user()->id)->whereMonth('created_at', date('m'))->count(),
      'payment_in_month' => GBOrder::where('user_id', auth()->user()->id)->where('payment', '!=', null)->whereMonth('created_at', date('m'))->sum('payment'),
    ];

    $items = GBOrder::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(12);

    return view('account.orders.boostings', [
      'pageTitle' => 'Lịch Sử Cày Thuê',
    ], compact('items', 'stats'));
  }

}
