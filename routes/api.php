<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Controller\{
//     MentorController
// };
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// mentor ini perlu di grup
Route::post('mentors','App\Http\Controllers\MentorController@create');
Route::put('mentors/{id}','App\Http\Controllers\MentorController@update');
Route::get('mentors','App\Http\Controllers\MentorController@index');
Route::get('mentors/{id}','App\Http\Controllers\MentorController@show');
Route::delete('mentors/{id}','App\Http\Controllers\MentorController@destroy');

// course ini perlu di grup
Route::post('courses','App\Http\Controllers\CourseController@create');
Route::put('courses/{id}','App\Http\Controllers\CourseController@update');
Route::get('courses','App\Http\Controllers\CourseController@index');
Route::delete('courses/{id}','App\Http\Controllers\CourseController@destroy');
Route::get('courses/{id}','App\Http\Controllers\CourseController@show');

// course ini perlu di grup
Route::post('chapters','App\Http\Controllers\ChapterController@create');
Route::put('chapters/{id}','App\Http\Controllers\ChapterController@update');
Route::get('chapters','App\Http\Controllers\ChapterController@index');
Route::get('chapters/{id}','App\Http\Controllers\ChapterController@show');
Route::delete('chapters/{id}','App\Http\Controllers\ChapterController@destroy');

// course ini perlu di grup
Route::post('lessons','App\Http\Controllers\LessonController@create');
Route::put('lessons/{id}','App\Http\Controllers\LessonController@update');
Route::get('lessons','App\Http\Controllers\LessonController@index');
Route::get('lessons/{id}','App\Http\Controllers\LessonController@show');
Route::delete('lessons/{id}','App\Http\Controllers\LessonController@destroy');


// course ini perlu di image grup
Route::post('image-courses','App\Http\Controllers\ImageCourseController@create');
Route::delete('image-courses/{id}','App\Http\Controllers\ImageCourseController@destroy');

// my course ini perlu di grup
Route::post('my-courses','App\Http\Controllers\MyCourseController@create');
Route::get('my-courses','App\Http\Controllers\MyCourseController@index');
Route::post('my-courses/premium','App\Http\Controllers\MyCourseController@createPremiumAccess');

// my review ini perlu di grup
Route::post('reviews','App\Http\Controllers\ReviewController@create');
Route::put('reviews/{id}','App\Http\Controllers\ReviewController@update');
Route::delete('reviews/{id}','App\Http\Controllers\ReviewController@destroy');

