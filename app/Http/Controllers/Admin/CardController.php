<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardList;

class CardController extends Controller
{
  public function index()
  {
    $cards = CardList::orderBy('id', 'desc')->limit(2000)->get();

    $total = CardList::where('status', 'Completed')->sum('value');
    $week  = CardList::where('status', 'Completed')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('value');
    $month = CardList::where('status', 'Completed')->whereMonth('created_at', now()->month)->whereYear('created_at', date('Y'))->sum('value');
    $today = CardList::where('status', 'Completed')->whereDate('created_at', now()->toDateString())->sum('value');

    $stats['cards']   = [
      'total' => $total,
      'week'  => $week,
      'month' => $month,
      'today' => $today,
    ];
    $stats['t_cards'] = [
      'total' => 'Toàn thời gian',
      'week'  => 'Tuần này',
      'month' => 'Tháng này',
      'today' => 'Hôm nay',
    ];

    return view('admin.cards.index', compact('cards', 'stats'));
  }
}
