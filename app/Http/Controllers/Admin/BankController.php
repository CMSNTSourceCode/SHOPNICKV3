<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Transaction;
use Helper;
use Illuminate\Http\Request;

class BankController extends Controller
{
  public function index()
  {
    $banks = BankAccount::all();

    return view('admin.banks.index', compact('banks'));
  }

  public function deposit()
  {

    $total = Transaction::where('type', 'deposit-bank')->sum('amount');
    $week  = Transaction::where('type', 'deposit-bank')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
    $month = Transaction::where('type', 'deposit-bank')->whereMonth('created_at', now()->month)->whereYear('created_at', date('Y'))->sum('amount');
    $today = Transaction::where('type', 'deposit-bank')->whereDate('created_at', now()->toDateString())->sum('amount');

    $stats['banks']   = [
      'total' => $total,
      'week'  => $week,
      'month' => $month,
      'today' => $today,
    ];
    $stats['t_banks'] = [
      'total' => 'Toàn thời gian',
      'week'  => 'Tuần này',
      'month' => 'Tháng này',
      'today' => 'Hôm nay',
    ];

    return view('admin.banks.deposit', compact('stats'));
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'name'   => 'required|string',
      'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'owner'  => 'required|string',
      'number' => 'required|string',
      'branch' => 'nullable|string',
    ]);
    //
    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'));
    } else {
      $payload['image'] = null;
    }

    BankAccount::create($payload);

    Helper::addHistory('Thêm tài khoản ' . $payload['number'] . ', ngân hàng ' . $payload['name']);

    return redirect()->back()->with('success', 'Thêm tài khoản ngân hàng thành công.');
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'     => 'required|integer',
      'name'   => 'required|string',
      'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'owner'  => 'required|string',
      'number' => 'required|string',
      'branch' => 'nullable|string',
      'status' => 'required|boolean',
    ]);

    $bank = BankAccount::findOrFail($request->id);

    if ($request->hasFile('image')) {
      $payload['image'] = Helper::uploadFile($request->file('image'));
    } else {
      $payload['image'] = $bank->image;
    }

    $bank->update($payload);

    Helper::addHistory('Cập nhật tài khoản ngân hàng ' . $payload['number'] . ' #' . $payload['id']);

    return redirect()->back()->with('success', 'Cập nhật tài khoản ngân hàng #' . $payload['id'] . ' thành công.');
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|integer',
    ]);

    $bank = BankAccount::findOrFail($request->id);

    $bank->delete();

    Helper::addHistory('Xóa tài khoản ngân hàng ' . $bank->number . ' #' . $bank->id);

    return response()->json([
      'status'  => 200,
      'message' => 'Xóa tài khoản ngân hàng thành công.',
    ]);
  }
}
