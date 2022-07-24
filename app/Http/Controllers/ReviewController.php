<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Course,
    Review
};
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request){
        $rules = [
            'course_id' => 'required|integer', // exist
            'user_id' => 'required|integer', // exist
            'rating' => 'required|integer',
            'note' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
           
            return response()->json([
                'status'=> 'error',
                'message' => $validator->errors()
            ],400);
        }

        $userId = $request->input('user_id');
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        if(!$course){
            return response()->json([
                'status'=> 'error',
                'data' => 'no found course'
            ],404);
        }

        $user = getUser($userId);
        if($user['status'] === 'error'){
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ],$user['http_code']);
        }
        
        $review = Review::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $review
        ],200);
    }

    public function update(Request $request, $id){
        $rules = [
            'course_id' => 'integer', // exist
            'user_id' => 'integer', // exist
            'rating' => 'integer',
            'note' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
           
            return response()->json([
                'status'=> 'error',
                'message' => $validator->errors()
            ],400);
        }
        $review = Review::find($id);
        $userId = $request->input('user_id');
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        if(!$course){
            return response()->json([
                'status'=> 'error',
                'data' => 'course no found course'
            ],404);
        }

        if(!$review){
            return response()->json([
                'status'=> 'error',
                'data' => 'review no found course'
            ],404);
        }
        $user = getUser($userId);
        if($user['status'] === 'error'){
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ],$user['http_code']);
        }
        $review->fill($data);
        $review->Save();
      

        return response()->json([
            'status' => 'success',
            'message' => 'berhasil update',
            'data' => $review
        ],200);
    }

    public function destroy($id){
        $review = Review::find($id);

        if(!$review){
            return response()->json([
                'status' => 'error',
                'message' => 'not found review',
               
          ],404);  
        }

        $review->delete();
    
        return response()->json([
                'status' => 'success',
                'message' => 'berhasil delete',
               
        ],200);  
        
    }
}
