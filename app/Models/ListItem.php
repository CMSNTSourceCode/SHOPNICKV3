<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'type',
    'code',
    'cost',
    'image',
    'price',
    'domain',
    'discount',
    'status',
    'username',
    'password',
    'list_skin',
    'raw_skins',
    'list_champ',
    'raw_champions',

    // dot kich
    'cf_the_loai',
    'cf_vip_ingame',
    'cf_vip_amount',

    //

    'extra_data',
    'description',
    'list_image',
    'highlights',
    'priority',
    'group_id',
    'buyer_name',
    'buyer_code',
    'buyer_paym',
    'buyer_date',

    'staff_name',
    'staff_status',
    'staff_payment',
    'staff_completed_at',
  ];

  protected $hidden = [
    'username',
    'password',
    'raw_skins',
    'extra_data',
    'buyer_name',
    'buyer_code',
    'buyer_paym',
    'buyer_date',
    'raw_champions',

    'staff_name',
    'staff_status',
    'staff_payment',
    'staff_completed_at',
  ];

  protected $casts = [
    'status'             => 'boolean',
    'list_skin'          => 'array',
    'list_champ'         => 'array',
    'list_image'         => 'array',
    'highlights'         => 'array',

    'buyer_date'         => 'datetime',
    'staff_completed_at' => 'datetime',
  ];

  protected $appends = [
    'is_sold',
    'payment',
    'price_str',
    'price_discount',
  ];

  public function getPaymentAttribute()
  {
    $payment = $this->price;

    if ($this->discount > 0) {
      $payment = $this->price - ($this->price * $this->discount / 100);
    }

    if ($payment <= 0) {
      $payment = 0;
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

  public function getListSkinRawAttribute()
  {
    if ($this->list_skin === null && $this->group?->game_type === 'lien-minh') {
      return '';
    }

    $listSkinName = collect($this->list_skin)->pluck('name')->toArray();

    return implode(', ', $listSkinName);
  }

  public function getListChampRawAttribute()
  {
    if ($this->list_champ === null && $this->group?->game_type === 'lien-minh') {
      return '';
    }

    $listChampName = collect($this->list_champ)->pluck('name')->toArray();

    return implode(', ', $listChampName);
  }

  public function getPriceDiscountAttribute()
  {
    if ($this->discount === 0) {
      return 0;
    }

    $discount = $this->price - ($this->price * $this->discount / 100);

    return Helper::formatCurrency($discount);
  }

  public function getIsSoldAttribute()
  {
    if ($this->buyer_name === null) {
      return false;
    }

    return true;
  }

  public static function generateCode()
  {
    $code = date('y') . date('m') . Helper::randomNumber(4);

    if (self::where('code', $code)->exists()) {
      return self::generateCode();
    }

    return $code;
  }

  public function group()
  {
    return $this->belongsTo(Group::class);
  }

  public function archive()
  {
    if ($this->type === 'account_group') {
      return $this->hasMany(ListItemArchive::class, 'code', 'id');
    }

    return null;
  }
}
