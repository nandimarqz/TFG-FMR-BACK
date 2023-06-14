<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE reviews MODIFY COLUMN review_id BIGINT UNSIGNED AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
