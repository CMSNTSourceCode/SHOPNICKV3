<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Helper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = ItemCategory::all();

    return view('admin.items.categories.index', compact('categories'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug']     = ItemCategory::generateSlug($payload['name']);
    $payload['username'] = auth()->user()->username;

    ItemCategory::create($payload);

    Helper::addHistory('Thêm danh mục vật phẩm ' . $payload['name']);

    return redirect()->back()->with('success', 'Thêm danh mục thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|exists:item_categories,id',
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug'] = ItemCategory::generateSlug($payload['name']);

    ItemCategory::find($payload['id'])->update($payload);

    Helper::addHistory('Cập nhật danh mục vật phẩm ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật danh mục thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:item_categories,id',
    ]);

    $category = ItemCategory::findOrFail($payload['id']);

    if ($category->groups()->count() > 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Danh mục này đang có nhóm con, không thể xóa',
      ], 400);
    }

    Helper::addHistory('Xóa danh mục vật phẩm ' . $category->name);

    $category->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa danh mục thành công',
    ]);
  }
}