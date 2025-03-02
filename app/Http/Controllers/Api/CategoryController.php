<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryV2;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $categories = [];

    foreach (Category::where('status', true)->get() as $val1) {
      $categories[] = [
        'id'   => $val1->id,
        'name' => $val1->name,
        'data' => $val1->groups()->get(['id', 'name', 'image', 'slug', 'category_id', 'category_name', 'created_at']),
        'type' => 'v1',
      ];
    }

    foreach (CategoryV2::where('status', true)->get() as $val2) {
      $categories[] = [
        'id'   => $val2->id,
        'name' => $val2->name,
        'data' => $val2->groups()->get(['id', 'name', 'image', 'slug', 'category_id', 'category_name', 'created_at'])?->makeHidden('items'),
        'type' => 'v2',
      ];
    }

    return response()->json([
      'data'    => $categories,
      'meta'    => [
        'total' => count($categories),
      ],
      'status'  => 200,
      'message' => 'Lấy danh sách danh mục thành công',
    ]);
  }
}
