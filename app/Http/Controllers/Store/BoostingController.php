<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoostingController extends Controller
{
  public function index($slug)
  {
    $group = \App\Models\GBGroup::where('status', true)->where('slug', $slug)->firstOrFail();

    return view('store.boosting', compact('group'), [
      'pageTitle' => 'Xem sản phẩm ' . $group->name,
    ]);
  }
}