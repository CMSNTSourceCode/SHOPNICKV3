<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
  public function accountsV1(Request $request)
  {
    $payload   = $request->validate([

      'sold'       => 'nullable|integer',
      'group'      => 'nullable|integer',
      'buyer_name' => 'nullable|string|max:255',
      'username'   => 'nullable|string|max:255',
      'start_date' => 'nullable|date',
      'end_date'   => 'nullable|date',
      'domain'     => 'nullable|string|max:255',

      'page'       => 'nullable|integer|min:1',
      'limit'      => 'nullable|integer|min:1',
      'search'     => 'nullable|string|max:255',
      'sort_by'    => 'nullable|string|max:255',
      'sort_type'  => 'nullable|string|in:asc,desc',
    ]);
    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'asc';

    $query = \App\Models\ListItem::query();

    if ($search) {
      // $query->where('code', 'like', '%' . $search . '%');
      $query->where(function ($sub) use ($search) {
        $sub->where('code', 'like', '%' . $search . '%')
          ->orWhere('name', 'like', '%' . $search . '%')
          ->orWhere('username', 'like', '%' . $search . '%');
      });
    }

    if (isset($payload['sold'])) {
      if ($payload['sold'] === '0') {
        $query->where('buyer_name', null);
      } else {
        $query->where('buyer_name', '!=', null);
      }

    }

    if ($payload['username'] ?? null) {
      $query->where('username', $payload['username']);
    }

    if ($payload['group'] ?? null) {
      $query->where('group_id', $payload['group']);
    }

    if ($payload['buyer_name'] ?? null) {
      $query->where('buyer_name', $payload['buyer_name']);
    }

    if ($payload['start_date'] ?? null) {
      $query->where('buyer_date', '>=', $payload['start_date']);
    }

    if ($payload['end_date'] ?? null) {
      $query->where('buyer_date', '<=', $payload['end_date']);
    }

    if ($payload['domain'] ?? null) {
      $query->where('domain', $payload['domain']);
    }

    $total = $query->count();

    $data = $query->skip($offset)
      ->take($limit)
      ->orderBy($sort_by, $sort_type)
      ->with('group')
      ->get()->makeVisible([
          'buyer_name',
          'buyer_code',
          'buyer_paym',
          'buyer_date',
          'username',
          'staff_name',
          'staff_status',
          'staff_payment',
        ]);

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

  public function accountsV2(Request $request)
  {
    $payload   = $request->validate([

      'sold'       => 'nullable|integer',
      'group'      => 'nullable|integer',
      'buyer_name' => 'nullable|string|max:255',
      'username'   => 'nullable|string|max:255',
      'start_date' => 'nullable|date',
      'end_date'   => 'nullable|date',
      'domain'     => 'nullable|string|max:255',

      'page'       => 'nullable|integer|min:1',
      'limit'      => 'nullable|integer|min:1',
      'search'     => 'nullable|string|max:255',
      'sort_by'    => 'nullable|string|max:255',
      'sort_type'  => 'nullable|string|in:asc,desc',
    ]);
    $page      = $payload['page'] ?? 1;
    $limit     = $payload['limit'] ?? 10;
    $search    = $payload['search'] ?? null;
    $offset    = ($page - 1) * $limit;
    $sort_by   = $payload['sort_by'] ?? 'id';
    $sort_type = $payload['sort_type'] ?? 'asc';

    $query = \App\Models\ResourceV2::query();

    if ($search) {
      // $query->where('code', 'like', '%' . $search . '%');
      $query->where(function ($sub) use ($search) {
        $sub->where('code', 'like', '%' . $search . '%')
          ->orWhere('username', 'like', '%' . $search . '%')
          ->orWhere('buyer_name', 'like', '%' . $search . '%');
      });
    }

    if (isset($payload['sold'])) {
      if ($payload['sold'] === '0') {
        $query->where('buyer_name', null);
      } else {
        $query->where('buyer_name', '!=', null);
      }

    }

    if ($payload['username'] ?? null) {
      $query->where('username', $payload['username']);
    }

    if ($payload['group'] ?? null) {
      $query->where('code', $payload['group']);
    }

    if ($payload['buyer_name'] ?? null) {
      $query->where('buyer_name', $payload['buyer_name']);
    }

    if ($payload['start_date'] ?? null) {
      $query->where('buyer_date', '>=', $payload['start_date']);
    }

    if ($payload['end_date'] ?? null) {
      $query->where('buyer_date', '<=', $payload['end_date']);
    }

    if ($payload['domain'] ?? null) {
      $query->where('domain', $payload['domain']);
    }

    $total = $query->count();

    $data = $query->skip($offset)
      ->take($limit)
      ->orderBy($sort_by, $sort_type)
      ->with('parent')
      ->get()->makeVisible([
          'username',
          'extra_data',
          'buyer_name',
          'buyer_code',
          'buyer_paym',
          'buyer_date',
        ]);

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
