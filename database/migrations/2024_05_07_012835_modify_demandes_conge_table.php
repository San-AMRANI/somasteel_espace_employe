<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('demandes_conge', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('demande_id')->constrained()->onDelete('cascade');
            // $table->date('start_date')->nullable();
            // $table->date('end_date')->nullable();
            // $table->text('motif')->nullable();
            // $table->boolean('approuvé_responsable')->default(false);
            // $table->boolean('approuvé_directeur')->default(false);
            // $table->boolean('approuvé_rh')->default(false);
            // $table->text('Autre')->nullable();
            // $table->foreignId('to_responsable_id')->constrained('users')->onDelete('cascade');
            // $table->foreignId('to_directeur_id')->constrained('users')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes_conge');
    }
};