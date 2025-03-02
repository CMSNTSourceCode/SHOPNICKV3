<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\BulkOrder;
use App\Models\GroupV2;
use App\Models\ListItemV2;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccountV2Controller extends Controller
{
  public function index(Request $request)
  {
    $payload    = $request->validate([
      'page'       => 'nullable|integer',
      'limit'      => 'nullable|integer',
      'price'      => 'nullable|string',
      'search'     => 'nullable|string',
      'sort_by'    => 'nullable|string',
      'group_id'   => 'required|integer',
      'sort_type'  => 'nullable|string|in:asc,desc',
      'display_by' => 'nullable|string|in:created_at_asc,created_at_desc,price_asc,price_desc,priority_asc,priority_desc',
    ]);
    $page       = $payload['page'] ?? 1;
    $limit      = $payload['limit'] ?? 10;
    $search     = $payload['search'] ?? null;
    $offset     = ($page - 1) * $limit;
    $sort_by    = $payload['sort_by'] ?? 'id';
    $sort_type  = $payload['sort_type'] ?? 'desc';
    $display_by = $payload['display_by'] ?? null;

    $group = GroupV2::where('id', $payload['group_id'])->where('status', true)->first();

    if ($group === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy nhóm dịch vụ này',
      ], 400);
    }

    $query = $group->items()->where('status', true);

    if (isset($search)) {
      if (is_numeric($search)) {
        $query = $query->where('code', $search);
      } else {
        $query = $query->where(function ($q) use ($search) {
          $q->where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%');
        });
      }


    }

    if (isset($payload['sort_by'])) {
      $query = $query->orderBy($sort_by, $sort_type);
    }

    if (isset($payload['price'])) {
      $price = explode('-', $payload['price']);
      if (count($price) === 2) {
        if (is_numeric($price[0]) && is_numeric($price[1])) {
          if ($price[1] <= 0) {
            $query = $query->where('price', '>=', $price[0]);
          } else {
            $query = $query->whereBetween('price', [$price[0], $price[1]]);
          }
        }
      }
    }

    $meta = [
      'page'       => (int) $page,
      'limit'      => (int) $limit,
      'total_rows' => $query->count(),
      'total_page' => ceil($query->count() / $limit),
    ];

    if ($display_by !== null) {
      if ($display_by === 'created_at_asc') {
        $query = $query->orderBy('created_at', 'asc');
      } else if ($display_by === 'created_at_desc') {
        $query = $query->orderBy('created_at', 'desc');
      } else if ($display_by === 'price_asc') {
        $query = $query->orderBy('price', 'asc');
      } else if ($display_by === 'price_desc') {
        $query = $query->orderBy('price', 'desc');
      } else if ($display_by === 'priority') {
        $query = $query->orderBy('priority', 'asc');
      } else if ($display_by === 'priority_desc') {
        $query = $query->orderBy('priority', 'desc');
      }
    } else {
      $query = $query->orderBy('priority', 'desc')->orderBy($sort_by, $sort_type);
    }

    $data = $query->skip($offset)
      ->take($limit)
      ->get();

    $data = $data->map(function ($item) {
      $item->makeHidden(['list_image', 'description']);
      return $item;
    });

    return response()->json([
      'data'    => [
        'meta' => $meta,
        'data' => $data,
      ],
      'status'  => 200,
      'message' => 'Lấy danh sách tài khoản thành công',
    ], 200);
  }

  public function show($code)
  {
    $item = ListItemV2::where('code', $code)->first();

    if ($item === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy sản phẩm này',
      ], 400);
    }

    if ($item->is_sold === true) {
      return response()->json([
        'status'  => 400,
        'message' => 'Sản phẩm này đã được bán',
      ], 400);
    }

    return response()->json([
      'data'    => $item,
      'status'  => 200,
      'message' => 'Lấy thông tin tài khoản thành công',
    ], 200);
  }

  public function buy(Request $request, $code)
  {
    $payload  = $request->validate([
      'quantity' => 'nullable|integer',
    ]);
    $quantity = $payload['quantity'] ?? 1;

    $item = ListItemV2::where('code', $code)->first();

    if ($item === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy thông tin sản phẩm này',
      ], 400);
    }

    if ($item->status !== true) {
      return response()->json([
        'status'  => 400,
        'message' => 'Sản phẩm này hiện đã bị vô hiệu hoá',
      ], 400);
    }

    $group = $item->group;

    if ($group === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy thông tin nhóm dịch vụ',
      ], 400);
    }

    if (!$group->status) {
      return response()->json([
        'status'  => 400,
        'message' => 'Nhóm dịch vụ này đã bị vô hiệu hoá',
      ], 400);
    }

    if (!feature_enabled('bulk-orders')) {
      $quantity = 1;
    }

    if ($quantity > 1 && $item->is_bulk === 1) {
      return response()->json([
        'status'  => 400,
        'message' => 'Sản phẩm này không hỗ trợ mua số lượng lớn',
      ], 400);
    }

    if ($item->is_bulk > 1 && $quantity > $item->is_bulk) {
      return response()->json([
        'status'  => 400,
        'message' => 'Sản phẩm này chỉ hỗ trợ mua tối đa ' . $item->is_bulk . ' tài khoản',
      ], 400);
    }

    // check resources available
    $resources = $item->resources()->where('buyer_name', null)
      ->where('buyer_code', null)
      ->limit($quantity)->get();

    if ($resources->count() < $quantity) {
      return response()->json([
        'status'  => 400,
        'message' => 'Sản phầm này tạm hết hoặc không đủ số lượng cung cấp',
      ], 400);
    }

    // $resource = $item->resources()->where('buyer_name', null)
    //   ->where('buyer_code', null)
    //   ->limit($quantity)->get();

    // if ($resource === null) {
    //   return response()->json([
    //     'status'  => 400,
    //     'message' => 'Sản phẩm này tạm hết tài khoản, vui lòng quay lại sau',
    //   ], 400);
    // }

    $user = User::find($request->user()?->id);

    if ($user === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không xác thực được thông tin người dùng',
      ], 400);
    }

    if ($user->status !== 'active') {
      return response()->json([
        'status'  => 400,
        'message' => 'Tài khoản của bạn đã bị vô hiệu hoá',
      ], 400);
    }

    $totalPayment = $item->payment * $quantity;

    if (!is_numeric($totalPayment) || $totalPayment < 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không thể tính tiền, vui lòng thử lại',
      ], 400);
    }

    if (!$totalPayment) {
      $timeWait   = setting('time_wait_free', 10); // seconds
      $lastAction = $user->last_action; // timestamp

      if ($lastAction !== null) {
        $timeDiff = now()->diffInSeconds($lastAction);

        if ($timeDiff < $timeWait) {
          return response()->json([
            'status'  => 400,
            'message' => __t('Bạn cần chờ') . ' ' . ($timeWait - $timeDiff) . ' ' . __t('giây để mua tài khoản miễn phí'),
          ], 400);
        }
      }

      $user->update([
        'last_action' => now(),
      ]);
    }

    if ($user->balance < $totalPayment) {
      $require = $totalPayment - $user->balance;

      return response()->json([
        'status'  => 400,
        'message' => __t('Bạn còn thiếu') . ' ' . Helper::formatCurrency($require) . ' ' . __t('để mua!'),
      ], 400);
    }

    if (!$user->decrement('balance', $totalPayment) && $totalPayment > 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không thể trừ tiền, vui lòng thử lại'),
      ], 400);
    }

    if ($quantity === 1) {
      $resource = $resources->first();

      if ($resource === null) {
        $user->increment('balance', $totalPayment);

        return response()->json([
          'status'  => 400,
          'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
        ], 400);
      }

      $code = 'Y2-' . Helper::randomString(8, true);

      $resource->update([
        'domain'     => Helper::getDomain(),
        'buyer_code' => $code,
        'buyer_name' => $user->username,
        'buyer_paym' => $totalPayment,
        'buyer_date' => now(),
      ]);

      $group = isset($item->group) ? $item->group->name : '-';

      $user->transactions()->create([
        'code'           => $code,
        'amount'         => $totalPayment,
        'cost_amount'    => $item->cost,
        'balance_after'  => $user->balance,
        'balance_before' => $user->balance + $totalPayment,
        'type'           => 'account-v2-buy',
        'extras'         => [
          'code'       => $item->code,
          'group_id'   => $item->group_id,
          'account_id' => $item->id,
        ],
        'status'         => 'paid',
        'content'        => '[V2] Mua tài khoản #' . $item->code . '; Nhóm ' . $group,
        'user_id'        => $user->id,
        'username'       => $user->username,
      ]);

      $ref = $user->referrer;
      if ($ref !== null) {
        $affiliate = $ref->affiliate;
        if ($affiliate !== null) {
          $affiliate->increment('total_account_buy');
        }
      }

      return response()->json([
        'data'    => [
          'is_bulk'        => false,
          'code'           => $code,
          'username'       => $resource->username,
          'password'       => $resource->password,
          'extra_data'     => $resource->extra_data,
          'discount'       => $item->discount,
          'original_price' => $item->price,
        ],
        'status'  => 200,
        'message' => 'Mua tài khoản mã số ' . $item->code . ' thành công',
      ], 200);
    } else {
      //
      $code   = 'G2-' . Helper::randomString(8, true);
      $group  = isset($item->group) ? $item->group->name : '-';
      $ref_id = 'MT_' . mt_rand(00000, 19999) . '_' . time();
      foreach ($resources as $resource) {
        $resource->update([
          'domain'     => Helper::getDomain(),
          'is_bulk'    => true,
          'group_id'   => $ref_id,
          'buyer_code' => $code,
          'buyer_name' => $user->username,
          'buyer_paym' => $item->payment,
          'buyer_date' => now(),
        ]);
      }
      //
      $order = BulkOrder::create([
        'name'     => $group,
        'code'     => $code,
        'group'    => $ref_id,
        'image'    => $item->group->image ?? null,
        'domain'   => Helper::getDomain(),
        'payment'  => $totalPayment,
        'user_id'  => $user->id,
        'username' => $user->username,
      ]);

      $user->transactions()->create([
        'code'           => $code,
        'amount'         => $totalPayment,
        'cost_amount'    => $item->cost,
        'balance_after'  => $user->balance,
        'balance_before' => $user->balance + $totalPayment,
        'type'           => 'account-v2-buy',
        'extras'         => [
          'code'       => $item->code,
          'group_id'   => $item->group_id,
          'account_id' => $item->id,
        ],
        'status'         => 'paid',
        'content'        => 'Mua ' . $quantity . ' tài khoản trong nhóm ' . $group,
        'user_id'        => $user->id,
        'username'       => $user->username,
      ]);

      $ref = $user->referrer;
      if ($ref !== null) {
        $affiliate = $ref->affiliate;
        if ($affiliate !== null) {
          $affiliate->increment('total_account_buy', $quantity);
        }
      }

      return response()->json([
        'data'    => [
          'code'           => $code,
          'group'          => $group,
          'is_bulk'        => true,
          'quantity'       => $quantity,
          'original_price' => $item->price,
        ],
        'status'  => 200,
        'message' => 'Chúc mừng bạn đã mua thành công ' . $resources->count() . ' tài khoản',
      ], 200);
    }
  }
}
