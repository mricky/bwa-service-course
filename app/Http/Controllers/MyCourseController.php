<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Course,
    MyCourse
};
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
   
    public function index(Request $request){
        $mycouse = MyCourse::query();

        $userId = $request->query('user_id');
        $courseId = $request->query('course_id');
        $mycouse->when($userId, function($query) use ($userId){
            $query->where('user_id','=',$userId);
        });
        $mycouse->when($courseId, function($query) use ($courseId){
            $query->where('course_id','=',$courseId);
        });
        
        return response()->json([
            'status' => 'success',
            'data' => $mycouse->get()
        ],200);
    }
    public function create(Request $request){

         $rules = [
            'course_id' => 'required|integer', // exist
            'user_id' => 'required|integer', // exist
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
           
            return response()->json([
                'status'=> 'error',
                'message' => $validator->errors()
            ],400);
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        if(!$course){
            return response()->json([
                'status'=> 'error',
                'data' => 'no found course'
            ],404);
        }

        $userId = $request->input('user_id');

        $user = getUser($userId);

        if($user['status'] === 'error'){
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ],$user['http_code']);
        }
        
        $isExistMyCourse = MyCourse::where('course_id','=',$courseId)
                                    ->where('user_id','=',$userId)
                                    ->exists();
        if($isExistMyCourse){
            return response()->json([
                'status' => 'error',
                'message' => 'user already take this course'
            ],409);
        }
        
        if($course->type === 'premium'){
            $order = postOrders([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);
            
            if($course->price === 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Price cannot be 0'
                 ],405);
            }

            if($order['status'] === 'error'){
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ],$order['http_code']);
            }
           
            return response()->json([
                'status' => $order['status'],
                'data' => $order['data'],
            ]);
        } else {
            $myCourse = MyCourse::create($data);

            return response()->json([
                'status' => 'success',
                'data' => $myCourse
            ]);
        }
        $mycouse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $mycouse
        ],200);
    }

    public function createPremiumAccess(Request $request){
        $data = $request->all();
        $myCourse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}
