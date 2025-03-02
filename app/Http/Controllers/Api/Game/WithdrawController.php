<?php

namespace App\Http\Controllers\Api\Game;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawLog;
use Helper;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'      => 'nullable|integer',
      'limit'     => 'nullable|integer',
      'search'    => 'nullable|string',
      'sort_by'   => 'nullable|string',
      'sort_type' => 'nullable|string|in:asc,desc',
    ]);

    $query = WithdrawLog::where('user_id', auth()->user()->id);

    if (isset($payload['search'])) {
      $query = $query->where('content', 'like', '%' . $payload['search'] . '%')
        ->orWhere('ip_address', 'like', '%' . $payload['search'] . '%');
    }

    if (isset($payload['sort_by'])) {
      $query = $query->orderBy($payload['sort_by'], $payload['sort_type'] ?? 'asc');
    }

    $meta = [
      'page'       => (int) ($payload['page'] ?? 1),
      'limit'      => (int) ($payload['limit'] ?? 10),
      'total_rows' => $query->count(),
      'total_page' => ceil($query->count() / ($payload['limit'] ?? 10)),
    ];

    $data = $query->skip(($meta['page'] - 1) * $meta['limit'])->take($meta['limit']);

    return response()->json([
      'data'    => [
        'meta' => $meta,
        'data' => $data->get(),
      ],
      'status'  => 200,
      'message' => 'Lấy lịch sử rút tiền thành công',
    ], 200);

  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'value'     => 'required|integer|min:10',
      'user_note' => 'required|string|max:255'
    ]);

    $user   = User::findOrFail(auth()->user()->id);
    $value  = $payload['value'];
    $config = Helper::getConfig('mng_withdraw');

    if (isset($config['min_withdraw']) && $value < $config['min_withdraw']) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số lượng rút tối thiểu là ' . number_format($config['min_withdraw']) . ' ' . $config['unit'],
      ], 400);
    }

    if (isset($config['max_withdraw']) && $value > $config['max_withdraw']) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số lượng rút tối đa là ' . number_format($config['max_withdraw']) . ' ' . $config['unit'],
      ], 400);
    }

    if ($user->balance_2 < $value) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số dư không đủ để rút, vui lòng chơi thêm.',
      ], 400);
    }

    if (!$user->decrement('balance_2', $value)) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không thể thực hiện trừ tiền trong tài khoản của bạn #3',
      ], 400);
    }

    $log = WithdrawLog::create([
      'unit'            => $config['unit'] ?? 'Coin',
      'value'           => $value,
      'status'          => 'Pending',
      'user_note'       => $payload['user_note'],
      'admin_note'      => '',
      'user_id'         => $user->id,
      'username'        => $user->username,
      'current_balance' => $user->balance_2,
    ]);

    return response()->json([
      'status'  => 200,
      'message' => 'Tạo yêu cầu rút #' . $log->id . ' thành công, vui lòng chờ xác nhận.',
    ], 200);
  }
}
