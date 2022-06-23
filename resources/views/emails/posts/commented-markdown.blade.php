@component('mail::message')
# comment was posted on your blog post

<p>Hi {{ $comment->commentable->user->name }}   /p>

someone  has commented on your blog post

@component('mail::button', ['url' => route('posts.show',  $comment->commentable->id)   ])
View the blog post 
@endcomponent

@component('mail::button', ['url' =>  route('posts.show',  $comment->user->id) ])
visit {{  $comment->user->name  }} profile
@endcomponent

@component('mail::panel')
{{ $comment->user->name }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
