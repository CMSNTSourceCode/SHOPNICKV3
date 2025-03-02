<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\User;
use App\Models\VoucherLog;
use App\Models\WalletLog;
use App\Utils\ServerCtl;
use App\Utils\Service;
use Helper;
use Illuminate\Http\Request;

class PageController extends Controller
{
  public function privacyPolicy()
  {
    return view('pages.privacy-policy');
  }

  public function affiliates()
  {
    $user      = User::findOrFail(auth()->id());
    $config    = Helper::getConfig('affiliate_config');
    $histories = WalletLog::where('user_id', auth()->id())->orderBy('id', 'desc')->limit(100)->get();


    $affiliate = Affiliate::where('username', $user->username)->firstOrCreate(['code' => $user->referral_code, 'username' => $user->username]);

    return view('pages.affiliates', compact('user', 'config', 'histories', 'affiliate'));
  }

  public function apiDocs()
  {
    return view('pages.api-docs');
  }

  public function websiteBuilder()
  {
    return view('pages.website-builder');
  }

  public function contact()
  {
    return view('pages.contact');
  }
}
