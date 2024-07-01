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
			Schema::create('orders', function (Blueprint $table) {
				$table->id();
				$table->unsignedBigInteger('customer_id');
				$table->string('order_id')->unique(); // Add order_id column
				$table->string('status');
				$table->decimal('total', 10, 2);
				$table->decimal('subtotal', 10, 2);
				$table->decimal('tax', 10, 2);
				$table->decimal('tax_rate', 5, 2);
				$table->string('currency');
				$table->timestamps();
				$table->dateTime('refunded_at')->nullable();
			});
		}
		
		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('orders');
		}
	};
