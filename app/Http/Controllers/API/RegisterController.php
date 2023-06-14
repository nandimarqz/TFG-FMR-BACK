<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseController
{
     /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        if($input['roles'] != null){

            foreach ($input['roles'] as $role) {
                $user->roles()->attach($role);
            }

        }

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        $success['roles'] = $input['roles'];
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $bdUser = User::find($user->getAuthIdentifier());

            if($bdUser->hasRole($request->role)){

                $success['token'] =  $user->createToken('MyApp')->plainTextToken ;
                $success['user_id'] =  $user->id;
                $success['userRole'] = $request->role;
                return $this->sendResponse($success, 'User login successfully.');

            }else{

                return $this->sendError('erroneous role', ['error'=>'erroneous role'],401);

            }
           
        } 
        else{ 
            return $this->sendError('Unauthorized', ['error'=>'Unauthorized'],401);
        } 
    }
}
