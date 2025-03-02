<?php

namespace App\Http\Controllers\Admin\Boosting;

use App\Http\Controllers\Controller;
use App\Models\GBGroup;
use App\Models\GBPackage;
use Helper;
use Illuminate\Http\Request;

class PackageController extends Controller
{
  public function index(Request $request, $id = null)
  {
    $group = GBGroup::findOrFail($id);

    return view('admin.boosting.packages.index', compact('group'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'id'     => 'required|integer|exists:g_b_groups,id',
      'name'   => 'required|string',
      'price'  => 'required|numeric',
      'descr'  => 'nullable|string',
      'status' => 'required|boolean',
    ]);

    $group = GBGroup::findOrFail($payload['id']);

    $group->packages()->create(array_merge($payload, [
      'code' => GBPackage::generateCode()
    ]));

    Helper::addHistory('Thêm gói dịch vụ cày thuê ' . $payload['name']);

    return redirect()->back()->with('success', 'Thêm gói dịch vụ cày thuê thành công');
  }

  public function show(Request $request, $id)
  {
    $package = GBPackage::findOrFail($id);

    return view('admin.boosting.packages.show', compact('package'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'     => 'required|integer|exists:g_b_packages,id',
      'name'   => 'required|string',
      'price'  => 'required|numeric',
      'descr'  => 'nullable|string',
      'status' => 'required|boolean',
    ]);

    $package = GBPackage::findOrFail($payload['id']);

    $package->update($payload);

    Helper::addHistory('Cập nhật gói dịch vụ cày thuê ' . $payload['name']);

    return redirect()->back()->with('success', 'Cập nhật gói dịch vụ cày thuê thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer|exists:g_b_packages,id'
    ]);

    $package = GBPackage::findOrFail($payload['id']);

    $package->delete();

    Helper::addHistory('Xóa gói dịch vụ cày thuê ' . $package->name);

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa gói dịch vụ cày thuê thành công',
    ], 200);
  }
}
