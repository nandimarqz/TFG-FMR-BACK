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
        Schema::create('taughts', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('user_id');
            $table->string('unity_name');
            $table->string('subject_name');

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('unity_name')
            ->references('name')
            ->on('unities')
            ->onDelete('cascade');

            $table->foreign('subject_name')
            ->references('name')    
            ->on('subjects')
            ->onDelete('cascade');

            $table->primary(['id','user_id', 'unity_name','subject_name']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taughts');
    }
};
