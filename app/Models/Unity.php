<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unity extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'name';
    protected $casts = [
        'name' => 'string',
    ];

    protected $fillable = [
        'name',
    ];

    public function subjects(){

        return $this->belongsToMany(Subject::class, 'taughts');

    }

    public function users(){

        return $this->belongsToMany(User::class, 'taughts');

    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrolleds');
    }
}
