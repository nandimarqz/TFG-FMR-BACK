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
        Schema::create('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('review_id');
            $table->enum('review_type', ['ENTREGA', 'EVALUACION 1', 'EVALUACION 2', 'RECOGIDA']); 
            $table->enum('status', ['BIEN', 'MAL', 'REGULAR', 'NO REVISADO'])->nullable(); 
            $table->string('observation')->nullable();
            $table->string('user_id');
            $table->string('unity_name');
            $table->string('subject_name');
            $table->unsignedBigInteger('student_id');   

            $table->foreign('user_id')
            ->references('user_id')
            ->on('taughts')
            ->onDelete('cascade');

            $table->foreign('unity_name')
            ->references('unity_name')
            ->on('taughts')
            ->onDelete('cascade');

            $table->foreign('subject_name')
            ->references('subject_name')
            ->on('taughts')
            ->onDelete('cascade');

            $table->foreign('student_id')
            ->references('id')
            ->on('students')
            ->onDelete('cascade');

            $table->unique(['student_id', 'review_type','subject_name','unity_name']);  

            $table->primary(['review_id']);

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
