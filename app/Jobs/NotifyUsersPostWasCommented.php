<?php

namespace App\Jobs;

use App\Mail\CommentPostedOnPostWatched;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyUsersPostWasCommented implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    { 
           $this->comment= $comment;  
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $user= User::thatHasCommentedOnPost($this->comment->commentable)
        ->get()
        ->filter(function(User $user){
            return $user->id !== $this->comment->user_id;

        })->map(function (User $user){
            TrottledMail::dispatch(
                new CommentPostedOnPostWatched($this->comment,$user), $user

            );
        });
    }
}
