<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\DB;

class EndCourseController extends BaseController{

    public function endCourse(){
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");

        DB::statement("
        TRUNCATE TABLE reviews
        ");
        DB::statement("
        TRUNCATE TABLE taughts
        ");
        DB::statement("
            TRUNCATE TABLE incidences
        ");
        DB::statement("
            TRUNCATE TABLE `user-roles`
        ");
        DB::statement("
            TRUNCATE TABLE enrolleds
        ");
        DB::statement("
            TRUNCATE TABLE users
        ");
        DB::statement("
            TRUNCATE TABLE students
        ");
        DB::statement("
            TRUNCATE TABLE roles
        ");
        DB::statement("
            TRUNCATE TABLE unities
        ");
        DB::statement("
            TRUNCATE TABLE subjects
        ");

        DB::statement("SET FOREIGN_KEY_CHECKS = 1");

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

        return $this->sendResponse('Se ha iniciado el nuevo curso','Reviews retrieved successfully');
    }
    
}