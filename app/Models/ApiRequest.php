<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class ApiRequest extends Model
	{
		use HasFactory;

		protected $fillable = [
			'url',
			'post_data',
			'results',
			'file_name',
			'auth_user_id'
		];
	}
