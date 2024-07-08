<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryData extends Model
{
    use HasFactory;

	protected $fillable = [
		'user_id',
		'activity_id',
		'step',
		'title',
		'image',
		'chapter_text',
		'chapter_voice',
		'choices',
		'choice',
		'language',
		'json_data',
	];
}
