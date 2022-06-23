<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use SoftDeletes;

    use HasFactory , Taggable;
    
    protected $fillable =['title','content','user_id'];

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable')->latest();
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
   

    public function image()
    {
      return $this->morphOne(Image::class,'imageable');
    }
    public function scopeLatestWithRelations(Builder $query)
    {
      return $query->latest()->withCount('comments')->with('user')
      ->with('tags');
    }
    public function scopeLatest(Builder $query)
    {
     return $query->orderby(static::CREATED_AT,'desc');

    } 
    public function scopeMostCommented(Builder $query)
    { 
      return $query->withCount('comments')->orderby('comments_count','desc');
    }

    public static function boot()
    {
      static::addGlobalScope(new DeletedAdminScope);

        parent::boot();
        // static::addGlobalScope(new LatestScope);
        // static::deleting(function(BlogPost $blogPost){
  
        // });
        // static::updating(function (BlogPost $blogPost){
        //   Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        // });
        // static::restoring(function (BlogPost $blogPost) {
        //   $blogPost->comments()->restore();
        // });

    }
}
