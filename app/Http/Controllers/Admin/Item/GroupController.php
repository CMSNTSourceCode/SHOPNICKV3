<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\ItemGroup;
use Helper;
use Illuminate\Http\Request;

class GroupController extends Controller
{
  public function index($id)
  {
    $category = ItemCategory::findOrFail($id);

    return view('admin.items.groups.index', compact('category'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'         => 'required|exists:item_categories,id',
      'name'       => 'required|string|max:255',
      'descr'      => 'nullable|string|max:1024',
      'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'status'     => 'required|boolean',
      'priority'   => 'required|integer',
      'login_with' => 'nullable|string',
    ]);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public');
    }

    $category = ItemCategory::findOrFail($payload['id']);

    $payload['slug'] = ItemGroup::generateSlug($payload['name']);

    if (isset($payload['login_with'])) {
      $payload['login_with'] = Helper::text2array($payload['login_with']);
    }

    $category->groups()->create(array_merge($payload, [
      'username'      => auth()->user()->username,
      'category_name' => $category->name,
    ]));

    Helper::addHistory('Thêm nhóm vật phẩm ' . $payload['name'] . ' cho danh mục ' . $category->name);

    return redirect()->back()->with('success', 'Thêm nhóm thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'         => 'required|exists:item_groups,id',
      'descr'      => 'nullable|string|max:1024',
      'name'       => 'required|string|max:255',
      'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'status'     => 'required|boolean',
      'priority'   => 'required|integer',
      'login_with' => 'nullable|string',
    ]);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public');
    }

    $group = ItemGroup::findOrFail($payload['id']);

    $payload['slug'] = ItemGroup::generateSlug($payload['name']);

    if (isset($payload['login_with'])) {
      $payload['login_with'] = Helper::text2array($payload['login_with']);
    } else {
      $payload['login_with'] = $group->login_with ?? [];
    }

    $group->update($payload);

    Helper::addHistory('Cập nhật nhóm vật phẩm ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật nhóm #' . $payload['id'] . ' thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:item_groups,id',
    ]);

    $group = ItemGroup::findOrFail($payload['id']);

    if ($group->data()->count() > 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Nhóm này đang có tài khoản, không thể xóa',
      ], 400);
    }

    Helper::addHistory('Xóa nhóm vật phẩm ' . $group->name);

    $group->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa nhóm thành công',
    ]);
  }
}
