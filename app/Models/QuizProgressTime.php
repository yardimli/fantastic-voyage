<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizProgressTime extends Model
{
    use HasFactory;

	protected $fillable = [
		'job_id',
		'percentage',
		'description',
	];
}
