<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserDTO;
use App\Models\Enrolled;
use App\Models\Review;
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
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;

class ExportCSVController extends BaseController{

    public function ExportTaughtCSV(){
        $taughts = Taught::all();
        
        $csvWriter = Writer::createFromPath('./Imparte.csv', 'w+');
        try {
            $csvWriter->insertOne(['Profesor', 'Curso', 'Asignatura']); 
      
            $data = [];

            foreach ($taughts as $taught) {
                array_push($data,[$taught->user_id, $taught->unity_name, $taught->subject_name]);
            }
      
            $csvWriter->insertAll($data); 
      
            return response()->download('./Imparte.csv')->deleteFileAfterSend(true);
        } catch (CannotInsertRecord $e) {
            // Manejar cualquier excepción si ocurre
            return response()->json(['error' => 'No se pudo generar el archivo CSV'], 500);
        }
    }
    public function ExportReviewsCSV(){
        $reviews = Review::all();
        
        $csvWriter = Writer::createFromPath('./Revisiones.csv', 'w+');
        try {
            $csvWriter->insertOne(['Etapa', 'Estado', 'Observacion', 'Profesor', 'Curso', 'Asignatura', 'Alumno']); 
      
            $data = [];

            foreach ($reviews as $review) {
                $student = Student::find($review->student_id);
                array_push($data,[$review->review_type, $review->status, $review->observation, $review->user_id, $review->unity_name,$review->subject_name, $student->name.', '.$student->surnames]);
            }
      
            $csvWriter->insertAll($data); 
      
            return response()->download('./Revisiones.csv')->deleteFileAfterSend(true);
        } catch (CannotInsertRecord $e) {
            // Manejar cualquier excepción si ocurre
            return response()->json(['error' => 'No se pudo generar el archivo CSV'], 500);
        }
    }
    public function ExportEnrolledsCSV(){
        $enrolleds = Enrolled::all();
        
        $csvWriter = Writer::createFromPath('./Matriculado.csv', 'w+');
        try {
            $csvWriter->insertOne(['Alumno', 'Curso']); 
      
            $data = [];

            foreach ($enrolleds as $enrolled) {
                $student = Student::where('id' , $enrolled->student_id)->first();
                array_push($data,[$student->name.', '.$student->surnames, $enrolled->unity_name]);
            }
      
            $csvWriter->insertAll($data); 
      
            return response()->download('./Matriculado.csv')->deleteFileAfterSend(true);
        } catch (CannotInsertRecord $e) {
            // Manejar cualquier excepción si ocurre
            return response()->json(['error' => 'No se pudo generar el archivo CSV'], 500);
        }
    }

}

