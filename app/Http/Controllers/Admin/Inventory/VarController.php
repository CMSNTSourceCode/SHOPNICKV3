<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryVar;
use Helper;
use Illuminate\Http\Request;

class VarController extends Controller
{
  public function index(Request $request)
  {
    $vars = InventoryVar::orderBy('id', 'desc')->get();

    return view('admin.inventory.var.index', compact('vars'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'          => 'required|string',
      'unit'          => 'required|string',
      'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
      'is_active'     => 'required|boolean',
      'form_inputs'   => 'required|string',
      'form_packages' => 'required|string',
      'min_withdraw'  => 'required|integer|min:0',
      'max_withdraw'  => 'required|integer|min:0',
    ]);

    $payload['is_active'] = $payload['is_active'] ? true : false;

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'), 'do_spaces', 'inventory_vars');
    }

    $inputs     = [];
    $frm_inputs = Helper::text2array($payload['form_inputs'] ?? '');
    foreach ($frm_inputs as $key => $input) {
      $input = explode('|', $input);

      if (count($input) < 1) {
        unset($inputs[$key]);
        continue;
      }

      $options = [];

      if (count($input) > 2) {
        $options = explode(',', $input[2]);
      }

      $inputs[$key] = [
        'label'   => ucfirst(str_replace('_', ' ', $input[0])),
        'type'    => $input[1] ?? 'text',
        'options' => $options,
      ];
    }
    $payload['form_inputs'] = ($inputs);

    $packages     = [];
    $frm_packages = Helper::text2array($payload['form_packages'] ?? '');
    foreach ($frm_packages as $value) {
      $packages[$value] = $value . ' ' . $payload['unit'];
    }
    $payload['form_packages'] = ($packages);

    $inventoryVar = InventoryVar::create($payload);

    Helper::addHistory("Tạo loại phần thưởng mới thành công: $inventoryVar->name");

    return redirect()->route('admin.inventories.vars')->with('success', 'Tạo loại phần thưởng mới thành công');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'            => 'required|integer|exists:inventory_vars,id',
      'name'          => 'required|string',
      'unit'          => 'required|string',
      'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
      'is_active'     => 'required|boolean',
      'form_inputs'   => 'required|string',
      'form_packages' => 'required|string',
      'min_withdraw'  => 'required|integer|min:0',
      'max_withdraw'  => 'required|integer|min:0',
    ]);

    $inventoryVar = InventoryVar::find($payload['id']);

    if ($inventoryVar === null) {
      return redirect()->route('admin.inventories.vars')->with('error', 'Không tìm thấy loại phần thưởng này');
    }

    if ($request->has('image')) {
      if ($request->hasFile('image')) {
        $payload['image'] = Helper::uploadFile($request->file('image'), 'do_spaces', 'inventory_vars');
      } else {
        $payload['image'] = null;
      }
    }

    $inputs     = [];
    $frm_inputs = Helper::text2array($payload['form_inputs'] ?? '');
    foreach ($frm_inputs as $key => $input) {
      $input = explode('|', $input);

      if (count($input) < 1) {
        unset($inputs[$key]);
        continue;
      }

      $options = [];

      if (count($input) > 2) {
        $options = explode(',', $input[2]);
      }

      $inputs[$key] = [
        'label'   => ucfirst(str_replace('_', ' ', $input[0])),
        'type'    => $input[1] ?? 'text',
        'options' => $options,
      ];
    }
    $payload['form_inputs'] = ($inputs);

    $packages     = [];
    $frm_packages = Helper::text2array($payload['form_packages'] ?? '');
    foreach ($frm_packages as $value) {
      $packages[$value] = $value . ' ' . $payload['unit'];
    }
    $payload['form_packages'] = ($packages);

    $inventoryVar->update($payload);

    Helper::addHistory("Cập nhật loại phần thưởng #" . $inventoryVar->id . ": $inventoryVar->name");

    return redirect()->route('admin.inventories.vars')->with('success', 'Cập nhật loại phần thưởng thành công');
  }

  public function delete(Request $request)
  {
    $payload = $request->validate([
      'id' => 'required|integer|exists:inventory_vars,id',
    ]);

    $inventoryVar = InventoryVar::find($payload['id']);

    if ($inventoryVar === null) {
      return redirect()->route('admin.inventories.vars')->with('error', 'Không tìm thấy loại phẩn thưởng này');
    }

    $inventoryVar->delete();

    Helper::addHistory("Deleted inventory var: $inventoryVar->name");

    return response()->json(['status' => 200, 'message' => 'Đã xoá loại phần thưởng ' . $inventoryVar->unit . ' thành công']);
  }
}
