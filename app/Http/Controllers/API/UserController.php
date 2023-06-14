<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\TaughtsDTO;
use App\Http\Resources\UserDTO;
use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Taught;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public function GetUserRoles(User $user){

        return $this->sendResponse(UserDTO::collection($user->roles()->get()), 'User Roles retrieved successfully');
        
    }

    public function getAllUsers(){
        $results = DB::table('users')
        ->leftJoin('user-roles', 'user-roles.user_id', '=', 'users.id')
        ->leftJoin('roles', 'roles.id', '=', 'user-roles.role_id')
        ->select('users.*')
        ->whereNotIn('users.id', function ($query) {
            $query->select('users.id')
                  ->from('users')
                  ->join('user-roles', 'user-roles.user_id', '=', 'users.id')
                  ->join('roles', 'roles.id', '=', 'user-roles.role_id')
                  ->whereIn('roles.name', ['DIRECTIVO', 'COORDINADOR TIC']);
        })
        ->get();
        return $this->sendResponse($results, 'Users retrieved successfully');
    }

    public function getUser(User $user){

        return $this->sendResponse(new UserDTO($user), 'User retrieved successfully');
        
    }

    public function updateUser(Request $request, User $user){

        $data = $request->all();

        $validator = Validator::make($data,[
            'id'=>'required',
            'name'=>'required',
            'email'=>'required',
            'directive'=>'required',
            'restore'=>'required',
            'coordinator'=>'required',
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
        }

        if($data['directive'] == "true"){
            if (!$user->roles->contains(Role::where('name', 'DIRECTIVO')->first())) {
                $user->roles()->attach(Role::where('name', 'DIRECTIVO')->first());
            } 
        }
        if($data['restore'] == "true"){

            $user->update([
                'password'=>Hash::make($user->DNI),
            ]);
            
        }
        if($data['coordinator'] == "true"){
            if (!$user->roles->contains(Role::where('name', 'COORDINADOR TIC')->first())) {
                $user->roles()->attach(Role::where('name', 'COORDINADOR TIC')->first());
            } 
        }
       
        unset($data['directive']);
        unset($data['restore']);
        unset($data['coordinator']);
    

        $user->update($request->all());
        return $this->sendResponse(new UserDTO($user),'User update successfully');

    }

    public function deleteUser(User $user){
        $user->delete();
        return $this->sendResponse(new UserDTO($user),'User delete successfully');
    }

    public function createTeacher(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'taughts'=>'nullable'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $input['DNI'] = $input['password'];
        $input['password'] = bcrypt($input['password']);
       
        $user = User::create($input);

        $teacherRole = Role::where('name', 'PROFESOR') -> first();

        if ($teacherRole) {
            $user->roles()->attach($teacherRole->id);
        }
        
        $unities = array_keys($input['taughts']);

        foreach ($unities as $unity) {
            foreach ($input['taughts'][$unity] as $subject) {
                Taught::create([
                    'user_id' => $user->id,
                    'subject_name' => $subject,
                    'unity_name' => $unity,
                ]);
            }
        }

        return $this->sendResponse(new UserDTO($user),'Teacher create successfully');
    }

    public function GetSubjectsTaught(User $user){
        return $this->sendResponse(TaughtsDTO::collection($user->subjects()->get()),'Subject retrieved successfully');
    }

}