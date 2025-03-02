<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollaTransaction;
use App\Models\Group;
use App\Models\Transaction;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index()
  {
    return view('admin.users.index');
  }

  public function show($id)
  {
    $user = User::findOrFail($id);

    return view('admin.users.show', compact('user'));
  }

  public function update(Request $request, $id)
  {
    $action = $request->input('action', null);

    if ($action === 'update-info') {
      $payload = $request->validate([
        'role'            => 'required|in:admin,member,collaborator,partner,accounting',
        'email'           => 'required|email|unique:users,email,' . $id,
        'status'          => 'required|in:active,locked',
        'password'        => 'nullable|string|min:6',
        'balance_1'       => 'required|numeric|min:0',
        'colla_type'      => 'nullable|in:account,boosting,items',
        'colla_percent'   => 'nullable|numeric|min:0|max:100',
        'staff_group_ids' => 'nullable|array',
      ]);

      $user = User::findOrFail($id);

      if (isset($payload['password'])) {
        $payload['password'] = bcrypt($payload['password']);
      } else {
        unset($payload['password']);
      }

      if (isset($payload['colla_type']) && $payload['colla_type'] === 'account') {
        $group_ids = [];
        $group_ids = array_map('intval', $payload['staff_group_ids'] ?? $user->staff_group_ids);

        $getGroups = Group::where('status', true)->whereIn('id', $group_ids)->get();

        $payload['staff_group_ids'] = $getGroups->pluck('id')->toArray();
      } else {
        $payload['staff_group_ids'] = [];
      }

      $user->update($payload);

      Helper::addHistory('Cập nhật thông tin của ' . $user->username . ' [' . $action . ']', $payload);

      return redirect()->back()->with('success', 'Cập nhật thông tin của ' . $user->username . ' thành công');
    } elseif ($action === 'plus-money') {
      $payload = $request->validate([
        'amount' => 'required|numeric|min:0',
        'reason' => 'nullable|string|max:255',
      ]);

      $user = User::findOrFail($id);

      $user->balance += $payload['amount'];
      $user->total_deposit += $payload['amount'];
      $user->save();

      $ref = $user->referrer;
      if ($ref !== null) {
        $affiliate = $ref->affiliate;
        if ($affiliate !== null) {
          $affiliate->increment('total_deposit', $payload['amount']);
        }
      }

      Transaction::create([
        'code'           => 'SP3-' . Helper::randomString(7, true),
        'amount'         => $payload['amount'],
        'balance_after'  => $user->balance,
        'balance_before' => $user->balance - $payload['amount'],
        'type'           => 'deposit-bank',
        'extras'         => [
          'reason' => ($payload['reason'] ?? ''),
          'change' => 'admin-change',
        ],
        'status'         => 'paid',
        'content'        => '#' . auth()->id() . ': ' . ($payload['reason'] ?? ''),
        'user_id'        => $user->id,
        'username'       => $user->username,
      ]);

      Helper::addHistory('Cộng tiền thành công cho ' . $user->username . ' [' . $action . ']', $payload);

      return redirect()->back()->with('success', 'Cộng tiền thành công cho ' . $user->username . ', số dư cuối : ' . Helper::formatCurrency($user->balance));
    } elseif ($action === 'sub-money') {
      $payload = $request->validate([
        'amount' => 'required|numeric|min:0',
        'reason' => 'nullable|string|max:255',
      ]);

      $user = User::findOrFail($id);

      $user->balance -= $payload['amount'];
      $user->save();

      Transaction::create([
        'code'           => 'SP3-' . Helper::randomString(10, true),
        'amount'         => $payload['amount'],
        'balance_after'  => $user->balance,
        'balance_before' => $user->balance + $payload['amount'],
        'type'           => 'admin-change',
        'extras'         => [
          'reason' => ($payload['reason'] ?? ''),
        ],
        'status'         => 'paid',
        'content'        => '#' . auth()->id() . ': ' . ($payload['reason'] ?? ''),
        'user_id'        => $user->id,
        'username'       => $user->username,
      ]);

      Helper::addHistory('Trừ tiền tài khoản ' . $user->username . ' thành công [' . $action . ']', $payload);

      return redirect()->back()->with('success', 'Trừ tiền tài khoản ' . $user->username . ', số dư cuối : ' . Helper::formatCurrency($user->balance));
    } else if ($action === 'plus-commision') {
      $payload = $request->validate([
        'amount' => 'required|numeric|min:0',
        'reason' => 'nullable|string|max:255',
      ]);

      $user = User::findOrFail($id);

      $user->colla_balance += $payload['amount'];
      $user->save();

      CollaTransaction::create([
        'type'           => 'boosting',
        'user_id'        => $user->id,
        'username'       => $user->username,
        'amount'         => $payload['amount'],
        'status'         => 'Completed',
        'reference'      => null,
        'description'    => '#' . auth()->id() . ': ' . ($payload['reason'] ?? ''),
        'balance_before' => $user->colla_balance - $payload['amount'],
        'balance_after'  => $user->colla_balance,
      ]);

      Helper::addHistory('Cộng hoa hồng cho ' . $user->username . ' [' . $action . ']', $payload);

      return redirect()->back()->with('success', 'Cộng hoa hồng cho ' . $user->username . ', số dư cuối : ' . Helper::formatCurrency($user->colla_balance));
    } else if ($action === 'sub-commision') {
      $payload = $request->validate([
        'amount' => 'required|numeric|min:0',
        'reason' => 'nullable|string|max:255',
      ]);

      $user = User::findOrFail($id);

      $user->colla_balance -= $payload['amount'];
      $user->save();

      CollaTransaction::create([
        'type'           => 'boosting',
        'user_id'        => $user->id,
        'username'       => $user->username,
        'amount'         => $payload['amount'],
        'status'         => 'Completed',
        'reference'      => null,
        'description'    => '#' . auth()->id() . ': ' . ($payload['reason'] ?? ''),
        'balance_before' => $user->colla_balance + $payload['amount'],
        'balance_after'  => $user->colla_balance,
      ]);

      Helper::addHistory('Trừ hoa hồng cho ' . $user->username . ' [' . $action . ']', $payload);

      return redirect()->back()->with('success', 'Trừ hoa hồng cho ' . $user->username . ', số dư cuối : ' . Helper::formatCurrency($user->colla_balance));
    } else {
      return redirect()->back()->with('error', 'Không tìm thấy hành động');
    }
  }
}
