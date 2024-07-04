<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Activity extends Model
	{
		use HasFactory;

		protected $fillable = [
			'user_id',
			'title',
			'cover_image',
			'keywords',
			'prompt',
			'language',
			'voice_id',
			'question_count',
			'theme',
			'is_deleted'
		];
	}
