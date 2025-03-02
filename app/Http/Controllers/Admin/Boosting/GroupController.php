<?php

namespace App\Http\Controllers\Admin\Boosting;

use App\Http\Controllers\Controller;
use App\Models\GBCategory;
use App\Models\GBGroup;
use Helper;
use Illuminate\Http\Request;

class GroupController extends Controller
{
  public function index($id)
  {
    $category = GBCategory::findOrFail($id);

    return view('admin.boosting.groups.index', compact('category'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|exists:g_b_categories,id',
      'name'     => 'required|string|max:255',
      'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'descr'    => 'nullable|string|max:1024',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public');
    }

    $category = GBCategory::findOrFail($payload['id']);

    $payload['slug'] = GBGroup::generateSlug($payload['name']);

    $category->groups()->create(array_merge($payload, [
      'username'      => $request->user()->username,
      'category_name' => $category->name,
    ]));

    Helper::addHistory('Thêm nhóm vật phẩm ' . $payload['name'] . ' cho danh mục ' . $category->name);

    return redirect()->back()->with('success', 'Thêm nhóm thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|exists:g_b_groups,id',
      'descr'    => 'nullable|string|max:1024',
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public');
    }

    $group = GBGroup::findOrFail($payload['id']);

    $payload['slug'] = GBGroup::generateSlug($payload['name']);

    $group->update($payload);

    Helper::addHistory('Cập nhật nhóm vật phẩm ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật nhóm #' . $payload['id'] . ' thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:g_b_groups,id',
    ]);

    $group = GBGroup::findOrFail($payload['id']);

    Helper::addHistory('Xóa nhóm vật phẩm ' . $group->name);

    $group->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa nhóm thành công',
    ]);
  }
}
