<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupV2 extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'image',
    'type',
    'slug',
    'descr',
    'sold',
    'price',
    'priority',
    'meta_seo',
    'descr_seo',
    'discount',
    'currency',
    'status',
    'username',
    'game_type',
    'category_id',
    'category_name',
  ];

  protected $casts = [
    'status'      => 'boolean',
    'meta_seo'    => 'array',
    'category_id' => 'integer',
  ];

  protected $appends = [
    'sold',
    'in_stock'
  ];

  public function getSoldAttribute()
  {
    return $this->items->sum('sold');
  }

  public function getInStockAttribute()
  {
    return $this->items->sum('amount');
  }

  public static function generateSlug($name)
  {
    $slug = str()->slug($name);

    if (self::where('slug', $slug)->exists()) {
      $slug .= '-' . rand(1000, 9999);
    }

    return $slug;
  }

  public function category()
  {
    return $this->belongsTo(CategoryV2::class, 'category_id', 'id');
  }

  public function items()
  {
    return $this->hasMany(ListItemV2::class, 'group_id', 'id');
  }
}
