<div class="container">
@card(['title'=> 'Most Commented'])
@slot('subtitle')
What people are currently talking about
@slot('items')
@foreach ($mostCommented as $post)
<li class="list-group-item">
    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
        {{ $post->title }}
    </a>
</li>
@endforeach
@endslot
@endcard
      
@card(['title'=> 'most active '])
@slot('subtitle')
Writters with most posts 
@endslot
@slot('items', collect($mostActive)->pluck('name'))
@endcard
<div class="col-4">
    @card(['title'=> 'most active last month '])
@slot('subtitle')
users with most posts written in the  month
@endslot
@slot('items', collect($mostActiveLastMonth)->pluck('name'))
@endcard
   
    </div>