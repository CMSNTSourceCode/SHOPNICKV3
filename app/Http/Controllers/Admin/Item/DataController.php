<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\Ingames;
use App\Models\ItemGroup;
use App\Models\ItemData;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
  public function index(Request $request, $id = null)
  {
    $group   = ItemGroup::findOrFail($id);
    $ingames = Ingames::orderBy('id', 'desc')->where('status', true)->get();

    return view('admin.items.data.index', compact('group', 'ingames'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'          => 'required|exists:item_groups,id',
      'type'        => 'required|string|in:user_pass,addfriend',
      'name'        => 'required|string',
      'code'        => 'nullable|integer|unique:item_data',
      'price'       => 'required|integer',
      'robux'       => 'required|integer',
      'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1004800',
      'discount'    => 'required|integer',
      'status'      => 'required|boolean',
      'ingame_id'   => 'nullable|exists:ingames,id',
      'highlights'  => 'required|string',
      'description' => 'required|string',
    ]);

    $group = ItemGroup::findOrFail($payload['id']);

    $payload['group_id']  = $group->id;
    $payload['ingame_id'] = Ingames::where('status', true)->first()->id ?? null;

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'items/' . $group->id);
    }

    $highlights = explode(PHP_EOL, $payload['highlights']);
    $highlights = array_map(function ($item) {
      return trim($item);
    }, $highlights);

    $payload['highlights'] = ($highlights);

    $autoCode = true;
    if (!empty($payload['code'])) {
      $autoCode = false;
    }

    $payload['code'] = $autoCode ? ItemData::generateCode() : $payload['code'];

    $data = ItemData::create($payload);

    Helper::addHistory('[ITEMS] Thêm sản phẩm ' . $data->name . ' vào nhóm ' . $group->name);

    return redirect()->back()->with('success', 'Thêm sản phẩm vào nhóm thành công');
  }

  public function show($id)
  {
    $item    = ItemData::findOrFail($id);
    $ingames = Ingames::orderBy('id', 'desc')->where('status', true)->get();

    return view('admin.items.data.show', compact('item', 'ingames'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'          => 'required|exists:item_data,id',
      'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'code'        => 'nullable|integer|unique:item_data,code,' . $request->id . ',id',
      'type'        => 'required|string|in:user_pass,addfriend',
      'name'        => 'required|string',
      'price'       => 'required|integer',
      'robux'       => 'required|integer',
      'discount'    => 'required|integer',
      'status'      => 'required|boolean',
      'highlights'  => 'required|string',
      'description' => 'required|string',
    ]);

    $item = ItemData::findOrFail($payload['id']);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public', 'items/' . $item->group_id);
    }

    $highlights = explode(PHP_EOL, $payload['highlights']);
    $highlights = array_map(function ($item) {
      return trim($item);
    }, $highlights);

    $payload['ingame_id']  = Ingames::where('status', true)->first()->id ?? null;
    $payload['highlights'] = ($highlights);

    $item->update($payload);

    Helper::addHistory('[ITEMS] Cập nhật sản phẩm ' . $item->name);

    return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công');
  }

  public function updateList(Request $request)
  {
    $validated = $request->validate([
      'ids'      => 'required|array',
      'ids.*'    => 'required|exists:item_data,id',
      'rate'     => 'nullable|integer',
      'price'    => 'nullable|integer',
      'discount' => 'nullable|integer',
    ]);

    try {
      DB::beginTransaction();

      foreach ($validated['ids'] as $id) {
        $item = ItemData::findOrFail($id);

        if (isset($validated['price'])) {
          $item->price = $validated['price'];
        }

        if (isset($validated['discount'])) {
          $item->discount = $validated['discount'];
        }

        if (isset($payload['rate']) && $item->robux) {
          $item->price = $item->robux * $validated['rate'];
        }

        $item->save(); // Thêm dòng này để lưu thay đổi
      }

      Helper::addHistory('[ITEMS] Cập nhật danh sách sản phẩm: ' . implode(', ', $validated['ids']));

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Cập nhật thành công',
      ]);

    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'success' => false,
        'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
      ], 500);
    }
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:item_data,id',
    ]);

    $data = ItemData::findOrFail($payload['id']);

    Helper::addHistory('Xóa sản phẩm ' . $data->name . ' trong nhóm ' . $data->group->name);

    $data->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa sản phẩm thành công',
    ]);
  }
}
