<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Resources\comment as CommentResorce ;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\TrottledMail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function index(BlogPost $post)
    {
      // Dump(is_array($post->comments) );
      // Dump(get_class($post->comments) );

      // die();
      // return new CommentResorce($post->comments->first());
      return CommentResorce::collection($post->comments()->with('user')->get());
      // return $post->comments()->with('user')->get();

    }

    public function store(BlogPost $post,StoreComment $request )
    {
     $comment = $post->comments()->create([
          'content'=>$request->input('content'),
          'user_id'=>$request->user()->id,
      ]);

      // $when= now()->addMinute(1);
      // DD($post->user->email);
      // Mail::to($post->user->email)->send(new CommentPostedMarkdown($comment));
      // Mail::to($post->user->email)->queue(new CommentPostedMarkdown($comment));
      // $request->session()->flash('status','Comment was created');
      TrottledMail::dispatch(new CommentPostedMarkdown($comment), $post->user);
     
      NotifyUsersPostWasCommented::dispatch($comment);
      // Mail::to($post->user->email)->later($when,new CommentPostedMarkdown($comment));
      // $request->session()->flash('status','Comment was created');
      return redirect()->back()
      ->withStatus('Comment was created!');
    }
}
