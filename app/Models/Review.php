<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'review_id';

    protected $fillable = [
        'review_id',
        'review_type',
        'status',
        'observation',
        'user_id',
        'unity_name',
        'subject_name',
        'student_id',
        
    ];

    public function students(){
        return $this->belongsToMany(Student::class, 'reviews');
    }

    public function taughts(){
        return $this->belongsToMany(Taught::class, 'reviews');
    }
}
