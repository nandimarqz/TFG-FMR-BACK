<?php

namespace App\Http\Controllers\API;
use App\Models\Review;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReviewController extends BaseController{

    public function createReviews(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'reviews' => 'required|array',
            'reviews.*.review_type' => 'required|in:ENTREGA,EVALUACION 1,EVALUACION 2,RECOGIDA',
            'reviews.*.status' => 'nullable',
            'reviews.*.observation' => 'nullable',
            'reviews.*.user_id' => 'required',
            'reviews.*.unity_name' => 'required',
            'reviews.*.subject_name' => 'required',
            'reviews.*.student_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
          
        }

        $reviewsCreated = [];

        foreach($data['reviews'] as $review){

            $reviewBD = Review::create($review);

            array_push($reviewsCreated, $reviewBD);
        }

        return $this->sendResponse($reviewsCreated,'Reviews created successfully');
    }

    public function getUserReviews(User $user){

        $results = DB::table('reviews')
                ->select('reviews.review_type', 'reviews.unity_name', 'reviews.subject_name')
                ->where('reviews.user_id', $user->id)
                ->distinct()
                ->get();

        return $this->sendResponse($results,'Reviews retrieved successfully');
    }

    public function getUserReviewsDirective(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, [
            'subject_name' => 'required',
            'review_type' => 'required',
            'unity_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
          
        }

        $results = DB::table('reviews')
        ->join('students', 'reviews.student_id', '=', 'students.id')
        ->select('reviews.*', 'students.name', 'students.surnames') 
        ->where('reviews.review_type', $data['review_type'])
        ->where('reviews.subject_name', $data['subject_name'])
        ->where('reviews.unity_name', $data['unity_name'])
        ->get();

        return $this->sendResponse($results,'Reviews retrieved successfully');
    }

    public function getAllReviews(){

        $results = DB::table('reviews')
                ->join('users', 'reviews.user_id', '=', 'users.id')
                ->select('reviews.review_type', 'reviews.unity_name', 'reviews.subject_name', 'users.name')
                ->distinct()
                ->get();

        return $this->sendResponse($results,'Reviews retrieved successfully');
    }

    public function getReviewsOfASubjectAtAStage(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id'=>'required',
            'subject_name' => 'required',
            'review_type' => 'required',
            'unity_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
          
        }

        $results = DB::table('reviews')
        ->join('students', 'reviews.student_id', '=', 'students.id')
        ->select('reviews.*', 'students.name', 'students.surnames')
        ->where('reviews.user_id', $data['user_id'])
        ->where('reviews.review_type', $data['review_type'])
        ->where('reviews.subject_name', $data['subject_name'])
        ->where('reviews.unity_name', $data['unity_name'])
        ->get();

        return $this->sendResponse($results,'Reviews retrieved successfully');
    }

    public function getStudentReviewsInAStatusAtAStage(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, [
            'status' => 'required',
            'review_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
        }

        
        $results = DB::table('reviews')
        ->join('students', 'reviews.student_id', '=', 'students.id')
        ->join('users', 'reviews.user_id', '=', 'users.id')
        ->select('reviews.unity_name','reviews.subject_name', 'students.name', 'students.surnames' , 'reviews.observation')
        ->selectRaw('users.name AS user_name')
        ->where('reviews.review_type', $data['review_type'])
        ->where('reviews.status', $data['status'])
        ->orderBy('unity_name')
        ->orderBy('subject_name')
        ->orderBy('students.surnames')
        ->get();

        return $this->sendResponse($results,'Reviews retrieved successfully');
    }

    function updateReview(Request $request){
        $data = $request->all();

        $validator = Validator::make($data,[
            'reviews' => 'required|array',
            'reviews.*.review_type' => 'required|in:ENTREGA,EVALUACION 1,EVALUACION 2,RECOGIDA',
            'reviews.*.status' => 'nullable',
            'reviews.*.observation' => 'nullable',
            'reviews.*.user_id' => 'required',
            'reviews.*.unity_name' => 'required',
            'reviews.*.subject_name' => 'required',
            'reviews.*.student_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Fail',$validator->errors(), 400 );
          
        }

        foreach($data['reviews'] as $review){

            $reviewBD = Review::where('review_id',$review['review_id'])->first();

            $reviewBD->status = $review['status'];
            $reviewBD->observation = $review['observation'];
            $reviewBD->review_id = $review['review_id'];
            $reviewBD->save();
        }

        return $this->sendResponse('','Reviews updated successfully');
    }
}