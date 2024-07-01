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
        Schema::create('activity_data', function (Blueprint $table) {
            $table->id();
		        $table->integer('user_id')->unsigned();
		        $table->string('title')->nullable(); //"Science Quiz"
		        $table->string('category')->nullable(); // "Science"
		        $table->string('grade')->nullable(); // "K-4"
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
        Schema::dropIfExists('activity_data');
    }
};
