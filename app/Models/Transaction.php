<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Transaction extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'amount',
    'cost_amount',
    'balance_after',
    'balance_before',
    'type',
    'extras',
    'order_id',
    'sys_note',
    'status',
    'content',
    'user_id',
    'username',
    'domain',
  ];

  protected $hidden = [
    'order_id',
    'sys_note',
    'extras',
  ];

  protected $appends = [
    'prefix',
  ];

  protected $casts = [
    'extras'   => 'array',
    'order_id' => 'string',
  ];

  public function getPrefixAttribute()
  {
    return $this->balance_before > $this->balance_after ? '-' : '+';
  }

  public static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      if (Schema::hasColumn($model->getTable(), 'domain')) {
        // $model->domain = Helper::getDomain();
      }
    });
  }
}
