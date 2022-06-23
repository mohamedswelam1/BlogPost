<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    public function index($tag)
    {
      $tag=Tag::findorfail($tag);
      return view('post.index',[
          'posts'=> $tag->blogposts()->latestWithRelations()->get(),
          'mostCommented'=>[],
          'mostActive'=>[],
          'mostActiveLastMonth'=>[]
      ]);
    }
}
