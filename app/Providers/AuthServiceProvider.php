<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\User;
use App\Policies\BlogPostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\BlogPost'=>'App\Policies\BlogPostPolicy',
        'App\Models\User' =>'App\Policies\UserPolicy',

       
            BlogPost::class => BlogPostPolicy::class,
            User::class => UserPolicy::class,   
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Gate::define('home.secret',function($user){
        // //    return $user->is_admin ;
        // });
        // Gate::define('posts.update',[BlogPostPolicy::class,'update']);
        // Gate::define('posts.delete',[BlogPostPolicy::class,'delete']);
        // Gate::resource('posts',[BlogPostPolicy::class]);

        // Gate::define('update-post',function(User $user,BlogPost $post){
            
        //  });
        //  Gate::define('delete-post',function(User $user,BlogPost $post){
        //     return $user->id == $post->user_id;
        //  });
         Gate::before(function($user , $ability){
          if($user->is_admin && in_array($ability ,['update','delete'])) 
          {
            return true ;
            
          }

         });
    }
}
