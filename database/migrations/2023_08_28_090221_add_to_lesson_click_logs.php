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
			Schema::table('lesson_click_logs', function (Blueprint $table) {
				$table->integer('auth_user_id')->unsigned();
				$table->integer('auth_student_id')->unsigned();
				$table->boolean('human_detected')->default(0);
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::table('lesson_click_logs', function (Blueprint $table) {
				$table->dropColumn('auth_user_id');
				$table->dropColumn('auth_student_id');
				$table->dropColumn('human_detected');
			});
		}
	};
