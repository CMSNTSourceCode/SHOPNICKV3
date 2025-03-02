<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GBCategory extends Model
{
  use HasFactory;

  use HasFactory;

  protected $fillable = [
    'name',
    'slug',
    '_lft',
    'status',
    'username',
    'priority',
  ];

  public static function generateSlug($name)
  {
    $slug = str()->slug($name);

    if (self::where('slug', $slug)->exists()) {
      $slug .= '-' . rand(1000, 9999);
    }

    return $slug;
  }

  public function groups()
  {
    return $this->hasMany(GBGroup::class, 'category_id', 'id');
  }
}