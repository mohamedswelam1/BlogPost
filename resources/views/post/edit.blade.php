@extends('layout')

@section('content')
    <form method="POST" action="{{ route('posts.update', $post->id) }}" 
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
       
       @include('post._form')
        <button type="submit">update!</button>
    </form>
@endsection