<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\TaughtsDTO;
use App\Models\Taught;
use App\Models\Unity;
use App\Models\User;

class TaughtController extends BaseController{

    public function GetAll(){
        return $this->sendResponse(TaughtsDTO::collection(Taught::all()),'Taught retrieved successfully');
    }

    public function GetUserTaughts(User $user){
        return $this->sendResponse(TaughtsDTO::collection(Taught::all()->where('user_id', $user->id)),'Taught retrieved successfully');
    }

    public function getUnitySubjects(Unity $unity){

        return $this->sendResponse(TaughtsDTO::collection($unity->subjects()->distinct()->get()),'Unities subjects retrieved successfully');

    }
}