<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'is_admin',
        'updated_at',
        'created_at',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    

    public function blogPost()
    {
        return $this->hasMany(BlogPost::class,'user_id','id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'user_id','id');
    }
    public function commentsOn()
    {
        return $this->morphMany(Comment::class,'commentable')->latest();
    }
    public function image()
    {
      return $this->morphOne(Image::class,'imageable');
    } 
     public function scopeWithMostBlogPosts(Builder $query)
    {
      return $query->withcount('blogpost')->orderby('blogpost_count','desc');  
    }
    public function scopeWithMostBlogPostsLastMonth(Builder $query)
    {
        return $query->withcount(['blogpost' => function(Builder $query){ 
            $query->whereBetween('created_at',[now()->subMonths(1),now()]);

        }])
        ->having('blogpost_count','>=',1)->orderby('blogpost_count','desc');
        
    }
    public function scopeThatHasCommentedOnPost(Builder $query , BlogPost $post)
    {
              return $query->whereHas('comments', function($query) use($post){
                      return $query->where('commentable_id','=',$post->id)
                      ->where('commentable_type','=', BlogPost::class);
              });
    }
    
}
