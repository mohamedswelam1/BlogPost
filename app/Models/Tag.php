<?php

namespace App\Models;

use App\Http\Resources\comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function blogposts()
    {
        return $this->morphedByMany(BlogPost::class,'taggable')
        ->withTimestamps()->as('tagged');
    }
    public function comments()
    {
        return $this->morphedByMany(comment::class,'taggable')
        ->withTimestamps()->as('tagged');
    }


}
