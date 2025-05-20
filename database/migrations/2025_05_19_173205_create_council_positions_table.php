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
        Schema::create('council_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('council_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained();
            $table->foreignId('student_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('council_positions');
    }
};
