<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawData extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'type',

    'name',
    'unit',
    'amount',
    'var_id',
    'inv_id',

    'status',
    'user_inputs',
    'admin_note',
    'user_id',
    'username',

    'after_value',
    'before_value',
  ];

  protected $hidden = [
    'user_inputs',
  ];

  protected $casts = [
    'user_inputs'  => 'array',

    'after_value'  => 'integer',
    'before_value' => 'integer',
  ];



  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function inventory()
  {
    return $this->belongsTo(Inventory::class, 'inv_id', 'id');
  }

  public function inventoryVar()
  {
    return $this->belongsTo(InventoryVar::class, 'var_id', 'id');
  }
}
