<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Mockery\Exception;
use Tests\Unit\CommentCRUDTest;

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

    $comment = Comment::create($parameters);

    return response()->json($comment, 201);
  }

  public function show(Comment $comment)
  {
    $comment->getAllReplies();
    return response()->json($comment, 200);
  }

  public function update(CreateCommentRequest $request, $id)
  {
    $comment = Comment::findOrFail($id);
    $parameters = $request->validated();
    $parameters['ip'] = $request->ip();
    $comment->fill($parameters);
    $comment->save();

    return response()->json($comment, 200);
  }

  public function destroy($id)
  {
    $rows = Comment::where('id', $id)->delete();

    $status = $rows == 1 ? 200:400;
    return response()->json([], $status);
  }
}
