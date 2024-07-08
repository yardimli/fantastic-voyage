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
			Schema::create('story_data', function (Blueprint $table) {
				$table->id();
				$table->integer('user_id')->default(0)->unsigned();
				$table->integer('activity_id')->default(0)->unsigned();
				$table->integer('step')->default(0)->unsigned();
				$table->string('title',255)->nullable(); //"Science Quiz"
				$table->string('image',255)->nullable(); //"Science Quiz"
				$table->longText('chapter_text')->nullable(); // parse html, storing as JSON
				$table->string('chapter_voice',255)->nullable(); //"Science Quiz"
				$table->longText('choices')->nullable(); // parse html, storing as JSON
				$table->longText('choice')->nullable(); // parse html, storing as JSON
				$table->string('language')->nullable();
				$table->longText('json_data')->nullable(); // parse html, storing as JSON
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('story_data');
		}
	};
