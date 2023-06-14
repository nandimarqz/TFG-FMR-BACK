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
        Schema::create('enrolleds', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('student_id');
            $table->string('unity_name');

            $table->foreign('student_id')
            ->references('id')
            ->on('students')
            ->onDelete('cascade');

            $table->foreign('unity_name')
            ->references('name')
            ->on('unities')
            ->onDelete('cascade');

            $table->primary(['id','student_id', 'unity_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrolleds');
    }
};
