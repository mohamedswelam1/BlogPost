<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\This;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes ;
    use Taggable;

    protected $fillable =['user_id' , 'content'];
    // protected $hidden  =['user_id','deleted_at'];
  
    public function commentable()
    {
        return $this->morphTo();
    }
     public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
   
    public function scopeLatest(Builder $query)
    {
     return $query->orderby(static::CREATED_AT,'desc');

    } 
    public static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new LatestScope);
        // static::creating(function(Comment $comment){
        //     if($comment->commentable_type === BlogPost::class){
        //         Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
        //     }
        // })
      
        
    }
}
