<?php

namespace App\Http\Controllers\Admin\AccountV2;

use App\Http\Controllers\Controller;
use App\Models\ListItemV2;
use App\Models\ResourceV2;
use Helper;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
  public function index($id)
  {
    $item = ListItemV2::findOrFail($id);


    return view('admin.accountsv2.resources.index', compact('item'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|exists:list_item_v2_s,id',
      'accounts' => 'required|string',
    ]);

    $item = ListItemV2::findOrFail($payload['id']);


    $listAccount = explode(PHP_EOL, $payload['accounts']);
    $listAccount = array_map(function ($item) {
      return str_replace("\r", '', $item);
    }, $listAccount);
    $listAccount = array_filter($listAccount, function ($item) {
      return !empty(trim($item));
    });

    if (count($listAccount) === 0) {
      return redirect()->back()->with('error', 'Vui lòng nhập danh sách tài khoản');
    }

    $created = [];

    foreach ($listAccount as $account) {
      $data = parseItem($account);

      $created[] = ResourceV2::create([
        'code'       => $item->code,
        'username'   => $data['username'],
        'password'   => $data['password'],
        'extra_data' => $data['extra_data'],
      ]);
    }

    Helper::addHistory('[V2] Thêm ' . count($created) . ' tài khoản vào ' . $item->name);

    return redirect()->route('admin.accountsv2.items')->with('success', 'Thêm ' . count($created) . ' tài khoản vào ' . $item->name . ' thành công');
  }

  public function show(Request $request, $id)
  {
    $resource = ResourceV2::findOrFail($id);

    if (!$resource) {
      return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
    }

    return view('admin.accountsv2.resources.show', compact('resource'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'         => 'required|exists:resource_v2_s,id',
      'username'   => 'nullable|string',
      'password'   => 'nullable|string',
      'extra_data' => 'nullable|string',
    ]);

    $resource = ResourceV2::findOrFail($payload['id']);

    $resource->update($payload);

    Helper::addHistory('[V2] Cập nhật tài khoản ' . $resource->username);

    return redirect()->back()->with('success', 'Cập nhật tài khoản ' . $resource->username . ' thành công');
  }


  public function export(Request $request)
  {
    $payload = $request->validate([
      'ids' => 'required|array',
    ]);

    $resources = ResourceV2::whereIn('id', $payload['ids'])->get();

    $output = "XUẤT NGÀY " . now() . " \n";

    // foreach ($resources as $resource) {
    //   $output .= "Tài khoản: " . $resource->username . "\n";
    //   $output .= "Mật khẩu: " . $resource->password . "\n";
    //   $output .= "Additional: " . $resource->extra_data . "\n";
    //   $output .= "------------------------\n";
    // }

    foreach ($resources as $resource) {
      $output .= $resource->username . "|" . $resource->password . "|" . $resource->extra_data . "\n";
    }

    $filename = 'export-' . now()->format('d-m-Y') . '.txt';

    $output .= 'Tổng số tài khoản: ' . $resources->count();

    Helper::addHistory('[V2] Xuất ' . $resources->count() . ' tài khoản');

    return response()->json([
      'name'    => $filename,
      'data'    => $output,
      'status'  => 200,
      'message' => 'Xuất tài ' . $resources->count() . ' khoản thành công',
    ], 200);
  }


  public function delete(Request $request)
  {
    $payload = $request->validate([
      'ids' => 'required|array',
    ]);

    $resources = ResourceV2::whereIn('id', $payload['ids'])->get();

    $deleted = [];

    foreach ($resources as $resource) {
      if ($resource->delete()) {
        $deleted[] = $resource->username;
      }
    }

    Helper::addHistory('[V2] Xóa tài khoản ' . implode(', ', $deleted));

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa tài ' . $resources->count() . ' khoản thành công',
    ], 200);
  }
}
