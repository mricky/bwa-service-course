<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    ImageCourse,
    Course
};
use Illuminate\Support\Facades\Validator;


class ImageCourseController extends Controller
{
   
    public function index(Request $request){

        $chapter = ImageCourse::query();

        $course_id = $request->input('course_id');

        $chapter->when($course_id, function($query) use ($course_id){
                $query->where('course_id','=',$course_id);
        });

        return response()->json([
            'status' => 'succes',
            'data' => $chapter->get()
        ]);

    }
    
    public function create(Request $request){
        
        $rules = [
            'image' => 'required|url',
            'course_id' => 'required|integer', // exist
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
           
            return response()->json([
                'status'=> 'error',
                'message' => $validator->errors()
            ],400);
        }

        $course = Course::find($request->input('course_id'));
      
        if(!$course){
            return response()->json([
                'status'=> 'error',
                'data' => 'no found course'
            ],404);
        }

        $imageCourse = ImageCourse::create($data);

        return response()->json([
            'status'=> 'success',
            'data' => $imageCourse
        ]);

    }

    public function destroy($id){
        $imageCourse = ImageCourse::find($id);

        if(!$imageCourse){
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ]);
        }
        $imageCourse->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'berhasil delete'
        ]);
    }

}
