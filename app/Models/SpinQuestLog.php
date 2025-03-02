<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinQuestLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'unit',
    'prize',
    'price',
    'status',
    'content',
    'user_id',
    'username',
    'is_fake_data',
    'spin_quest_id',
  ];

  protected $casts = [
    'is_fake_data' => 'boolean',
  ];
}
