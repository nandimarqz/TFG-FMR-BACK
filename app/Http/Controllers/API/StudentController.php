<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StudentController extends BaseController{

    public function getStudentName(Student $student){
        return $this->sendResponse($student->name.", ".$student->surnames,'Student name retrieved successfully');
    }

    public function createStudent(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, [
            'name'=>'required',
            'surnames' => 'required',
            'unity' => 'required',
            'NIF' => 'required',
            'email'=> 'nullable',
            'parent_email'=>'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
          
        }

        $student =  Student::create([
            'name'=> $data['name'],
            'surnames'=> $data['surnames'],
            'NIF'=> $data['NIF'],
            'email'=> $data['email'],
            'parent_email'=> $data['parent_email'],
        ]);

        $student->unities()->attach($data['unity']);

        return $this->sendResponse($student, 'Alumno creado con exito');
    }
    
}