<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateUser extends Model
{
  use HasFactory;

  protected $fillable = [
    'username',
    'affiliate_user',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'affiliate_user', 'username');
  }

  public function affiliate()
  {
    return $this->belongsTo(Affiliate::class, 'affiliate_user', 'username');
  }
}
