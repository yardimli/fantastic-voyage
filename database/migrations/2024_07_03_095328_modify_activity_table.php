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
			Schema::table('activities', function (Blueprint $table) {
				$table->text('prompt')->nullable()->after('title');
				$table->string('language', 255)->nullable()->after('title');
				$table->string('voice_id', 255)->nullable()->after('title');
				$table->integer('question_count')->default(1)->after('title');
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::table('activities', function (Blueprint $table) {
				$table->dropColumn('prompt');
				$table->dropColumn('language');
				$table->dropColumn('voice_id');
				$table->dropColumn('question_count');
			});
			//
		}
	};
