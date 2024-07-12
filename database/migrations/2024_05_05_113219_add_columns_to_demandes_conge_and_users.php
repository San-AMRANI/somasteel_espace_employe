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
        Schema::table('demandes_conge', function (Blueprint $table) {
            // $table->integer('to_respnsable_id');
            // $table->integer('to_directeur_id');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->integer('matricule')->unique();
            $table->string('nom', 100);
            $table->string('prénom', 100);
            $table->string('fonction', 255);
            $table->string('service', 255);
            $table->string('type', 255);
            $table->float('solde_conge')->default(0);
            $table->string('responsable_hiarchique', 255)->nullable();
            
        });
        DB::statement('ALTER TABLE demandes_conge ADD CONSTRAINT fk_to_responsable_id FOREIGN KEY (to_responsable_id) REFERENCES users( id);');
        DB::statement('ALTER TABLE demandes_conge ADD CONSTRAINT fk_to_directeur_id FOREIGN KEY (to_directeur_id) REFERENCES users(id);');

    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::table('demandes_conge_and_users', function (Blueprint $table) {
    //         //
    //     });
    // }
};