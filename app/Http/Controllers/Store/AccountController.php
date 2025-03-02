<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ListItem;

class AccountController extends Controller
{
  public function index($slug)
  {
    $group = \App\Models\Group::where('status', true)->where('slug', $slug)->firstOrFail();

    $meta_seo = $group->meta_seo;

    return view('store.account', compact('group', 'meta_seo'), [
      'pageTitle' => 'Xem sản phẩm ' . $group->name,
    ]);
  }

  public function show($code)
  {
    $item = ListItem::where('code', $code)->firstOrFail();

    if ($item === null) {
      return redirect(route('home'))->with('error', 'Không tìm thấy sản phẩm này!');
    }

    if ($item->is_sold === true && $item->buyer_name !== auth()->user()?->username) {
      // return redirect()->back()->with('error', 'Sản phẩm này đã được bán!');
      return abort(403);
    }

    return view('store.account-show', compact('item'), [
      'pageTitle' => 'Xem sản phẩm ' . $item->name,
    ]);
  }
}