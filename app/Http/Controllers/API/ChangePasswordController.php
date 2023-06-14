<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\UserDTO;
use App\Models\User;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ChangePasswordController extends BaseController{

    public function update(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'currentPassword'=>'required|max:255',
            'user_id'=>'required',
            'password'=>'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
          
        }
        $user = User::find($request->user_id) ;
        
        if (!Hash::check($request->currentPassword, $user->password)) {
            return $this->sendError('Bad current password'," the current password is erroneous", 400 );
        }

        $user->update([
            'password'=>Hash::make($request->password),
        ]);

        return $this->sendResponse('','Successful password change');
    }

}