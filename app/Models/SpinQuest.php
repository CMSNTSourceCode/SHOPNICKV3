<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinQuest extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'type',
    'unit',
    'price',
    'cover',
    'descr',
    'image',
    'prizes',
    'status',
    'priority',
    'store_id',
    'invar_id',
    'play_times',
    'category_id',
  ];

  protected $casts = [
    'status'     => 'boolean',
    'prizes'     => 'array',
    'play_times' => 'integer',
  ];

  public function store()
  {
    return $this->belongsTo(Group::class);
  }

  public function canPlay()
  {
    $arr = $this->prizes ?? [];

    $totalPercent = 0;

    foreach ($arr as $item) {
      $totalPercent += $item['percent'] ?? 0;
    }

    if ($totalPercent === 0) {
      return false;
    }

    return true;
  }

  public function playGame($test = false)
  {
    $items         = $this->prizes ?? [];
    $weightedItems = [];

    if ($test) {
      foreach ($items as $index => $item) {
        $items[$index]['percent'] = 80;
      }
    }

    foreach ($items as $index => $item) {
      if ((int) $item['percent'] === 0) {
        continue;
      }

      for ($i = 0; $i < $item['percent']; $i++) {
        // $weightedItems[] = $item['label'];
        $weightedItems[] = $index;
      }
    }
    //
    $randomIndex = $weightedItems[array_rand($weightedItems)] + 1;


    $location = null;
    switch ($randomIndex) {
      case '1':
        $location = 360;
        break;
      case '2':
        $location = 320;
        break;
      case '3':
        $location = 270;
        break;
      case '4':
        $location = 230;
        break;
      case '5':
        $location = 180;
        break;
      case '6':
        $location = 130;
        break;
      case '7':
        $location = 85;
        break;
      case '8':
        $location = 44;
        break;
      default:
        $location = null;
        break;
    }

    return [
      'data'     => $items[$randomIndex - 1] ?? null,
      'location' => $location ?? null,
    ];
  }

  public function inventoryVar()
  {
    return $this->belongsTo(InventoryVar::class, 'invar_id');
  }
}
