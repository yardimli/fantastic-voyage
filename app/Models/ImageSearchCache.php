<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageSearchCache extends Model
{
    use HasFactory;
		protected $fillable = [
			'query',
			'page',
			'per_page',
			'orientation',
			'size',
			'result',
		];
}
