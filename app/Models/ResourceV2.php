<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceV2 extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'type',
    'domain',
    'is_bulk',
    'group_id',
    'username',
    'password',
    'extra_data',
    'buyer_name',
    'buyer_code',
    'buyer_paym',
    'buyer_date',
  ];

  protected $hidden = [
    'username',
    'password',
    'extra_data',
    'buyer_name',
    'buyer_code',
    'buyer_paym',
    'buyer_date',
  ];

  public function parent()
  {
    return $this->belongsTo(ListItemV2::class, 'code', 'code');
  }

}
