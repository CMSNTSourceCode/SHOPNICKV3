<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
  public function index($slug)
  {
    $group        = \App\Models\ItemGroup::where('status', true)->where('slug', $slug)->firstOrFail();
    $loginWith    = [];
    $defaultLogin = ["Riot", "Garena", "Steam", "Facebook", "Google", "Roblox", "Other"];
    //

    if (count($group->login_with ?? []) === 0) {
      foreach ($defaultLogin as $value) {
        $loginWith[] = [
          'value' => ucfirst($value),
          'label' => __t('Đăng nhập bằng') . ' ' . ucfirst($value),
        ];
      }
    } else {
      foreach ($group->login_with as $value) {
        $loginWith[] = [
          'value' => ucfirst($value),
          'label' => __t('Đăng nhập bằng') . ' ' . ucfirst($value),
        ];
      }
    }

    return view('store.item', compact('group', 'loginWith'), [
      'pageTitle' => 'Xem sản phẩm ' . $group->name,
    ]);
  }

}
