<?php

namespace App\Http\Controllers\Admin\Boosting;

use App\Http\Controllers\Controller;
use App\Models\GBCategory;
use Helper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = GBCategory::get();

    return view('admin.boosting.categories.index', compact('categories'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug']     = GBCategory::generateSlug($payload['name']);
    $payload['username'] = $request->user()->username;

    GBCategory::create($payload);

    Helper::addHistory('Thêm danh mục cày thuê ' . $payload['name']);

    return redirect()->back()->with('success', 'Thêm danh mục thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|exists:g_b_categories,id',
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug'] = GBCategory::generateSlug($payload['name']);

    GBCategory::find($payload['id'])->update($payload);

    Helper::addHistory('Cập nhật danh mục cày thuê ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật danh mục thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:g_b_categories,id',
    ]);

    $category = GBCategory::findOrFail($payload['id']);

    if ($category->groups()->count() > 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Danh mục này đang có nhóm con, không thể xóa',
      ], 400);
    }

    Helper::addHistory('Xóa danh mục cày thuê ' . $category->name);

    $category->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa danh mục thành công',
    ]);
  }
}