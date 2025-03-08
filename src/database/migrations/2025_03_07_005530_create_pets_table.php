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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_type_id')->constrained('pet_types')->onDelete('cascade');
            $table->foreignId('breed_id')->nullable()->constrained('breeds')->onDelete('set null');
            $table->string('name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->boolean('is_age_estimated')->default(false);
            $table->boolean('is_dangerous')->default(false);
            $table->boolean('is_mix')->default(false); 
            $table->string('custom_breed')->nullable();
            $table->boolean('is_unknown')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
