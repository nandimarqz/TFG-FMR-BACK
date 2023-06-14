<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'user_id',
        'date',
        'observation',
        'status',
        'type'
    ];

    public function user(){

        return $this->hasOne(User::class);

    }
}
