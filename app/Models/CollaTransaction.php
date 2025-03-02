<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaTransaction extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'username',
    'type',
    'amount',
    'status',
    'reference',
    'description',
    'balance_before',
    'balance_after',
  ];

  protected $casts = [
    'balance_before' => 'integer',
    'balance_after'  => 'integer',
  ];

  protected $appends = [
    'prefix', // Add this line
    'formatted_amount',
    'formatted_balance_before',
    'formatted_balance_after',
  ];

  public function getPrefixAttribute() // Add this method
  {
    return $this->balance_after > $this->balance_before ? '+' : '-';
  }

  public function getFormattedAmountAttribute()
  {
    return Helper::formatCurrency($this->amount);
  }

  public function getFormattedBalanceBeforeAttribute()
  {
    return Helper::formatCurrency($this->balance_before);
  }

  public function getFormattedBalanceAfterAttribute()
  {
    return Helper::formatCurrency($this->balance_after);
  }


  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
