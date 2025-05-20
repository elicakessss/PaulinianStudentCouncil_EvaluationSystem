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
        Schema::create('councils', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_year');
            $table->enum('status', ['active', 'deactivated'])->default('active');
            $table->foreignId('adviser_id')->constrained('advisers');
            $table->foreignId('department_id')->nullable()->constrained();
            $table->timestamps();

            // University-wide councils have null department_id
            // Ensure academic year uniqueness per council
            $table->unique(['name', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('councils');
    }
};
