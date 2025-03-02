<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\ListItem;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
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


    $group = Group::where('id', $payload['group_id'])->where('status', true)->first();

    if ($group === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy nhóm dịch vụ này',
      ], 400);
    }

    $query = $group->items()->where('buyer_name', null)->where('buyer_code', null)->where('status', true);

    if (isset($search)) {
      if (is_numeric($search)) {
        $query = $query->where('code', $search);
      } else {
        $query = $query->where(function ($q) use ($search) {
          $q->where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->orWhere('highlights', 'like', '%' . $search . '%')
            ->orWhere('raw_skins', 'like', '%' . $search . '%');
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
      $item->makeHidden(['list_image', 'description', 'list_skin', 'list_champ']);
      return $item;
    });

    // $data = $data->toArray();


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
    $item = ListItem::where('code', $code)->first();

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
    $item = ListItem::where('code', $code)->first();

    if ($item === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy thông tin sản phẩm này',
      ], 400);
    }

    if ($item->is_sold === true) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Sản phẩm này đã được bán cho người khác'),
      ], 400);
    }

    $group = $item->group;

    if ($group === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy thông tin nhóm dịch vụ',
      ], 400);
    }

    if ($group->status === false) {
      return response()->json([
        'status'  => 400,
        'message' => 'Nhóm dịch vụ này đã bị vô hiệu hoá',
      ], 400);
    }

    if ($group->game_type === 'thue-dot-kich') {
      return response()->json([
        'status'  => 400,
        'message' => 'Đây là tài khoản cho thuê, hãy liên hệ admin để thuê!',
      ], 400);
    }

    $user = User::find($request->user()?->id);

    if ($user === null) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không xác thực được thông tin người dùng'),
      ], 400);
    }

    if ($user->status !== 'active') {
      return response()->json([
        'status'  => 400,
        'message' => __t('Tài khoản của bạn đã bị vô hiệu hoá'),
      ], 400);
    }

    if (!is_numeric($item->payment) || $item->payment < 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không thể tính tiền, vui lòng thử lại'),
      ], 400);
    }

    if (!$item->payment) {
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

    if ($user->balance < $item->payment) {
      $require = $item->payment - $user->balance;

      return response()->json([
        'status'  => 400,
        'message' => __t('Bạn còn thiếu') . ' ' . Helper::formatCurrency($require) . ' ' . __t('để mua!'),
      ], 400);
    }

    if (!$user->decrement('balance', $item->payment) && $item->payment > 0) {
      return response()->json([
        'status'  => 400,
        'message' => __t('Không thể trừ tiền, vui lòng thử lại'),
      ], 400);
    }

    $code = 'Y1-' . Helper::randomString(8, true);

    $item->update([
      'status'     => false,
      'domain'     => Helper::getDomain(),
      'buyer_code' => $code,
      'buyer_name' => $user->username,
      'buyer_paym' => $item->payment,
      'buyer_date' => now(),
    ]);

    $group = isset($item->group) ? $item->group->name : '-';

    $user->transactions()->create([
      'code'           => $code,
      'amount'         => $item->payment,
      'cost_amount'    => $item->cost,
      'balance_after'  => $user->balance,
      'balance_before' => $user->balance + $item->payment,
      'type'           => 'account-buy',
      'extras'         => [
        'code'       => $item->code,
        'group_id'   => $item->group_id,
        'account_id' => $item->id,
      ],
      'status'         => 'paid',
      'content'        => 'Mua tài khoản #' . $item->code . '; Nhóm ' . $group,
      'user_id'        => $user->id,
      'username'       => $user->username,
    ]);

    if ($item->staff_name) {
      $client = User::where('username', $item->staff_name)->first();
      if ($client) {
        $payment = (float) (($item->payment * $client->colla_percent) / 100);

        $item->update([
          'staff_payment'      => $payment,
          'staff_status'       => 'WaitPayment',
          'staff_completed_at' => now(),
        ]);
      }
    }

    if (domain() !== 'shopgame5sao.com') {
      $ref = $user->referrer;
      if ($ref !== null) {
        $affiliate = $ref->affiliate;
        if ($affiliate !== null) {
          $affiliate->increment('total_account_buy');
        }
      }
    }

    return response()->json([
      'data'    => [
        'code'           => $code,
        'username'       => $item->username,
        'password'       => $item->password,
        'extra_data'     => $item->extra_data,
        'discount'       => $item->discount,
        'original_price' => $item->price,
      ],
      'status'  => 200,
      'message' => __t('Mua tài khoản') . ' #' . $item->code . ' ' . __t('thành công'),
    ], 200);
  }

  public function champions($id)
  {
    $path = public_path('static/champions/' . $id . '.png');
    $link = "https://img.yourol06.com/img/DataLienMinh/assets/champions/portraits/" . $id . ".png";

    if (!file_exists(public_path('static/champions'))) {
      mkdir('static/champions', 0755, true);
    }

    if (file_exists($path)) {
      return response(file_get_contents($path), 200)->header('Content-Type', 'image/png');
    }

    $response = Http::get($link);

    if ($response->failed()) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy thông tin tướng này',
      ], 400);
    }

    file_put_contents($path, $response->body());


    return response($response->body(), 200)->header('Content-Type', 'image/png');
  }

  public function skins($id)
  {
    $path = public_path('static/skins/' . $id . '.jpg');
    $link = "https://lienminhshop.vn/Data/upload/images/SkinsId/" . $id . ".jpg";

    if (!file_exists(public_path('static/skins'))) {
      mkdir('static/skins', 0755, true);
    }

    if (file_exists($path)) {
      return response(file_get_contents($path), 200)->header('Content-Type', 'image/png');
    }

    $response = Http::get($link);

    if ($response->failed()) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không tìm thấy thông tin skin này',
      ], 400);
    }

    file_put_contents($path, $response->body());


    return response($response->body(), 200)->header('Content-Type', 'image/png');
  }
}
