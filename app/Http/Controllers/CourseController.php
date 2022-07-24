<?php

namespace App\Http\Controllers;

use App\Models\{
    Chapter,
    Course,
    Mentor,
    MyCourse,
    Review
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request){

        $courses = Course::query();

        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when($q, function($query) use ($q){
            $query->whereRaw("name LIKE '%".strtolower($q)."%'");
        });
        
        $courses->when($status, function($query) use ($status){
            $query->where("status","=",$status);
        });

        return response()->json([
            'status' => 'succes',
            'data' => $courses->paginate(10)
        ]);
    }

    public function show($id){
        $course = Course::with(['chapters.lessons','mentor','images'])->find($id);

        if(!$course){
            return response()->json([
                'status' => 'succes',
                'message' => 'course not found' 
            ],404);  
        }
        
        $reviews = Review::where('course_id','=',$id)->get()->toArray();
   
        if(count($reviews) > 0){
            $userIds = array_column($reviews,'user_id');
            $users = getUserByIds($userIds);
            
            if($users['status'] == 'error'){
                $reviews = [];
            } else {
                foreach($reviews as $key => $review){
                    $userIndex = array_search($review['user_id'],array_column($users['data'],'id'));
                    
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }
       
        $totalStudent = MyCourse::where('course_id',$id)->count();
        $totalVideos = Chapter::where('course_id',$id)->withCount('lessons')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos,'lesson_count'));
        $course['review'] = $reviews;
        $course['total_student'] = $totalStudent;
        $course['total_videos'] = $finalTotalVideos; 
        return response()->json([
            'status' => 'succes',
            'data' => $course

        ]);

    }

    public function create(Request $request){
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'required|string',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'required|integer',
            'level' => 'required|in:all_level,beginner,intermediate,advance',
            'mentor_id' => 'required|integer',
            'description' => 'required|string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ],400);
        }

        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);

        if(!$mentor){
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ]);
        }

        $course = Course::create($data);

        return response()->json([
            'status' => 'success insert',
            'data' => $course
        ],200);
    }

    public function update(Request $request, $id){

         $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'string',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all_level,beginner,intermediate,advance',
            'mentor_id' => 'integer',
            'description' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ],400);
        }
        $course = Course::find($id);

        if(!$course){
            return response()->json([
                'status' => 'error',
                'message' => 'not found course'
            ],404);
        }

        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);

        // ini bisa di validasi di rules menggunakan exist
        if(!$mentor){
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ]);
        }

        $course->fill($data);
        $course->save();
        
        return response()->json([
            'status' => 'success update',
            'data' => $course
        ],200);

    }

    public function destroy($id){

        $courses = Course::find($id);
        if(!$courses){
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ]);                
        }
        $courses->delete();

        return response()->json([
            'status' => 'success delete',
        ],200);
    }
}
