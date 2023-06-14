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
        $passwd = bcrypt('IHM2223');

        DB::statement("
            INSERT INTO users (id, name, email, email_verified_at, DNI, password, remember_token, created_at, updated_at) VALUES
            ('directivo', 'DIRECTIVO', 'directivo@ieshnosmachado.org', NULL, NULL, '$passwd', NULL, NOW(), NOW()),
            ('coordinadorTIC', 'COORDINADOR TIC', 'coordinador@ieshnosmachado.org', NULL, NULL, '$passwd', NULL, NOW(), NOW())
      ");
      DB::statement("
            INSERT INTO roles (id, name, created_at, updated_at) VALUES
            ('1', 'DIRECTIVO', NOW(), NOW()),
            ('2', 'COORDINADOR TIC', NOW(), NOW()),
            ('3', 'PROFESOR', NOW(), NOW())
      ");

      DB::statement("
        INSERT INTO `user-roles` (id, user_id, role_id, created_at, updated_at) VALUES
        ('1', 'directivo', '1', NOW(), NOW()),
        ('2', 'coordinadorTIC', '2', NOW(), NOW())
      ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
