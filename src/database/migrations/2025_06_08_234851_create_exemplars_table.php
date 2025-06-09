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
        Schema::create('exemplars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books'); // FK book_id (NOT NULL)
            $table->foreignId('exemplar_state_id')->constrained('exemplar_states'); // FK exemplar_state_id (NOT NULL)
            $table->string('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exemplars');
    }
};