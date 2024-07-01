<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	
	class Customer extends Model
	{
		use HasFactory;
		
		protected $fillable = [
			'name',
			'email',
			'created_at',
			'updated_at',
		];
		
		public function orders()
		{
			return $this->hasMany(Order::class);
		}
		
	}
