<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GBOrder extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'name',
    'input_user',
    'input_pass',
    'input_extra',
    'payment',
    'user_id',
    'username',
    'status',
    'package_id',
    'group_id',
    'admin_note',
    'order_note',

    //
    'assigned_to',
    'assigned_at',
    'assigned_note',
    'assigned_type',
    'assigned_status',
    'assigned_payment',
    'assigned_complain',
    'assigned_completed',

  ];

  protected $casts = [
    'user_id'            => 'integer',
    'package_id'         => 'integer',
    'group_id'           => 'integer',

    'assigned_at'        => 'datetime',
    'assigned_payment'   => 'double',
    'assigned_complain'  => 'boolean',
    'assigned_completed' => 'datetime',
  ];

  public function package()
  {
    return $this->belongsTo(GBPackage::class);
  }

  public function group()
  {
    return $this->belongsTo(GBGroup::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
