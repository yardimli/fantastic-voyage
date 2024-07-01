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
			Schema::create('image_search_caches', function (Blueprint $table) {
				$table->id();
				$table->string('query')->nullable(); // search query
				$table->integer('page')->default(1); // page number (1, 2, 3, etc.)
				$table->string('per_page')->default(80); // number of results per page (20, 40, 60, etc.)
				$table->string('orientation')->nullable(); // orientation of the image (landscape, portrait, square)
				$table->string('size')->nullable(); // size of the image (small, medium, large, original)
				$table->longText('result')->nullable(); // search result, storing as JSON
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('image_search_caches');
		}
	};
