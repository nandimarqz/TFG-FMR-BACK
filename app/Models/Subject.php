<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
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

    public function unities(){

        return $this->belongsToMany(Unity::class, 'taughts');

    }

    public function users(){

        return $this->belongsToMany(User::class, 'taughts');

    }
}
