<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
  use  SoftDeletes;

  protected $guarded = [];

  public function replies(){
    return $this->hasMany(Comment::class, 'commentReplyId', 'id');
  }

  public function getAllReplies()
  {
    foreach ($this->replies as $reply) {
      $reply->getAllReplies();
    }
  }
}

