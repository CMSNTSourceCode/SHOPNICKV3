<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $payload = $request->validate([
            'page' => 'nullable|integer',
            'limit' => 'nullable|integer',
            'search' => 'nullable|string',
            'sort_by' => 'nullable|string',
            'sort_type' => 'nullable|string|in:asc,desc',
        ]);

        $query = Transaction::where('user_id', auth()->user()->id);

        if (isset($payload['search'])) {
            $query = $query->where('code', 'like', '%'.$payload['search'].'%')
                ->orWhere('content', 'like', '%'.$payload['search'].'%');
        }

        if (isset($payload['sort_by'])) {
            $query = $query->orderBy($payload['sort_by'], $payload['sort_type'] ?? 'asc');
        }

        $meta = [
            'page' => (int) ($payload['page'] ?? 1),
            'limit' => (int) ($payload['limit'] ?? 10),
            'total_rows' => $query->count(),
            'total_page' => ceil($query->count() / ($payload['limit'] ?? 10)),
        ];

        $data = $query->skip(($meta['page'] - 1) * $meta['limit'])->take($meta['limit']);

        return response()->json([
            'data' => [
                'meta' => $meta,
                'data' => $data->get(),
            ],
            'status' => 200,
            'message' => 'Lấy danh sách giao dịch thành công',
        ], 200);

    }
}
