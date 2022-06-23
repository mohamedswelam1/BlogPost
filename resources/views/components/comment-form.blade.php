<div class="mb-2 mt-2">
    @auth
        <form method="POST" action="{{ $route }}">
            @csrf
            {{-- this post variable is avilable here  because this form is
                included on the show templte of thier post and there is the post variables
                there , when you include templte inherit the data that eas avialbele  
                to the parent view  --}}
    
            <div class="form-group">
                <textarea type="text" name="content" class="form-control"></textarea>
            </div>
    
            <button type="submit" class="btn btn-primary btn-block">Add comment</button>
        </form>
    @else
        <a href="{{ route('login') }}">Sign-in</a> to post comments!
    @endauth
    </div>
    <hr/>