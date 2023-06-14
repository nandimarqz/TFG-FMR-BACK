<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taught extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'unity_name',
        'subject_name',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'taughts');
    }

    public function unities(){
        return $this->belongsToMany(Unity::class, 'taughts');
    }

    public function subjects(){
        return $this->belongsToMany(Subject::class, 'taughts');
    }

    public function students(){
        return $this->belongsToMany(Student::class, 'reviews');
    }
}
