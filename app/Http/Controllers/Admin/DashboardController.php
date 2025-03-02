<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    $check = checkLicenseKey(env('CLIENT_SECRET_KEY'));

    if ($check['status'] !== true) {
      return view('admin.license', compact('check'));
    }

    $stats = [];

    // Users stats
    $stats['users'] = [
      'total'                 => User::count(),
      'today'                 => User::whereDate('created_at', date('Y-m-d'))->count(),
      'balance'               => User::sum('balance'),
      'total_deposit'         => User::sum('total_deposit'),

      'total_card_deposit_td' => Transaction::where('type', 'deposit-card')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
      'total_card_deposit_mo' => Transaction::where('type', 'deposit-card')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount'),

      'total_bank_deposit_td' => Transaction::whereIn('type', ['deposit-bank', 'deposit'])->whereDate('created_at', date('Y-m-d'))->sum('amount'),
      'total_bank_deposit_mo' => Transaction::whereIn('type', ['deposit-bank', 'deposit'])->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount'),

      'total_deposit_today'   => Transaction::whereIn('type', ['deposit-bank', 'deposit-card'])->whereDate('created_at', date('Y-m-d'))->sum('amount'),
    ];

    // => translate
    $stats['t_users'] = [
      'total'                 => 'Thành Viên',
      'balance'               => 'Tổng Số Dư',
      'today'                 => 'Thành Viên mới',
      'total_deposit'         => 'Tổng Tiền Nạp',
      'total_card_deposit_td' => 'Nạp Thẻ Hôm Nay',
      'total_card_deposit_mo' => 'Nạp Thẻ Tháng ' . date('m'),

      'total_bank_deposit_td' => 'Nạp Tiền Hôm Nay',
      'total_bank_deposit_mo' => 'Nạp Tiền Tháng ' . date('m'),

      'total_deposit_today'   => 'Tổng Tiền Nạp Hôm Nay',
    ];

    if (auth()->user()->role === 'partner') {
      // Accounts Order stats // account-buy
      $stats['accounts'] = [
        'total_order'            => Transaction::where('type', 'account-buy')->count(),
        'total_order_today'      => Transaction::where('type', 'account-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_order_yesterday'  => Transaction::where('type', 'account-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count(),

        'total_amount'           => Transaction::where('type', 'account-buy')->sum('amount'),
        'total_amount_week'      => Transaction::where('type', 'account-buy')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),
        'total_amount_today'     => Transaction::where('type', 'account-buy')
          ->whereDate('created_at', date('Y-m-d'))
          ->sum('amount'),
        'total_amount_month'     => Transaction::where('type', 'account-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_amount_yesterday' => Transaction::where('type', 'account-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),
      ];

      $stats['t_accounts'] = [
        'total_order'            => 'Tổng Đơn Hàng',
        'total_order_today'      => 'Đơn Hàng Hôm Nay',
        'total_order_yesterday'  => 'Đơn Hàng Hôm Qua',

        'total_amount'           => 'Tổng Doanh Thu',
        'total_amount_week'      => 'Doanh Thu Tuần',
        'total_amount_today'     => 'Doanh Thu Hôm Nay',
        'total_amount_month'     => 'Doanh Thu Tháng',
        'total_amount_yesterday' => 'Doanh Thu Hôm Qua',
      ];

      // Accounts Order stats // account-v2-buy
      $stats['accounts_v2'] = [
        'total_order'            => Transaction::where('type', 'account-v2-buy')->count(),
        'total_order_today'      => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_order_yesterday'  => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count(),

        'total_amount'           => Transaction::where('type', 'account-v2-buy')->sum('amount'),
        'total_amount_week'      => Transaction::where('type', 'account-v2-buy')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),
        'total_amount_today'     => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_amount_month'     => Transaction::where('type', 'account-v2-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_amount_yesterday' => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),


      ];

      $stats['t_accounts_v2'] = [
        'total_order'            => 'Tổng Đơn Hàng',
        'total_order_today'      => 'Đơn Hàng Hôm Nay',
        'total_order_yesterday'  => 'Đơn Hàng Hôm Qua',

        'total_amount'           => 'Tổng Doanh Thu',
        'total_amount_week'      => 'Doanh Thu Tuần',
        'total_amount_today'     => 'Doanh Thu Hôm Nay',
        'total_amount_month'     => 'Doanh Thu Tháng',
        'total_amount_yesterday' => 'Doanh Thu Hôm Qua',
      ];

      // Items Order stats // item-buy
      $stats['items'] = [
        'total_order'             => Transaction::where('type', 'item-buy')->count(),
        'total_order_today'       => Transaction::where('type', 'item-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_order_yesterday'   => Transaction::where('type', 'item-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count(),

        'total_amount'            => Transaction::where('type', 'item-buy')->sum('amount'),
        'total_refund'            => Transaction::where('type', 'item-refund')->sum('amount'),

        'total_amount_week'       => Transaction::where('type', 'item-buy')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),
        'total_refund_week'       => Transaction::where('type', 'item-refund')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),

        'total_amount_today'      => Transaction::where('type', 'item-buy')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_refund_today'      => Transaction::where('type', 'item-refund')->whereDate('created_at', date('Y-m-d'))->sum('amount'),


        'total_amount_month'      => Transaction::where('type', 'item-buy')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount'),
        'total_refund_month'      => Transaction::where('type', 'item-refund')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount'),

        'total_amount_last_month' => Transaction::where('type', 'item-buy')->whereDate('created_at', now()->subMonth()->format('m'))->whereYear('created_at', now()->subMonth()->format('Y'))->sum('amount'),
        'total_refund_last_month' => Transaction::where('type', 'item-refund')->whereDate('created_at', now()->subMonth()->format('m'))->whereYear('created_at', now()->subMonth()->format('Y'))->sum('amount'),

        'total_amount_yesterday'  => Transaction::where('type', 'item-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),
        'total_refund_yesterday'  => Transaction::where('type', 'item-refund')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),

      ];

      $stats['t_items'] = [
        'total_order'             => 'Tổng Đơn Hàng',
        'total_order_today'       => 'Đơn Hàng Hôm Nay',
        'total_order_yesterday'   => 'Đơn Hàng Hôm Qua',

        'total_amount'            => 'Tổng Doanh Thu',
        'total_refund'            => 'Tổng Hoàn Tiền',

        'total_amount_week'       => 'Doanh Thu Tuần',
        'total_refund_week'       => 'Hoàn Tiền Tuần',

        'total_amount_today'      => 'Doanh Thu Hôm Nay',
        'total_refund_today'      => 'Hoàn Tiền Hôm Nay',

        'total_amount_month'      => 'Doanh Thu Tháng',
        'total_refund_month'      => 'Hoàn Tiền Tháng',

        'total_amount_last_month' => 'Doanh Thu Tháng Trước',
        'total_refund_last_month' => 'Hoàn Tiền Tháng Trước',

        'total_amount_yesterday'  => 'Doanh Thu Hôm Qua',
        'total_refund_yesterday'  => 'Hoàn Tiền Hôm Qua',
      ];

      // Boosting Order stats // boosting-buy
      $stats['boostings'] = [
        'total_order'             => Transaction::where('type', 'boosting-buy')->count(),
        'total_order_today'       => Transaction::where('type', 'boosting-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_order_yesterday'   => Transaction::where('type', 'boosting-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count(),

        'total_amount'            => Transaction::where('type', 'boosting-buy')->sum('amount'),
        'total_refund'            => Transaction::where('type', 'boosting-refund')->sum('amount'),

        'total_amount_week'       => Transaction::where('type', 'boosting-buy')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),
        'total_refund_week'       => Transaction::where('type', 'boosting-refund')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),

        'total_amount_today'      => Transaction::where('type', 'boosting-buy')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_refund_today'      => Transaction::where('type', 'boosting-refund')->whereDate('created_at', date('Y-m-d'))->sum('amount'),


        'total_amount_month'      => Transaction::where('type', 'boosting-buy')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount'),
        'total_refund_month'      => Transaction::where('type', 'boosting-refund')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount'),

        'total_amount_last_month' => Transaction::where('type', 'boosting-buy')->whereDate('created_at', now()->subMonth()->format('m'))->whereYear('created_at', now()->subMonth()->format('Y'))->sum('amount'),
        'total_refund_last_month' => Transaction::where('type', 'boosting-refund')->whereDate('created_at', now()->subMonth()->format('m'))->whereYear('created_at', now()->subMonth()->format('Y'))->sum('amount'),

        'total_amount_yesterday'  => Transaction::where('type', 'boosting-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),
        'total_refund_yesterday'  => Transaction::where('type', 'boosting-refund')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),
      ];
    } else {
      // Accounts Order stats // account-buy
      $stats['accounts'] = [
        'total_order'            => Transaction::where('type', 'account-buy')->count(),
        'total_amount'           => Transaction::where('type', 'account-buy')->sum('amount'),
        'total_order_today'      => Transaction::where('type', 'account-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_order_yesterday'  => Transaction::where('type', 'account-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count(),

        'total_amount_week'      => Transaction::where('type', 'account-buy')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),
        'total_amount_today'     => Transaction::where('type', 'account-buy')
          ->whereDate('created_at', date('Y-m-d'))
          ->sum('amount'),
        'total_amount_month'     => Transaction::where('type', 'account-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_amount_yesterday' => Transaction::where('type', 'account-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),
      ];

      $stats['accounts']['total_profit']       = $stats['accounts']['total_amount'] - Transaction::where('type', 'account-buy')->sum('cost_amount');
      $stats['accounts']['total_profit_today'] = $stats['accounts']['total_amount_today'] - Transaction::where('type', 'account-buy')
        ->whereDate('created_at', date('Y-m-d'))
        ->sum('cost_amount');
      // $stats['accounts']['total_profit_week']       = $stats['accounts']['total_amount_week'] - Transaction::where('type', 'account-buy')
      //   ->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])
      //   ->sum('cost_amount');
      $stats['accounts']['total_profit_month'] = $stats['accounts']['total_amount_month'] - Transaction::where('type', 'account-buy')
        ->whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->sum('cost_amount');

      $stats['accounts']['total_profit_last_month'] = $stats['accounts']['total_amount_yesterday'] - Transaction::where('type', 'account-buy')
        ->whereDate('created_at', now()->subMonth()->format('m'))
        ->whereYear('created_at', now()->subMonth()->format('Y'))
        ->sum('cost_amount');



      // $stats['accounts']['total_profit_yesterday'] = $stats['accounts']['total_amount_yesterday'] - Transaction::where('type', 'account-buy')
      //   ->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))
      //   ->sum('cost_amount');


      $stats['t_accounts'] = [
        'total_order'             => 'Tổng Đơn Hàng',
        'total_order_today'       => 'Đơn Hàng Hôm Nay',
        'total_order_yesterday'   => 'Đơn Hàng Hôm Qua',

        'total_amount'            => 'Tổng Doanh Thu',
        'total_amount_week'       => 'Doanh Thu Tuần',
        'total_amount_today'      => 'Doanh Thu Hôm Nay',
        'total_amount_month'      => 'Doanh Thu Tháng',
        'total_amount_yesterday'  => 'Doanh Thu Hôm Qua',

        'total_profit'            => 'Tổng Lợi Nhuận',
        'total_profit_week'       => 'Lợi Nhuận Tuần #' . date('W'),
        'total_profit_today'      => 'Lợi Nhuận Hôm ' . date('d/m/Y'),
        'total_profit_month'      => 'Lợi Nhuận Tháng ' . date('m/Y'),
        'total_profit_last_month' => 'Lợi Nhuận Tháng Trước',
        'total_profit_yesterday'  => 'Lợi Nhuận Hôm Qua',
      ];

      // Accounts Order stats // account-v2-buy
      $stats['accounts_v2'] = [
        'total_order'            => Transaction::where('type', 'account-v2-buy')->count(),
        'total_amount'           => Transaction::where('type', 'account-v2-buy')->sum('amount'),
        'total_order_today'      => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_order_yesterday'  => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count(),

        'total_amount_week'      => Transaction::where('type', 'account-v2-buy')->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])->sum('amount'),
        'total_amount_today'     => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_amount_month'     => Transaction::where('type', 'account-v2-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_amount_yesterday' => Transaction::where('type', 'account-v2-buy')->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->sum('amount'),


      ];

      $stats['accounts_v2']['total_profit'] = $stats['accounts_v2']['total_amount'] - Transaction::where('type', 'account-v2-buy')->sum('cost_amount');
      // $stats['accounts_v2']['total_profit_week']      = $stats['accounts_v2']['total_amount_week'] - Transaction::where('type', 'account-v2-buy')
      //   ->whereBetween('created_at', [date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))])
      //   ->sum('cost_amount');
      $stats['accounts_v2']['total_profit_today'] = $stats['accounts_v2']['total_amount_today'] - Transaction::where('type', 'account-v2-buy')
        ->whereDate('created_at', date('Y-m-d'))
        ->sum('cost_amount');
      $stats['accounts_v2']['total_profit_month'] = $stats['accounts_v2']['total_amount_month'] - Transaction::where('type', 'account-v2-buy')
        ->whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->sum('cost_amount');
      // $stats['accounts_v2']['total_profit_yesterday'] = $stats['accounts_v2']['total_amount_yesterday'] - Transaction::where('type', 'account-v2-buy')
      //   ->whereDate('created_at', date('Y-m-d', strtotime('-1 day')))
      //   ->sum('cost_amount');
      $stats['accounts_v2']['total_profit_last_month'] = $stats['accounts_v2']['total_amount_yesterday'] - Transaction::where('type', 'account-v2-buy')
        ->whereMonth('created_at', date('m', strtotime('-1 month')))
        ->whereYear('created_at', date('Y'))
        ->sum('cost_amount');


      $stats['t_accounts_v2'] = [
        'total_order'             => 'Tổng Đơn Hàng',
        'total_order_today'       => 'Đơn Hàng Hôm Nay',
        'total_order_yesterday'   => 'Đơn Hàng Hôm Qua',

        'total_amount'            => 'Tổng Doanh Thu',
        'total_amount_week'       => 'Doanh Thu Tuần',
        'total_amount_today'      => 'Doanh Thu Hôm Nay',
        'total_amount_month'      => 'Doanh Thu Tháng',
        'total_amount_yesterday'  => 'Doanh Thu Hôm Qua',

        'total_profit'            => 'Tổng Lợi Nhuận',
        'total_profit_week'       => 'Lợi Nhuận Tuần S' . date('W'),
        'total_profit_today'      => 'Lợi Nhuận Hôm ' . date('d/m/Y'),
        'total_profit_month'      => 'Lợi Nhuận Tháng ' . date('m/Y'),
        'total_profit_yesterday'  => 'Lợi Nhuận Hôm Qua',
        'total_profit_last_month' => 'Lợi Nhuận Tháng Trước',
      ];

      // Items Order stats // item-buy
      $stats['items'] = [
        'total_order'        => Transaction::where('type', 'item-buy')->count(),
        'total_amount'       => Transaction::where('type', 'item-buy')->sum('amount'),
        'total_refund'       => Transaction::where('type', 'item-refund')->sum('amount'),
        'total_profit'       => Transaction::where('type', 'item-buy')->sum('amount') - Transaction::where('type', 'item-refund')->sum('amount'),

        'total_order_today'  => Transaction::where('type', 'item-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_amount_today' => Transaction::where('type', 'item-buy')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_refund_today' => Transaction::where('type', 'item-refund')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_profit_today' => Transaction::where('type', 'item-buy')
          ->whereDate('created_at', date('Y-m-d'))
          ->sum('amount') - Transaction::where('type', 'item-refund')
            ->whereDate('created_at', date('Y-m-d'))
            ->sum('amount'),

        'total_order_month'  => Transaction::where('type', 'item-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->count(),
        'total_amount_month' => Transaction::where('type', 'item-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_refund_month' => Transaction::where('type', 'item-refund')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_profit_month' => Transaction::where('type', 'item-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount') - Transaction::where('type', 'item-refund')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('amount'),
      ];

      $stats['t_items'] = [
        'total_order'           => 'Tổng Đơn Hàng',
        'total_amount'          => 'Tổng Doanh Thu',
        'total_refund'          => 'Tổng Hoàn Tiền',
        'total_profit'          => 'Tổng Lợi Nhuận',
        'total_order_today'     => 'Đơn Hàng Hôm Nay',
        'total_order_yesterday' => 'Đơn Hàng Hôm Qua',
        'total_profit_today'    => 'Lợi Nhuận Hôm Nay',
        'total_order_month'     => 'Đơn Hàng Tháng',
        'total_profit_month'    => 'Lợi Nhuận Tháng',

        'total_amount_today'    => 'Doanh Thu Hôm Nay',
        'total_refund_today'    => 'Hoàn Tiền Hôm Nay',

        'total_amount_month'    => 'Doanh Thu Tháng',
        'total_refund_month'    => 'Hoàn Tiền Tháng',
      ];

      // Boosting Order stats // boosting-buy
      $stats['boostings'] = [
        'total_order'        => Transaction::where('type', 'boosting-buy')->count(),
        'total_amount'       => Transaction::where('type', 'boosting-buy')->sum('amount'),
        'total_refund'       => Transaction::where('type', 'boosting-refund')->sum('amount'),
        'total_profit'       => Transaction::where('type', 'boosting-buy')->sum('amount') - Transaction::where('type', 'boosting-refund')->sum('amount'),

        'total_order_today'  => Transaction::where('type', 'boosting-buy')->whereDate('created_at', date('Y-m-d'))->count(),
        'total_amount_today' => Transaction::where('type', 'boosting-buy')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_refund_today' => Transaction::where('type', 'boosting-refund')->whereDate('created_at', date('Y-m-d'))->sum('amount'),
        'total_profit_today' => Transaction::where('type', 'boosting-buy')
          ->whereDate('created_at', date('Y-m-d'))
          ->sum('amount') - Transaction::where('type', 'boosting-refund')
            ->whereDate('created_at', date('Y-m-d'))
            ->sum('amount'),

        'total_order_month'  => Transaction::where('type', 'boosting-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->count(),
        'total_amount_month' => Transaction::where('type', 'boosting-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_refund_month' => Transaction::where('type', 'boosting-refund')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount'),
        'total_profit_month' => Transaction::where('type', 'boosting-buy')
          ->whereMonth('created_at', date('m'))
          ->whereYear('created_at', date('Y'))
          ->sum('amount') - Transaction::where('type', 'boosting-refund')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('amount'),
      ];
    }

    return view('admin.dashboard', compact('stats'));
  }
}
