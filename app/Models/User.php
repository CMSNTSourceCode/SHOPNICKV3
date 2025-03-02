<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens;
  use HasFactory;
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'email',
    'username',
    'password',
    'fullname',
    'phone',
    'avatar',
    'balance',
    'balance_1',
    'balance_2',
    'total_deposit',
    'total_withdraw',
    'status',
    'role',
    'colla_type',
    'colla_percent',
    'colla_balance',
    'colla_pending',
    'colla_withdraw',

    'received_gift',
    'referral_by',
    'referral_code',
    'access_token',
    'ip_address',
    'last_action',
    'register_ip',
    'register_by',

    'last_login_at',
    'last_login_ip',

    'staff_group_ids',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password'          => 'hashed',

    'received_gift'     => 'boolean',
    'staff_group_ids'   => 'array',
  ];

  // History
  public function histories()
  {
    return $this->hasMany(History::class);
  }

  // Transaction
  public function transactions()
  {
    return $this->hasMany(Transaction::class);
  }

  // Referral
  public function referrals()
  {
    return $this->hasMany(User::class, 'referral_by', 'username');
  }

  public function referrer()
  {
    return $this->belongsTo(User::class, 'referral_by', 'username');
  }

  // Affiliate
  public function affiliate()
  {
    return $this->hasOne(Affiliate::class, 'username', 'username');
  }

  // staff_group_ids
  public function getStaffGroupIdsAttribute($value)
  {
    return $value === null ? [] : json_decode($value, true);
  }

  // Inventory
  public function inventories()
  {
    return $this->hasMany(Inventory::class, 'username', 'username');
  }
}
