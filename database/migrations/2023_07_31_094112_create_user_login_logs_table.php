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
			Schema::create('user_login_logs', function (Blueprint $table) {
				$table->id();
				$table->integer('user_id')->unsigned();
				$table->timestamp('last_login')->nullable();
				$table->integer('continue_login_count')->default(0);
				$table->timestamps();
			});

		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('user_login_logs');
		}
	};
