<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\PinGroup;
use App\Models\Transaction;
use App\Models\User;
use Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $pin_groups          = PinGroup::orderBy('id', 'desc')->where('status', true)->get();
    $itemCategories      = \App\Models\ItemCategory::where('status', true)->orderBy('priority', 'desc')->get();
    $accountCategories   = \App\Models\Category::where('status', true)->orderBy('priority', 'desc')->get();
    $boostingCategories  = \App\Models\GBCategory::where('status', true)->orderBy('priority', 'desc')->get();
    $accountV2Categories = \App\Models\CategoryV2::where('status', true)->orderBy('priority', 'desc')->get();

    $top10UserDeposit = \App\Models\Transaction::selectRaw('username, sum(amount) as total')
      ->whereIn('type', ['deposit-bank', 'deposit-card', 'deposit'])
      ->groupBy('username')
      ->orderBy('total', 'desc')
      ->whereMonth('created_at', date('m'))
      ->whereYear('created_at', date('Y'))
      ->limit(5)
      ->get();

    // except role admin
    foreach ($top10UserDeposit as $key => $value) {
      $user = User::where('username', $value->username)->first();

      if ($user !== null && $user->role === 'admin') {
        unset($top10UserDeposit[$key]);
      }
    }

    // $transactions = \App\Models\Transaction::where('type', 'account-buy')
    //   ->whereOr('type', 'account-v2-buy')
    //   ->where('created_at', '>=', now()->subHours(24))
    //   ->orderBy('id', 'desc')
    //   ->get();
    $transactions = Transaction::where(function ($sub) {
      $sub->where('type', 'account-buy')
        ->orWhere('type', 'account-v2-buy');
    })->where('created_at', '>=', now()->subHours(24))
      ->orderBy('id', 'desc')
      ->get();

    $listAccountBuy = "";

    $lang = currentLang();

    if ($lang === 'vn') {
      foreach ($transactions as $transaction) {
        $listAccountBuy .= "<span style=\"color: #504099\">" . Helper::hideUsername($transaction->username) . "</span> " . __t('cách đây') . " <span style=\"color: #E25E3E\">" . Helper::getTimeAgo($transaction->created_at) . "</span> " . __t('đã mua tài khoản') . " <span style=\"color: #279EFF\">#" . ($transaction->extras['code'] ?? $transaction['extras']['account_id'] ?? '-') . "</span> - " . __t('Giá') . " <span style=\"color: #4D2DB7\">" . Helper::formatCurrency($transaction->amount) . "</span> | \n";
      }
    } else {
      foreach ($transactions as $transaction) {
        $listAccountBuy .= "<span style=\"color: #504099\">" . Helper::hideUsername($transaction->username) . "</span> purchased account <span style=\"color: #279EFF\">#" . ($transaction->extras['code'] ?? $transaction['extras']['account_id'] ?? '-') . "</span> <span style=\"color: #E25E3E\">" . Helper::getTimeAgo($transaction->created_at) . "</span> for <span style=\"color: #4D2DB7\">" . Helper::formatCurrency($transaction->amount) . "</span> | \n";
      }
    }

    return view('index', compact('pin_groups', 'accountCategories', 'accountV2Categories', 'itemCategories', 'boostingCategories', 'top10UserDeposit', 'listAccountBuy'), [
      'pageTitle' => 'Mua Tài Khoản / Vật Phẩm',
    ]);
  }

  public function ref($ref = null)
  {

    if (Auth::check()) {
      return redirect()->route('home')->with('error', 'Bạn đã đăng nhập, không thể nhập mã giới thiệu.');
    }

    if (is_null($ref)) {
      return redirect()->route('home')->with('error', 'Mã giới thiệu không tồn tại.');
    }

    $affiliate = Affiliate::where('code', $ref)->first();

    if (is_null($affiliate)) {
      return redirect()->route('home')->with('error', 'Mã giới thiệu không tồn tại.');
    }

    if (Cookie::has('ref_id') && Cookie::get('ref_id') === $affiliate->code) {
      return redirect()->route('home')->with('error', 'Bạn đã nhập mã giới thiệu này rồi.');
    }

    // set cookie for ref, expire after 7 days
    Cookie::queue('ref_id', $affiliate->code, 10080);
    // +1 click
    $affiliate->update([
      'clicks' => $affiliate->clicks + 1
    ]);


    return redirect()->route("home")->with("success", __t("Bạn đã nhập mã giới thiệu thành công, hãy đăng ký trong 7 ngày."));
  }
}
