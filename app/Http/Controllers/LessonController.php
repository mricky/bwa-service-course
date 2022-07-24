<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Chapter,
    Lesson
};
use Illuminate\Support\Facades\Validator;
class LessonController extends Controller
{
    public function index(Request $request){
        $lesson = Lesson::query();

        $chapter_id = $request->input('chapter_id');

        $lesson->when($chapter_id, function($query) use ($chapter_id){
                $query->where('chapter_id','=',$chapter_id);
        });

        return response()->json([
            'status' => 'success',
            'data' => $lesson->get()
        ]);
    }
  
    public function create(Request $request){
        
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer',
            
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

         if($validator->fails()){
           
            return response()->json([
                'status'=> 'error',
                'message' => $validator->errors()
            ],400);
        }
        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);
        
        if(!$chapter){
            return response()->json([
                'status'=> 'error',
                'data' => 'chapter not found'
            ],404);
        }

        $lesson = Lesson::create($data);

        if(!$lesson){
            return response()->json([
                'status'=> 'error',
                'data' => 'fail insert lesson'
            ],404);
        }
        return response()->json([
            'status'=> 'success',
            'data' => $lesson
        ]);
    }

     public function update(Request $request, $id){

        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()){
           
            return response()->json([
                'status'=> 'error',
                'message' => $validator->errors()
            ],400);
        }
        $chapterId = $request->input('chapter_id');
        $course = Chapter::find($chapterId);

        if(!$course){
            return response()->json([
                'status'=> 'error',
                'message' => 'chapter not found'
            ],404);
        }

    
        $lesson = Lesson::find($id);
        $lesson->fill($data);

         return response()->json([
            'status'=> 'success',
            'data' => $lesson
        ]);
    }

     public function destroy($id){
       $lesson = Lesson::find($id);

        if(!$lesson){
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ]);
        }
        $lesson->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'berhasil delete'
        ]);
    }

    public function show($id){
        $lessons = Lesson::find($id);

        if(!$lessons){
            return response()->json([
                'status' => 'error',
                'message' => 'lessons not found'
            ]);
        }

        return response()->json([
            'status' => 'succes',
            'data' => $lessons->get()
        ]);

    }
}
