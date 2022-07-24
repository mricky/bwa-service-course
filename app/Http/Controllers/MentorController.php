<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    public function index(){
        $mentor = Mentor::all();
        return response()->json([
            'status' => 'succes',
            'data' => $mentor
        ]);
    }

    public function show($id){
        $mentor = Mentor::find($id);

        if(!$mentor){
            return response()->json([
                'status' => 'error',
                'data' => 'mentor not found'
            ],400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ],200);
    }
    public function create(Request $request){

        $rules = [
            'name' => 'required|string',
            'profile' => 'required|string',
            'profession' => 'required|string',
            'email' => 'required|email'
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ],400);
        }


        $mentor = Mentor::create($data);

        return response()->json(['status' =>'success','data' => $mentor]);

    }

    public function update(Request $request, $id){
        
         $rules = [
            'name' => 'required|string',
            'profile' => 'required|string',
            'profession' => 'required|string',
            'email' => 'required|email'
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ],404);
        }

        $mentor = Mentor::find($id);

        $mentor->fill($data);
        $mentor->save();

        return response()->json(['status' =>'success','data' => $mentor]);
    }

    public function destroy($id){
        $mentor = Mentor::find($id);

          if(!$mentor){
            return response()->json([
                'status' => 'error',
                'message' => 'not found'
            ],404);

            $mentor->delete();
          }
          return response()->json(['status' =>'success','message' => 'delete success']);
    }
}
