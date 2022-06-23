<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $comment ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Commented was posted on your 
        {$this->comment->commentable->title}  blog post" ;
        return $this->attachData(Storage::get($this->comment->user->image->path),[
                      'mime' => 'image/jepg'

        ])
        // third example
        // attachFromStorageDisk('public',$this->comment->user->image->path)
        // second example using disk or public disk
        // attachFromStorage($this->comment->user->image->path,'profile_picture.jepg')
        // first example with full path 
        // attach(storage_path('app/public' ) . '/' .
        // $this->comment->user->image->path,
        // [
        //     'as'=>'profile_picture.jpeg',
        //     'mime' => 'image/jepg'
        // ])
        ->subject($subject)
        ->view('emails.posts.commented');
        
    }
}
