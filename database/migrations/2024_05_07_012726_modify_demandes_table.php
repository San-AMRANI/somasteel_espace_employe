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
        Schema::table('demandes', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->string('type');
            // $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes');
    }
};