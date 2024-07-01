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
			Schema::create('api_requests', function (Blueprint $table) {
				$table->id();
				$table->string('url')->nullable();
				$table->text('post_data')->nullable();
				$table->text('results')->nullable();
				$table->integer('auth_user_id')->unsigned()->default(0);
				$table->integer('auth_student_id')->unsigned()->default(0);
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('api_requests');
		}
	};
