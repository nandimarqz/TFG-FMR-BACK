<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserDTO;
use App\Models\Role;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Taught;
use App\Models\Unity;
use App\Models\User;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UploadCSV extends BaseController{

    public function uploadUserCSV(Request $request){

        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);
    
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            
            // Obtener la ruta temporal del archivo
            $path = $file->getRealPath();
    
            // Leer el contenido del archivo CSV
            $fileContent = file_get_contents($path);
            $fileContent = utf8_encode($fileContent);

            // Guardar el contenido convertido en un archivo temporal
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            // Leer el contenido del archivo CSV convertido
            $data = array_map('str_getcsv', file($tempFilePath));

            foreach ($data as $row) {
                if ($row[0] !== "Empleado/a") {

                    $user = User::where('id' , $row[1])->first();

                    if($user === null){

                        $user = new User();
                        $user->name = $row[0];
                        $user->id = $row[1];
                        $user->email = $row[1] . "@ieshnosmachado.org";
                        $user->DNI = $row[2];
                        if (!empty($row[2])) {
                            $user->password = bcrypt($row[2]);
                        } else {
                            // Asignar un valor predeterminado o mostrar un mensaje de error
                            $user->password = 'default_password';
                            // Otra opción: return $this->sendError('CSV cargado con errores', 'La contraseña está vacía para el usuario: ' . $user->name);
                        }
                      $user=User::create([
                            'name'=> $user->name,
                            'id'=> $user->id,
                            'email'=> $user->email,
                            'password'=> $user->password,
                            'DNI'=> $user->DNI
                        ]);

                        $teacherRole = Role::where('name', 'PROFESOR') -> first();

                        if ($teacherRole) {
                            $user->roles()->attach($teacherRole->id);
                        }

                    }
                   
                }
               
            }
        }
        
        return $this->sendResponse(null, 'CSV cargado con éxito');
    }

    public function uploadStudentCSV(Request $request){

        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);
    
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            
            // Obtener la ruta temporal del archivo
            $path = $file->getRealPath();
    
            // Leer el contenido del archivo CSV
            $fileContent = file_get_contents($path);
            $fileContent = utf8_encode($fileContent);

            // Guardar el contenido convertido en un archivo temporal
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            // Leer el contenido del archivo CSV convertido
            $data = array_map('str_getcsv', file($tempFilePath));

            foreach ($data as $row) {
                if ($row[0] !== "Alumno/a") {

                    if(!empty($row[1])){

                        $student = new Student();
                        $nameAndSurname = explode(",",$row[0]);
                        $name = $nameAndSurname[1];
                        $surnames = $nameAndSurname[0];
    
                        $student->NIF = $row[2];
                        $student->surnames = $surnames;
                        $student->name = $name;
                        
                        if(Student::where('NIF', $student->NIF)->first() == null){
                            
                            $student = Student::create([
                                'name'=> $student->name,
                                'surnames'=> $student->surnames,
                                'NIF'=> $student->NIF
                            ]);
    
                            $student->unities()->attach($row[1]);
                        }

                      
                    }

                    }

             
               
            }
        }
        
        return $this->sendResponse(null, 'CSV cargado con éxito');
    }

    public function uploadSubjectUnityCSV(Request $request){

        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);
    
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            
            // Obtener la ruta temporal del archivo
            $path = $file->getRealPath();
    
            // Leer el contenido del archivo CSV
            $fileContent = file_get_contents($path);
            $fileContent = utf8_encode($fileContent);

            // Guardar el contenido convertido en un archivo temporal
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            // Leer el contenido del archivo CSV convertido
            $data = array_map('str_getcsv', file($tempFilePath));
            
            $subjects = [];
            $unities = [];
            $userName = [];

            
            foreach ($data as $row) {
                if ($row[0] !== "Materia") {

                    //Buscamos la asignatura con el nombre si no devuelve nada es null y se crearia la asignatura si no no se hace nada
                    $subject = Subject::where('name','LIKE' , $row[0])->first();
                    if($subject === null){
                        $newSubject = new Subject();
                        $newSubject->name = $row[0];
                        $subject = Subject::create([
                            'name' => $newSubject->name,
                        ]);
                    }
                    $subject = Subject::where('name','LIKE' , $row[0])->first();
                    //Buscamos la unidad con el nombre si no devuelve nada es null y se crearia la asignatura si no no se hace nada
                    $unity = Unity::where('name' , $row[1])->first();
                    if($unity === null){
                        $newUnity = new Subject();
                        $newUnity->name = $row[1];
                        $unity = Unity::create([
                            'name' => $newUnity->name,
                        ]);
                       
                    }
                    $unity = Unity::where('name' , $row[1])->first();

                    //Buscamos el usuario por el nombre si el usuario es distinto a null se crea el registro en la tabla imparte
                    $user = User::where('name' , $row[2])->first();
                    if($user !== null){
                        $taughtBD = Taught::where('user_id',$user->id)
                                            ->where('unity_name',$unity->name)
                                            ->where('subject_name',$subject->name)->first();
                        if($taughtBD === null){
                            $taught = new Taught();

                            $taught->user_id = $user->id;
                            $taught->subject_name = $subject->name;
                            $taught->unity_name = $unity->name;
    
                            Taught::create([
                                'user_id' => $taught->user_id,
                                'subject_name' => $taught->subject_name,
                                'unity_name' => $taught->unity_name,
                            ]);
                        }
                    }
                }               
            }
        }
        
        return $this->sendResponse(null, 'CSV cargado con éxito');
    }
}

