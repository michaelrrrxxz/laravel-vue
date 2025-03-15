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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('test_type', 55);
            $table->string('question', 1000);
            $table->string('option_a',255);
            $table->string('option_b',255);
            $table->string('option_c',255);
            $table->string('option_d',255);
            $table->string('option_e',255);
            $table->string('option_correct',255);
            $table->string('ctype',255);
            $table->string('isDeleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
