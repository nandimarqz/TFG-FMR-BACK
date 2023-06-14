<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\StudentDTO;
use App\Http\Resources\UnityDTO;
use App\Models\Unity;

class UnityController extends BaseController{

    public function GetUnityStudent(Unity $unity){

        return $this->sendResponse(StudentDTO::collection($unity->students()->get()),'Students retrieved successfully');
    }

    public function GetAllUnities(){

        return $this->sendResponse(UnityDTO::collection(Unity::all()),'Unities retrieved successfully');
    }

}