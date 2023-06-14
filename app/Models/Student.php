<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'surnames',
        'NIF',
        'email',
        'parent_email',
    ];

    public function unities(){

        return $this->belongsToMany(Unity::class, 'enrolleds','student_id', 'unity_name');

    }

    public function reviews(){
        return $this->belongsToMany(Review::class, 'reviews');
    }
}
