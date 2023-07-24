<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    public function images(){
        return $this->hasMany(StadiumImage::class);
    }

    public function amenities(){
        return $this->hasManyThrough(Amenity::class,StadiumAmenity::class);
    }

    
}
