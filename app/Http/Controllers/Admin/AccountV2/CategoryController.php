<?php

namespace App\Http\Controllers\Admin\AccountV2;

use App\Http\Controllers\Controller;
use App\Models\CategoryV2;
use Helper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = CategoryV2::all();

    return view('admin.accountsv2.categories.index', compact('categories'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug']     = CategoryV2::generateSlug($payload['name']);
    $payload['username'] = auth()->user()->username;

    CategoryV2::create($payload);

    Helper::addHistory('[V2] Thêm danh mục ' . $payload['name']);

    return redirect()->back()->with('success', 'Thêm danh mục thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|exists:category_v2_s,id',
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug'] = CategoryV2::generateSlug($payload['name']);

    CategoryV2::find($payload['id'])->update($payload);

    Helper::addHistory('[V2] Cập nhật danh mục ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật danh mục thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:category_v2_s,id',
    ]);

    $category = CategoryV2::findOrFail($payload['id']);

    if ($category->groups()->count() > 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Danh mục này đang có nhóm con, không thể xóa',
      ], 400);
    }

    Helper::addHistory('[V2] Xóa danh mục ' . $category->name);

    $category->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa danh mục thành công',
    ]);
  }
}
