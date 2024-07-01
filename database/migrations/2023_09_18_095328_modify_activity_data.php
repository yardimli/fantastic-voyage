<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
	    Schema::table('activity_data', function (Blueprint $table) {
		    $table->dropColumn(['title']);
		    $table->integer('activity_id')->nullable()->after('user_id');
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
