<?php
 namespace App\Http\ViewComposers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\View\View;

 class ActivityComposer 
 {
    public function compose(View $view)
    {

        $mostCommented=BlogPost::mostCommented()->take(2)->get();
        $mostActive=User::withMostBlogPosts()->take(2)->get();
        $mostActiveLastMonth= User::withMostBlogPostsLastMonth()->take(2)->get();
        $view->with('mostCommented',$mostCommented);
        $view->with('mostActive',$mostActive);
        $view->with('mostActiveLastMonth',$mostActiveLastMonth)   ;
    } 
 }