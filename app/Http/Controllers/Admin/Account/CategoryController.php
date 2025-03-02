<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Helper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = Category::all();

    return view('admin.accounts.categories.index', compact('categories'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug']     = Category::generateSlug($payload['name']);
    $payload['username'] = auth()->user()->username;

    Category::create($payload);

    Helper::addHistory('Thêm danh mục ' . $payload['name']);

    return redirect()->back()->with('success', 'Thêm danh mục thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'       => 'required|exists:categories,id',
      'name'     => 'required|string|max:255',
      'status'   => 'required|boolean',
      'priority' => 'required|integer',
    ]);

    $payload['slug'] = Category::generateSlug($payload['name']);

    Category::find($payload['id'])->update($payload);

    Helper::addHistory('Cập nhật danh mục ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật danh mục thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|exists:categories,id',
    ]);

    $category = Category::findOrFail($payload['id']);

    if ($category->groups()->count() > 0) {
      return response()->json([
        'status'  => 400,
        'message' => 'Danh mục này đang có nhóm con, không thể xóa',
      ], 400);
    }

    Helper::addHistory('Xóa danh mục ' . $category->name);

    $category->delete();

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa danh mục thành công',
    ]);
  }


}
