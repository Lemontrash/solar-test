<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Mockery\Exception;

class CommentControllerR extends Controller
{

//TODO


//TODO


  public function index()
  {

    $comments = Comment::whereNull('commentReplyId')->get();
    foreach ($comments as $comment) {
      $comment->getAllReplies();
    }

    return response()->json($comments);
  }

  public function indexByPost($postId)
  {
    $comments = Comment::where('postId', $postId)->whereNull('commentReplyId')->get();
    foreach ($comments as $comment) {
      $comment->getAllReplies();
    }

    return response()->json($comments);
  }

  public function store(CreateCommentRequest $request)
  {
    $parameters = $request->validated();
    $parameters['ip'] = $request->ip();

    $c = Comment::create($parameters);

    return response()->json($c, 201);
  }

  public function show($id)
  {
    $c = Comment::find($id);

    $c->getAllReplies();

    if (empty($c)) {
      return response()->json([], 204);
    } else {
      return response()->json($c, 200);
    }
  }

  public function update(CreateCommentRequest $request, $id)
  {
    $c = Comment::find($id);

    $parameters = $request->validated();
    if (empty($c)) {
      return response()->json([], 406);
    } else {
      $c->fill($parameters);
      $c->save();

      return response()->json($c, 200);
    }
  }

  public function destroy($id)
  {
    if (Comment::where('id', $id)->delete()) {
      Comment::where('commentReplyId', $id)->delete();
      return response()->json([], 200);
    } else {
      return response()->json([], 400);
    }
  }
}
