<?php

namespace App\Http\Controllers\Admin\AccountV2;

use App\Http\Controllers\Controller;
use App\Models\GroupV2;
use App\Models\ListItemV2;
use App\Models\ResourceV2;
use Helper;
use Illuminate\Http\Request;

class ItemController extends Controller
{
  public function index(Request $request, $id = null)
  {
    if ($id === null) {
      $payload = $request->validate([
        'sold'       => 'nullable|in:0,1',
        'group'      => 'nullable|integer',
        'username'   => 'nullable|string',
        'buyer_name' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date',
        'domain'     => 'nullable|string',
      ]);

      $items = ResourceV2::orderBy('id', 'desc');

      if (isset($payload['sold']) && $payload['sold'] === '1') {
        $items = $items->where('buyer_name', '!=', null);
      } elseif (isset($payload['sold']) && $payload['sold'] === '0') {
        $items = $items->where('buyer_name', null);
      }

      if (isset($payload['username']) && $payload['username'] !== null) {
        $items = $items->where('username', 'like', '%' . $payload['username'] . '%');
      }

      if (isset($payload['buyer_name']) && $payload['buyer_name'] !== null) {
        $items = $items->where('buyer_name', 'like', '%' . $payload['buyer_name'] . '%');
      }

      if (isset($payload['start_date']) && $payload['start_date'] !== null) {
        $items = $items->whereDate('buyer_date', '>=', $payload['start_date']);
      }

      if (isset($payload['end_date']) && $payload['end_date'] !== null) {
        $items = $items->whereDate('buyer_date', '<=', $payload['end_date']);
      }

      if (isset($payload['domain']) && $payload['domain'] !== null) {
        $items = $items->where('domain', 'like', '%' . $payload['domain'] . '%');
      }

      $items  = $items->get();
      $groups = ListItemV2::orderBy('priority', 'desc')->get();

      return view('admin.accountsv2.items.stock', compact('items', 'groups'));
    } else {
      $group  = GroupV2::findOrFail($id);
      $groups = ListItemV2::orderBy('priority', 'desc')->get();

      return view('admin.accountsv2.items.index', compact('group', 'groups'));
    }
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'          => 'required|exists:group_v2_s,id',
      'type'        => 'nullable|string|in:account',
      'name'        => 'nullable|string|max:255',
      'code'        => 'required|integer|unique:list_item_v2_s,code',
      'cost'        => 'required|numeric|min:0',
      'price'       => 'required|numeric|min:0',
      'status'      => 'required|boolean',
      'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'is_bulk'     => 'nullable|integer',
      'discount'    => 'required|integer|min:0|max:100',
      'priority'    => 'nullable|integer',
      'list_item'   => 'nullable|string',
      'list_image'  => 'nullable|array',

      'highlights'  => 'nullable|string',
      'description' => 'nullable|string',
    ]);

    $group = GroupV2::findOrFail($payload['id']);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'items/' . $group->id);
    }

    $listItem = explode(PHP_EOL, $payload['list_item']);
    $listItem = array_map(function ($item) {
      return str_replace("\r", '', $item);
    }, $listItem);
    $listItem = array_filter($listItem, function ($item) {
      return !empty(trim($item));
    });

    if (count($listItem) === 0) {
      return redirect()->back()->with('error', 'Vui lòng nhập danh sách tài khoản');
    }

    $highlights = explode(PHP_EOL, $payload['highlights']);
    $highlights = array_map(function ($item) {
      return str_replace("\r", '', $item);
    }, $highlights);
    $highlights = array_filter($highlights, function ($item) {
      return !empty(trim($item));
    });
    $highlights = array_map(function ($item) {
      $item = explode(':', $item);
      if (count($item) === 2) {
        return [
          'name'  => trim($item[0]),
          'value' => trim($item[1]),
        ];
      }

      return trim($item[0]);
    }, $highlights);


    $autoCode = true;
    if (count($listItem) === 1 && !empty($payload['code'])) {
      $autoCode = false;
    }

    $code = $autoCode ? ListItemV2::generateCode() : $payload['code'];
    $item = ListItemV2::create([
      'name'        => $payload['name'] ?? $code,
      'code'        => $code,
      'type'        => 'account_group',
      'cost'        => $payload['cost'],
      'price'       => $payload['price'],
      'discount'    => $payload['discount'],
      'status'      => $payload['status'],
      'image'       => $payload['image'] ?? null,
      'is_bulk'     => $payload['is_bulk'] ?? 1,
      'highlights'  => $highlights,
      'description' => Helper::htmlPurifier($payload['description'] ?? ''),
      'list_image'  => $payload['list_image'] ?? [],
      'priority'    => 0,
      'group_id'    => $group->id,
    ]);

    $created = [];

    if ($item) {
      foreach ($listItem as $account) {
        $data = parseItem($account);

        $created[] = ResourceV2::create([
          'code'       => $item->code,
          'username'   => $data['username'],
          'password'   => $data['password'],
          'extra_data' => $data['extra_data'],
        ]);

      }
    }

    Helper::addHistory('[V2] Thêm ' . count($created) . ' sản phẩm cho nhóm ' . $group->name);

    return redirect()->back()->with('success', 'Thêm ' . count($created) . ' tài khoản vào nhóm ' . $group->name . ' thành công');
  }

  public function show($id)
  {
    $item = ListItemV2::findOrFail($id);

    return view('admin.accountsv2.items.show', compact('item'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'          => 'required|exists:list_item_v2_s,id',
      'name'        => 'nullable|string|max:255',
      'code'        => 'required|integer|unique:list_item_v2_s,code,' . $request->id . ',id',
      'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'cost'        => 'required|numeric|min:0',
      'price'       => 'required|numeric|min:0',
      'status'      => 'required|boolean',
      'is_bulk'     => 'nullable|integer',
      'priority'    => 'nullable|integer',
      'discount'    => 'required|integer|min:0|max:100',
      'extra_data'  => 'nullable|string',
      'list_image'  => 'nullable|array',
      'highlights'  => 'nullable|string',
      'description' => 'nullable|string',
    ]);

    $item = ListItemV2::findOrFail($payload['id']);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'items/' . $item->group_id);
    }

    $highlights = explode(PHP_EOL, $payload['highlights']);
    $highlights = array_map(function ($item) {
      return str_replace("\r", '', $item);
    }, $highlights);
    $highlights = array_filter($highlights, function ($item) {
      return !empty(trim($item));
    });
    $highlights = array_map(function ($item) {
      $item = explode(':', $item);
      if (count($item) === 2) {
        return [
          'name'  => trim($item[0]),
          'value' => trim($item[1]),
        ];
      }

      return trim($item[0]);
    }, $highlights);

    $payload['highlights']  = $highlights;
    $payload['description'] = Helper::htmlPurifier($payload['description'] ?? '');

    $item->update($payload);

    Helper::addHistory('[V2] Cập nhật sản phẩm #' . $item->code . ' -> ' . $payload['code']);

    return redirect()->back()->with('success', 'Cập nhật sản phẩm #' . $item->code . ' thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:list_item_v2_s,id',
    ]);

    $item = ListItemV2::findOrFail($payload['id']);

    if ($item->resources()->count() > 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Sản phẩm này đang có tài khoản, không thể xóa',
      ], 400);
    }

    $item->delete();

    Helper::deleteFile($item->image);
    foreach ($item->list_image as $image) {
      Helper::deleteFile($image);
    }

    Helper::addHistory('[V2] Xóa sản phẩm #' . $item->code);

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa sản phẩm #' . $item->code . ' thành công',
    ]);
  }
}
