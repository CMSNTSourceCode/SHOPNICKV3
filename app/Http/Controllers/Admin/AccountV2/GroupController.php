<?php

namespace App\Http\Controllers\Admin\AccountV2;

use App\Http\Controllers\Controller;
use App\Models\CategoryV2;
use App\Models\GroupV2;
use Helper;
use Illuminate\Http\Request;

class GroupController extends Controller
{
  public function index($id)
  {
    $category = CategoryV2::findOrFail($id);

    return view('admin.accountsv2.groups.index', compact('category'));
  }

  public function create($id)
  {
    $category = CategoryV2::findOrFail($id);

    return view('admin.accountsv2.groups.create', compact('category'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'        => 'required|exists:category_v2_s,id',
      'name'      => 'required|string|max:255',
      'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'descr'     => 'nullable|string',
      'meta_seo'  => 'nullable|array',
      'descr_seo' => 'nullable|string',
      'status'    => 'required|boolean',
      'priority'  => 'required|integer',
      'game_type' => 'nullable|string|in:game-khac',
    ]);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public');
    }

    $category = CategoryV2::findOrFail($payload['id']);

    $payload['slug']      = GroupV2::generateSlug($payload['name']);
    $payload['descr']     = Helper::htmlPurifier($payload['descr']);
    $payload['descr_seo'] = Helper::htmlPurifier($payload['descr_seo']);

    $category->groups()->create(array_merge($payload, [
      'username'      => auth()->user()->username,
      'category_name' => $category->name,
    ]));

    Helper::addHistory('[V2] Thêm nhóm ' . $payload['name'] . ' cho danh mục ' . $category->name);

    return redirect()->route('admin.accountsv2.groups', ['id' => $payload['id']])->with('success', 'Đã thêm nhóm ' . $payload['name'] . ' thành công');
  }

  public function edit($id, $gid)
  {
    $group    = GroupV2::findOrFail($gid);
    $category = CategoryV2::findOrFail($id);

    return view('admin.accountsv2.groups.edit', compact('category', 'group'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'        => 'required|exists:group_v2_s,id',
      'name'      => 'required|string|max:255',
      'descr'     => 'nullable|string',
      'meta_seo'  => 'nullable|array',
      'descr_seo' => 'nullable|string',
      'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1004800',
      'status'    => 'required|boolean',
      'priority'  => 'required|integer',
      'game_type' => 'nullable|string|in:game-khac',
    ]);

    $group = GroupV2::findOrFail($payload['id']);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'public');
      if ($payload['image']) {
        Helper::deleteFile($group->image);
      }
    }


    // $payload['slug']      = GroupV2::generateSlug($payload['name']);
    $payload['descr']     = Helper::htmlPurifier($payload['descr']);
    $payload['descr_seo'] = Helper::htmlPurifier($payload['descr_seo']);

    $group->update($payload);

    Helper::addHistory('[V2] Cập nhật nhóm ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật nhóm #' . $payload['id'] . ' thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:group_v2_s,id',
    ]);

    $group = GroupV2::findOrFail($payload['id']);

    if ($group->items()->count() > 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Nhóm này đang có tài khoản, không thể xóa',
      ], 400);
    }

    Helper::deleteFile($group->image);
    Helper::addHistory('[V2] Xóa nhóm ' . $group->name);

    $group->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa nhóm thành công',
    ]);
  }
}
