<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'      => 'nullable|integer',
      'limit'     => 'nullable|integer',
      'search'    => 'nullable|string',
      'sort_by'   => 'nullable|string',
      'sort_type' => 'nullable|string|in:asc,desc',
    ]);

    $query = Invoice::where('user_id', $request->user()->id);

    if (isset($payload['search'])) {
      $query = $query->where('content', 'like', '%' . $payload['search'] . '%')
        ->orWhere('ip_address', 'like', '%' . $payload['search'] . '%');
    }

    if (isset($payload['sort_by'])) {
      $query = $query->orderBy($payload['sort_by'], $payload['sort_type'] ?? 'asc');
    }

    $meta = [
      'page'       => (int) ($payload['page'] ?? 1),
      'limit'      => (int) ($payload['limit'] ?? 10),
      'total_rows' => $query->count(),
      'total_page' => ceil($query->count() / ($payload['limit'] ?? 10)),
    ];

    $data = $query->skip(($meta['page'] - 1) * $meta['limit'])->take($meta['limit'])->get();

    if ($data->isEmpty()) {
      return response()->json([
        'data'    => [
          'meta' => $meta,
          'data' => $data,
        ],
        'status'  => 204,
        'message' => 'Không có hoạt động nào',
      ], 204);
    }

    return response()->json([
      'data'    => [
        'meta' => $meta,
        'data' => $data,
      ],
      'status'  => 200,
      'message' => 'Lấy danh sách hoạt động thành công',
    ], 200);

  }

  public function show(Request $request, $id)
  {
    $invoice = Invoice::where('user_id', $request->user()->id)->findOrFail($id);

    return response()->json([
      'data'    => $invoice->makeVisible('payment_details'),
      'status'  => 200,
      'message' => 'Lấy thông tin hóa đơn thành công',
    ], 200);
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'amount'  => 'required|integer|min:10000',
      'bank_id' => 'required|integer|exists:bank_accounts,id',
    ]);

    $amount = $payload['amount'];
    $bankId = $payload['bank_id'];

    $user = $request->user();
    $bank = BankAccount::findOrFail($bankId);

    $invoiceCount = Invoice::where('user_id', $user->id)->where('status', 'processing')->count();
    if ($invoiceCount > 3) {
      return response()->json([
        'status'  => 400,
        'message' => 'Bạn đã có 3 hóa đơn đang chờ xử lý, vui lòng chờ xử lý hoặc hủy bỏ hóa đơn cũ trước khi tạo hóa đơn mới',
      ], 400);
    }

    $invoice = Invoice::create([
      'code'            => 'INV' . time(),
      'type'            => 'deposit',
      'status'          => 'processing',
      'amount'          => $amount,
      'user_id'         => $user->id,
      'username'        => $user->username,
      'currency'        => 'VND',
      'description'     => 'Nạp Tiền Tài Khoản',
      'payment_details' => [
        'name'   => $bank->name,
        'owner'  => $bank->owner,
        'number' => $bank->number,
      ],
      'paid_at'         => null,
      'expired_at'      => now()->addHours(6),
    ]);

    return response()->json([
      'data'    => $invoice,
      'status'  => 200,
      'message' => 'Tạo hóa đơn nạp tiền thành công',
    ], 200);
  }
}
