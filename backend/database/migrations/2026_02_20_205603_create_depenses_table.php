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
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->integer('amount');
            $table->date('date');
            $table->string('justification')->nullable();
            $table->enum('categorie', ['materiel', 'logiciel', 'infrastuctures et services cloud', 'autre'])->default('autre');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
