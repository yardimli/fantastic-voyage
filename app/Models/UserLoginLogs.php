<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class UserLoginLogs extends Model
	{
		use HasFactory;

		protected $fillable = [
			'user_id',
			'last_login',
			'continue_login_count',
		];

	}
