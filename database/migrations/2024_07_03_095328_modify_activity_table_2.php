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
				$table->string('cover_image',255)->nullable()->after('title');
				$table->string('keywords',255)->nullable()->after('cover_image');
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::table('activities', function (Blueprint $table) {
				$table->dropColumn('cover_image');
				$table->dropColumn('keywords');
			});
			//
		}
	};
