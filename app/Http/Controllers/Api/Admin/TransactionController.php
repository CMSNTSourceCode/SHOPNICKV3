<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
  public function index(Request $request)
  {
    $payload = $request->validate([
      'page'       => 'nullable|integer|min:1',
      'type'       => 'nullable|string',
      'limit'      => 'nullable|integer|min:1',
      'domain'     => 'nullable|string|max:255',
      'search'     => 'nullable|string|max:255',
      'sort_by'    => 'nullable|string|max:255',
      'username'   => 'nullable|string|max:255',
      'end_date'   => 'nullable|date_format:Y-m-d',
      'start_date' => 'nullable|date_format:Y-m-d',
      'sort_type'  => 'nullable|string|in:asc,desc',
    ]);

    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'asc';

    // extras
    $domain     = $payload['domain'] ?? null;
    $end_date   = $payload['end_date'] ?? null;
    $start_date = $payload['start_date'] ?? null;

    $query = \App\Models\Transaction::query();

    if ($search) {
      $query->where('code', 'like', '%' . $search . '%')
        ->orWhere('content', 'like', '%' . $search . '%')
        ->orWhere('username', 'like', '%' . $search . '%');
    }

    if ($payload['type'] ?? null) {
      $query->where('type', $payload['type']);
    }

    if ($payload['username'] ?? null) {
      $query->where('username', $payload['username']);
    }

    if ($start_date && $end_date) {
      $query->whereBetween('created_at', [$start_date, $end_date]);
    }

    if ($domain) {
      $query->where('domain', $domain);
    }

    $total = $query->count();

    $data = $query->skip($offset)
      ->take($limit)
      ->orderBy($sort_by, $sort_type)
      ->get();

    return response()->json([
      'data'    => [
        'meta' => [
          'page'  => (int) $page,
          'total' => (int) $total,
          'limit' => (int) $limit,
        ],
        'data' => $data,
      ],
      'status'  => 200,
      'message' => 'Get data success',
    ]);
  }
}
