<p>
    <label>Title</label>
    <input type="text" name="title" value="{{ old('title', $post->title ?? null) }}"/>
</p>

<p>
    <label>Content</label>
    <input type="text" name="content" value="{{ old('content' , $post->content ?? null) }}"/>
</p>
  

<div class="form-group">
<label for="">Thumbnail</label>
<input type="file" name="thumbnail" class="form-control-file">
</div>
@errors
@enderrors 