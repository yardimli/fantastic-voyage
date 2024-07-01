<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void
		{
			Schema::create('order_items', function (Blueprint $table) {
				$table->id();
				$table->unsignedBigInteger('order_id');
				$table->unsignedBigInteger('shop_order_id');
				$table->unsignedBigInteger('product_id');
				$table->unsignedBigInteger('variant_id');
				$table->string('product_name');
				$table->string('variant_name');
				$table->decimal('price', 10, 2);
				$table->timestamps();
			});
		}
		
		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('order_items');
		}
	};
