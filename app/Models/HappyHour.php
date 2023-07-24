<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HappyHour extends Model
{
    use HasFactory;


    public function stadium(){
        return $this->belongsTo(Stadium::class,'stadium_id');
    }
}
