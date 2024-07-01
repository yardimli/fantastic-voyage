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
			Schema::table('students', function (Blueprint $table) {
				$table->integer('active')->default(1)->after('profile_picture');
				$table->string('password')->nullable()->after('active');
				$table->date('date_of_birth')->nullable()->after('active');
				$table->dateTime('last_activity')->nullable()->after('date_of_birth');
			});
		}
		
		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::table('students', function (Blueprint $table) {
				$table->dropColumn('active');
				$table->dropColumn('password');
				$table->dropColumn('date_of_birth');
				$table->dropColumn('last_activity');
			});
		}
	};
