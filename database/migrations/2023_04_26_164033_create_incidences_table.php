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
        Schema::create('incidences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('user_id');
            $table->date('date')->default(now());
            $table->string('observation')->nullable(); 
            $table->string('description'); 
            $table->enum('status', ['PENDIENTE','EN PROCESO','RESUELTA']); 
            $table->enum('type', ['TIC', 'NO TIC']); 
            

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidences');
    }
};
