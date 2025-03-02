<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\SpinQuest;
use App\Models\SpinQuestLog;
use Helper;
use Illuminate\Http\Request;

class SpinQuestController extends Controller
{
  public function createFakeData(Request $request)
  {
    $rows = SpinQuest::where('status', true)->get();
    $unit = Helper::getConfig('mng_withdraw')['unit'] ?? null;

    foreach ($rows as $row) {
      if ($row->canPlay() !== true) {
        echo 'Can not play this SpinQuest #' . $row->id . ' <br />';
        continue;
      }
      // Create fake data, get random in ./users.txt
      $file           = resource_path('fake/users.txt');
      $file_arr       = file($file);
      $num_lines      = count($file_arr);
      $last_arr_index = $num_lines - 1;
      $rand_index     = rand(0, $last_arr_index);
      $rand_text      = $file_arr[$rand_index];

      if (strlen($rand_text) > 10) {
        $rand_text = substr($rand_text, 0, 10);
      }

      $type   = 'legacy';
      $kqNhan = rand(300, 2599);

      try {
        if (!$unit && !$row->invar_id) {
          continue;
        }

        if ($unit && !$row->invar_id) {
          $unit = $unit['unit'] ?? null;

          if (!$unit) {
            continue;
          }

          $type = 'legacy';
        } else if ($row->invar_id) {
          $unit = $row->inventoryVar;

          if (!$unit) {
            return response()->json([
              'status'  => 400,
              'message' => 'Không có dữ liệu phần thưởng, vui lòng thử lại #3',
            ], 400);
          }

          $type = 'inventory';
        }

        $result = $row->playGame(true);

        if ($result['data'] === null && $result['location'] === null) {
          continue;
        }

        $kqNhan = $result['data']['value'];

        if (!is_numeric($kqNhan)) {
          $exp = explode('-', $kqNhan);
          if (count($exp) === 2 && is_numeric($exp[0]) && is_numeric($exp[1])) {
            $kqNhan = mt_rand($exp[0], $exp[1]);
          } else {
            continue;
          }
        }
      } catch (\Exception $e) {
        continue;
      }

      if ($type === 'legacy') {
        $content = "Bạn đã nhận được {$kqNhan} {$unit} từ trò chơi này!";
      } else if ($type === 'inventory') {
        $content = 'Bạn đã quay trúng ' . number_format($kqNhan, 0, ',', '.') . ' ' . $unit->unit . '!';
      }

      $log = SpinQuestLog::create([
        'unit'          => $type === 'legacy' ? $unit : $unit->unit,
        'prize'         => $kqNhan,
        'price'         => 0,
        'status'        => 'Completed',
        'content'       => $content,
        'user_id'       => 0,
        'username'      => $rand_text,
        'is_fake_data'  => false,
        'spin_quest_id' => $row->id,
      ]);

      $row->increment('play_times', 1);

      if ($log) {
        echo "Created fake data for SpinQuest ID: {$log->id}; Spin: {$row->name} <br />";
      }
    }
  }
}
