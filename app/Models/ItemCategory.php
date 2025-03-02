<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
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
		return $this->hasMany(ItemGroup::class, 'category_id', 'id');
	}
}