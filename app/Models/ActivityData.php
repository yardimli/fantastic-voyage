<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityData extends Model
{
    use HasFactory;

	protected $fillable = [
		'user_id',
		'activity_id',
		'category',
		'grade',
		'language',
		'json_data'
	];
}
