<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\SpinQuest;
use Illuminate\Http\Request;

class SpinQuestController extends Controller
{
  public function index($id = null)
  {
    if ($id !== null) {
      $spinQuest = SpinQuest::where('id', $id)->where('status', true)->firstOrFail();

      return view('game.spin-quest.show', [
        'pageTitle' => $spinQuest->name,
      ], compact('spinQuest'));
    }
    $spinQuests = SpinQuest::where('status', true)->orderBy('priority', 'desc')->get();

    return view('game.spin-quest.index', [
      'pageTitle' => 'Vòng quay may mắn',
    ], compact('spinQuests'));
  }
}
