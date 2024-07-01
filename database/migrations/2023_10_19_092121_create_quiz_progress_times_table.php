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
        Schema::create('quiz_progress_times', function (Blueprint $table) {
            $table->id();
	          $table->string('job_id')->nullable();
		        $table->integer('percentage')->nullable();
		        $table->string('description')->nullable();
		        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_progress_times');
    }
};
