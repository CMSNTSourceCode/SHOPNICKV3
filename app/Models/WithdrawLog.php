<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'unit',
    'value',
    'status',
    'user_note',
    'admin_note',
    'user_id',
    'username',
    'current_balance'
  ];

  protected $casts = [
    'current_balance' => 'integer',
  ];
}
