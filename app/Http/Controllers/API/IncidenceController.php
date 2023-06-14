<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\IncidenceDTO;
use App\Models\Incidence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IncidenceController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incidences = DB::table('incidences')
                        ->join('users', 'incidences.user_id', '=', 'users.id')
                        ->select('incidences.*', 'users.name')
                        ->get();
      


        return $this->sendResponse($incidences, 'Incidences retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'description'=>'required|max:255',
            'user_id'=>'required',
            'type'=>'required|in:TIC,NO TIC'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
        }

        $incidence = new Incidence($data);
        $incidence->status = 'PENDIENTE';
        $incidence->observation = '';
        $incidence->user_id = $data['user_id'];
      
        $incidence = Incidence::create($incidence->toArray());

        return $this->sendResponse(new IncidenceDTO($incidence),'Incidence retrieved successfully' );
    }

    /**
     * Display the specified resource.
     */
    public function getIncidenceById(Incidence $incidence)
    {   
        $user = User::find($incidence->user_id);
        if($user != null){
            $incidence->user_id = $user->name;
        }
        return $this->sendResponse(new IncidenceDTO($incidence),'Incidence retrieved successfully' );
    }

    public function show(Incidence $incidence)
    {
        return $this->sendResponse(new IncidenceDTO($incidence),'Incidence retrieved successfully' );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incidence $incidence)
    {
        $data = $request->all();

        $validator = Validator::make($data,[
            'status'=>'in:PENDIENTE,EN PROCESO,RESUELTA',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
        }

        if($data['observation'] == ''){
            $incidence->observation = ' ';
        }

        $incidence->update($data);
        return $this->sendResponse(new IncidenceDTO($incidence),'Incidence update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incidence $incidence)
    {
        $incidence->delete();
        return $this->sendResponse(new IncidenceDTO($incidence),'Incidence delete successfully');
    }

    public function getUserIncidences(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'user_id'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
        }

        $incidences = DB::table('incidences')
        ->join('users', 'incidences.user_id', '=', 'users.id')
        ->select('incidences.*', 'users.name')
        ->where('user_id', $request->user_id)
        ->get();
        
        return $this->sendResponse($incidences,'Incidences retrieved successfully');
    }

    public function getTICIncidences(Request $request){

        $incidences = DB::table('incidences')
        ->join('users', 'incidences.user_id', '=', 'users.id')
        ->select('incidences.*', 'users.name')
        ->where('type', 'TIC')
        ->get();

        return $this->sendResponse($incidences,'Incidences retrieved successfully');
    }
}
