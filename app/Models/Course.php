<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'name','certificate','thumbnail','type',
        'status','price','level','mentor_id','description'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];
    
    public function mentor(){
        return $this->belongsTo('App\Models\Mentor'); // jika sudah di define pk nya di table, tak perlu tambahain pk id nya di model
   
    }
    
    public function chapters(){
        return $this->hasMany('App\Models\Chapter')->orderBy('id','ASC');
    }

    public function images(){
        
        return $this->hasMany('App\Models\ImageCourse')->orderBy('id','ASC');
    }
}
