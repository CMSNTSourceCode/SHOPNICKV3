<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItemV2 extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'type',
    'code',
    'cost',
    'image',
    'price',
    'status',
    'is_bulk',
    'discount',
    'priority',
    'group_id',
    'list_image',
    'highlights',
    'description',
    'resource_code',
  ];

  protected $hidden = [
    'cost',
    'profit',
    'revenue',
    'resources',
    'extra_data',
    'resource_code',
  ];

  protected $casts = [
    'status'     => 'boolean',
    'is_bulk'    => 'integer',
    'list_image' => 'array',
    'highlights' => 'array',
  ];

  protected $appends = [
    'profit',
    'sold',
    'amount',
    'revenue',
    'payment',
    'price_str',
    'price_discount',
  ];

  public function getIsBulkAttribute($value)
  {
    // tự ý thay đổi giá trị này license sẽ bị hủy / ngưng hỗ trợ!
    if (!feature_enabled('bulk-orders')) {
      return 1;
    }

    return (int) $value;
  }

  public function getProfitAttribute()
  {
    // $cost    = $this->resources->where('buyer_name', '!=', null)->count() * $this->cost;
    // $payment = $this->resources->where('buyer_name', '!=', null)->sum('buyer_paym');

    // return ($payment - $cost);

    return -1;
  }

  public function getAmountAttribute()
  {
    return $this->resources->where('buyer_name', null)->where('buyer_code', null)->count();
  }

  public function getSoldAttribute()
  {
    return $this->resources->where('buyer_name', '!=', null)->count();
  }

  public function getPaymentAttribute()
  {
    $payment = $this->price;

    if ($this->discount > 0) {
      $payment = $this->price - ($this->price * $this->discount / 100);
    }

    return $payment;
  }

  public function getPriceStrAttribute()
  {
    $totalPrice = $this->price;

    if ($this->discount > 0) {
      $totalPrice = $this->price - ($this->price * $this->discount / 100);
    }

    return Helper::formatCurrency($totalPrice);
  }

  public function getPriceDiscountAttribute()
  {
    if ($this->discount === 0) {
      return 0;
    }

    $discount = $this->price - ($this->price * $this->discount / 100);

    return Helper::formatCurrency($discount);
  }

  public static function generateCode()
  {
    $code = date('y') . date('m') . Helper::randomNumber(4);

    if (self::where('code', $code)->exists()) {
      return self::generateCode();
    }

    return $code;
  }

  public function getRevenueAttribute()
  {
    $revenue = $this->resources->where('buyer_name', '!=', null)->sum('buyer_paym');

    return ($revenue);
  }

  public function group()
  {
    return $this->belongsTo(GroupV2::class);
  }

  public function resources()
  {
    return $this->hasMany(ResourceV2::class, 'code', 'code');
  }
}
