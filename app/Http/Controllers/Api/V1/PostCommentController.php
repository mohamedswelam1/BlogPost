<?php

namespace App\Http\Controllers\Api\V1;
    use App\Http\Resources\comment as CommentResorce ;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlogPost $post,Request $request)
    {
        $perPage = $request->input('per_page') ?? 1 ;
        return CommentResorce::collection($post->comments()->with('user')->
        paginate($perPage)->appends([
            'per_page' =>  $perPage
        ]));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPost $post,StoreComment $request)
    {
        // dd('ee');
        $comment= $post->comments()->create([
            'content'=>$request->input('content'),
            'user_id'=>$request->user()->id,
        ]);
        return new CommentResorce($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
