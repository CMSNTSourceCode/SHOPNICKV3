<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawData;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{

  public function index(Request $request)
  {
    $validate = Validator::make($request->all(), [
      'page'      => 'nullable|integer|min:1',
      'type'      => 'nullable|string',
      'limit'     => 'nullable|integer|min:1',
      'search'    => 'nullable|string|max:255',
      'sort_by'   => 'nullable|string|max:255',
      'sort_type' => 'nullable|string|in:asc,desc',
    ]);

    if ($validate->fails()) {
      return response()->json([
        'data'    => $validate->errors(),
        'status'  => 422,
        'message' => $validate->errors()->first(),
      ], 422);
    }
    $payload = $validate->validated();
    // init default value
    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'desc';


    $query = WithdrawData::where('user_id', $request->user()->id)->with('inventoryVar');

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('code', 'like', '%' . $search . '%');
      });
    }

    $total = $query->count();

    $data = $query->skip($offset)
      ->take($limit)
      ->orderBy($sort_by, $sort_type)
      ->get();

    foreach ($data as $value) {
      if ($value->inventoryVar) {
        $value->inventoryVar->makeHidden(['form_inputs', 'form_packages']);
      }
    }

    return response()->json([
      'data'    => [
        'meta' => [
          'page'  => (int) $page,
          'limit' => (int) $limit,
          'total' => (int) $total,
          'pages' => ceil($total / $limit),
        ],
        'data' => $data,
      ],
      'status'  => 200,
      'message' => 'Get orders successfully',
    ], 200);
  }

  public function info(Request $request)
  {
    $user = User::where('id', auth()->id())->firstOrFail();

    $results = [];

    foreach ($user->inventories()->where('is_active', true)->with('inventory_var')->get() as $value) {
      $results[] = [
        'id'              => $value->id,
        'sign'            => str()->uuid(),
        'unit'            => $value->inventory_var->unit,
        'name'            => $value->inventory_var->name,
        'value'           => $value->value,
        'image'           => $value->inventory_var->image,
        'created_at'      => $value->created_at,
        'form_inputs'     => $value->inventory_var->form_inputs,
        'value_formatted' => number_format($value->value, 0, ',', '.') . ' ' . $value->inventory_var->unit,
      ];
    }

    return response()->json([
      'data'    => $results,
      'status'  => 200,
      'message' => 'Get data success',
    ]);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'         => 'required|integer|exists:inventories,id',
      'amount'     => 'required|integer',
      'arr_inputs' => 'required|array',
    ]);

    $user      = User::where('id', auth()->id())->firstOrFail();
    $amount    = (int) $payload['amount'];
    $inventory = $user->inventories()->where('id', $payload['id'])->where('is_active', true)->firstOrFail();


    if ($inventory->inventory_var === null) {
      return response()->json([
        'status'  => 400,
        'message' => 'Vật phẩm này không tồn tại trên hệ thống',
      ], 400);
    }

    if ($inventory->inventory_var->is_active === false) {
      return response()->json([
        'status'  => 400,
        'message' => 'Vật phẩm này đã bị vô hiệu hóa, vui lòng thử lại sau',
      ], 400);
    }

    // if ($inventory->inventory_var->min_withdraw > $amount) {
    //   return response()->json([
    //     'status'  => 400,
    //     'message' => 'Số lượng ' . $inventory->inventory_var->name . ' phải lớn hơn ' . $inventory->inventory_var->min_withdraw,
    //   ], 400);
    // }

    // if ($inventory->inventory_var->max_withdraw < $amount) {
    //   return response()->json([
    //     'status'  => 400,
    //     'message' => 'Số lượng ' . $inventory->inventory_var->name . ' phải nhỏ hơn ' . $inventory->inventory_var->max_withdraw,
    //   ], 400);
    // }

    $isValid = false;
    foreach ($inventory->inventory_var->form_packages as $value => $name) {
      if ($amount == $value) {
        $isValid = true;
        break;
      }
    }
    if (!$isValid) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số lượng ' . $inventory->inventory_var->unit . ' rút không hợp lệ, vui lòng kiểm tra lại',
      ], 400);
    }

    if ($inventory->value < $amount) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số lượng ' . $inventory->inventory_var->name . ' của bạn không đủ',
      ], 400);
    }

    // valid packages
    $productPackages = $inventory->inventory_var->form_packages;

    if (count($productPackages) === 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Không có gói sản phẩm nào được cấu hình, vui lòng liên hệ quản trị viên',
      ], 400);
    }

    $validPackage = false;
    foreach ($productPackages as $productPackage => $productPackgeName) {
      if ($productPackage == $amount) {
        $validPackage = true;
        break;
      }
    }

    if (!$validPackage) {
      return response()->json([
        'status'  => 400,
        'message' => 'Số lượng ' . $inventory->inventory_var->unit . ' rút không hợp lệ, vui lòng kiểm tra lại',
      ], 400);
    }

    // arr inputs
    $orderInput    = [];
    $productInputs = $inventory->inventory_var->form_inputs ?? [];
    $payloadInputs = $payload['arr_inputs'] ?? [];

    if (count($productInputs) !== count($payloadInputs)) {
      return response()->json([
        'data'    => null,
        'status'  => 422,
        'message' => 'Thiếu dữ liệu đầu vào, vui lòng kiểm tra lại',
      ], 422);
    }

    // validate inputs
    foreach ($productInputs as $key => $input) {
      $payloadInput = $payloadInputs[$key] ?? null;

      if ($input['type'] === 'select') {
        if (!in_array($payloadInput, $input['options'])) {
          return response()->json([
            'status'  => 422,
            'message' => 'Tuỳ chọn không hợp lệ, chỉ chấp nhận: ' . implode(', ', $input['options']),
          ], 422);
        }
      } else {
        if (!is_string($payloadInput)) {
          return response()->json([
            'status'  => 422,
            'message' => 'Dữ liệu đầu vào không hợp lệ, dữ liệu: ' . $payloadInput,
          ], 422);
        }
      }

      $orderInput[] = [
        'label' => $input['label'],
        'type'  => $input['type'],
        'value' => Helper::htmlPurifier($payloadInput),
      ];
    }

    if (!$inventory->decrement('value', $amount)) {
      return response()->json([
        'status'  => 500,
        'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
      ], 500);
    }

    $withdrawInfo = WithdrawData::create([
      'code'         => 'UT-' . Helper::randomString(7, true),
      'type'         => 'withdraw',

      'name'         => $inventory->inventory_var->name,
      'unit'         => $inventory->inventory_var->unit,
      'var_id'       => $inventory->var_id,
      'inv_id'       => $inventory->id,

      'amount'       => $amount,

      'status'       => 'Pending',
      'user_inputs'  => $orderInput,

      'user_id'      => $user->id,
      'username'     => $user->username,

      'after_value'  => $inventory->value,
      'before_value' => $inventory->value + $amount,
    ]);

    return response()->json([
      'data'    => [
        'code' => $withdrawInfo->code,
      ],
      'status'  => 200,
      'message' => 'Yêu cầu rút ' . $amount . ' ' . $inventory->inventory_var->name . ' đã được gửi',
    ]);
  }
}
