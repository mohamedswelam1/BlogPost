<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Facade\Ignition\DumpRecorder\Dump;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth')->only(['create','store','edit','update','destroy']);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::enableQueryLog(); 
       
        // // // $posts = BlogPost::all();   lazy loading
        // // // $posts = BlogPost::with('comments')->get();  eger loding
        // // foreach($posts as $post)
        // {
        //     foreach($post->comments() as $comment)
        //     {
        //         echo $comment->content ;
        //     }
        // }
        //  DD(DB::getQueryLog());
        $mostCommented=Cache::remember('blog-post-commented',now()->addSecond(10) , function () {
           return BlogPost::mostCommented()->take(2)->get();
        });
        $mostActive = Cache::remember('users-most-active',now()->addSecond(10) , function () {
            return User::withMostBlogPosts()->take(2)->get();
         });
        $mostActiveLastMonth = Cache::remember('users-most-active-last-month',now()->addSecond(10) , function () {
            return User::withMostBlogPostsLastMonth()->take(2)->get();
         });
        return view('post.index', ['posts'=>BlogPost::latest()->withcount('comments')->with('user')->with('tags')
        ->get(),
        'mostCommented'=>$mostCommented,
        'mostActive'=>$mostActive,
        'mostActiveLastMonth'=>$mostActiveLastMonth ,
         ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *  
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function store(StorePost $request)
    {
        
        $validateData= $request->validated();
        $validateData['user_id']=auth()->user()->id;
        $blogPost=BlogPost::create($validateData);
        // $validateData= $request->validate([
        //     // 'title'=>'bail|required|max:100|min:5'
        //     // whenever the first rule would fail it won't validate the others 
        //     'title'=>'required|max:100|min:5',
        //     'content'=>'required|min:9'
        // ]);

        // $blogPost = new BlogPost();
        // $blogPost->title = $request->input('title');
        // $blogPost->content = $request->input('content');
        // $blogPost->save();
        // $hasFile = $request->hasFile('thumbnail');
        if($request->hasFile('thumbnail'))
        {
           
            $path=$request->file('thumbnail')->store('thumbnails');
            $blogPost->image()->save(
                Image::make(['path'=> $path])
            );
            // dump($file);
            // dump($file->getClientMimeType());
            // dump($file->getClientOriginalExtension());
        //    dump( $file->store('thumbnails'));
        //    dump(Storage::disk('public')->put('thumbnails',$file));
        // to specify the file name  we use storeAs method
        // $name1=$file->storeAs('thumbnails', $blogPost->id . '.' . $file->guessExtension());
        // dump(Storage::putFileAs('thumbnails',$file ,$blogPost->id . '.' . $file->guessExtension()));
        //  $name2=Storage::disk('local')->putFileAs('thumbnails',$file ,$blogPost->id . '.' . $file->guessExtension());
        //  dump(Storage::url($name1));
        //   dump(Storage::disk('local')->url($name2));
        }
        // die(); 
        $request->session()->flash('status', 'Blog post was created!');
        
        return redirect()->route('posts.show', ['post' => $blogPost->id]); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return view('post.show',[
        //     'post'=>BlogPost::with(['comments' => function($query){
        //         return $query->latest();
        //     }])->findorfail($id)]);
        $blogPost= Cache::tags(['blog-post'])->remember("blog-post-{$id}",60,function() use($id){
          return BlogPost::with('comments','tags','user')->with('comments.user')
          ->findorfail($id);
        });
        $counter =0 ;
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);

        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $diffrence);
        }
        
        $counter = Cache::tags(['blog-post'])->get($counterKey);
        
        return view('post.show',[
            'counter'=> $counter,
            'post'=>$blogPost,
            'mostCommented'=>BlogPost::mostCommented()->take(2)->get(),
            'mostActive'=>User::withMostBlogPosts()->take(2)->get(),
            'mostActiveLastMonth'=> User::withMostBlogPostsLastMonth()->take(2)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        
        $post=BlogPost::findorfail($id);
        // Gate::authorize('posts.update', $post);
        $this->authorize('update', $post);
        // if(!Gate::allows('update',$post))
        // {
        //    abort(403);
        // }
        return view('post.edit' ,compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id )
    {
        
        $post = BlogPost::findOrFail($id);
        // Gate::authorize('posts.update', $post
         $this->authorize('update', $post);
        $validatedData = $request->validated();
        $post->fill($validatedData);
        if($request->hasFile('thumbnail'))
        {
            $path=$request->file('thumbnail')->store('thumbnails');
            if($post->image)
            {
              Storage::delete($post->image->path);
              $post->image->path = $path;
              $post->image->save();
            }
            else
            {   
                $post->image()->save(
                Image::make(['path'=> $path])
                );
            }
        }
        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', compact('post'));
        
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request , $id)
    {
         $post = BlogPost::findOrFail($id);
        // $post->delete();
        Gate::authorize('delete', $post);

        BlogPost::destroy($id);
        $request->session()->flash('status','Blog post was deleted');
        return redirect()->route('posts.index'); 
    }
}
