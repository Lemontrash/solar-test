<?php

namespace Tests\Unit;

use Faker\Generator as Faker;
use App\Models\Comment;
use phpDocumentor\Reflection\Utils;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function foo\func;

class CommentCRUDTest extends TestCase
{

  use RefreshDatabase;

  /** @test */
  public function comment_can_be_created()
  {
    $this->withoutExceptionHandling();

    $response = $this->post('api/comment', [
      'title' => 'any title',
      'body' => 'any body',
      'postId' => '1'
    ]);

    $this->assertEquals(1, Comment::all()->count());
    $this->assertEquals('201', $response->getStatusCode());
  }

  /** @test */
  public function comment_title_is_required()
  {
    $this->withExceptionHandling();

    $response = $this->post('api/comment', [
      'title' => '',
      'body' => 'any body',
      'postId' => '1'
    ]);

    $response->assertSessionHasErrors('title');
  }

  /** @test */
  public function comment_body_is_required()
  {
    $this->withExceptionHandling();

    $response = $this->post('api/comment', [
      'title' => 'title',
      'body' => '',
      'postId' => '1'
    ]);

    $response->assertSessionHasErrors('body');
  }

  /** @test */
  public function comment_postId_is_required()
  {
    $this->withExceptionHandling();

    $response = $this->post('api/comment', [
      'title' => 'title',
      'body' => 'adassdasfas',
      'postId' => ''
    ]);

    $response->assertSessionHasErrors('postId');
  }

  /** @test */
  public function comment_postId_should_be_integer()
  {
    $this->withExceptionHandling();

    $responsePostIdString = $this->post('api/comment', [
      'title' => 'title',
      'body' => 'adassdasfas',
      'postId' => 'sdflgkjdflgn'
    ]);

    $responsePostIdBoolean = $this->post('api/comment', [
      'title' => 'title',
      'body' => 'adassdasfas',
      'postId' => true
    ]);

    $responsePostIdFloat = $this->post('api/comment', [
      'title' => 'title',
      'body' => 'adassdasfas',
      'postId' => 6.9
    ]);

    $responsePostIdString->assertSessionHasErrors('postId');
    $responsePostIdBoolean->assertSessionHasErrors('postId');
    $responsePostIdFloat->assertSessionHasErrors('postId');
  }

  public function comment_postId_should_be_a_valid_id()
  {
    //@TODO
  }

  /** @test */
  public function get_all_comments_with_all_replies()
  {
    $this->withoutExceptionHandling();

    $this->seedComments(true);
    $response = $this->get('api/comment');


    $content = json_decode($response->assertOk()->getContent(), true);

    $size = sizeof($content);
    foreach ($content as $contentItem) {
      if (!empty($contentItem['replies'])){
        $size += sizeof($contentItem['replies']);
      }
    }
    $this->assertEquals($size, Comment::all()->count());
  }

  /** @test */
  public function comment_can_be_updated()
  {
    $this->withoutExceptionHandling();

    $initialCommentData = [
      'title' => 'initial title',
      'body' => 'initial body',
      'postId' => 1
    ];

    $changedData = [
      'title' => 'initial title',
      'body' => 'initial body',
      'postId' => 1
    ];

    $commentResponse = $this->post('api/comment', $initialCommentData);
    $this->assertEquals(201, $commentResponse->getStatusCode());

    $id = Comment::first()->id;

    $this->post('api/comment/' . $id, $changedData);

    $this->assertEquals($changedData['title'], Comment::first()->title);
    $this->assertEquals($changedData['body'], Comment::first()->body);
    $this->assertEquals($changedData['postId'], Comment::first()->postId);
  }

  /** @test */
  public function comment_can_be_deleted()
  {
    $this->withoutExceptionHandling();

    $this->seedComments(1, 0);
    $id = Comment::first()->id;
    $response = $this->delete('api/comment/' . $id);

    $response->assertOk();
  }

  /** @test */
  public function comment_cannot_be_deleted_with_a_wrong_id()
  {
    $this->withoutExceptionHandling();

    $this->seedComments(1, 0);
    $id = 34245;
    $response = $this->delete('api/comment/' . $id);

    $this->assertEquals(400,$response->status());
  }

  public function seedComments(int $rootComments = 20, int $replies = 10)
  {
    $comments = factory(Comment::class, random_int(1,$rootComments))->create();

    if ($replies != 0){
      $comments->each(function ($comments, $replies) {
        factory(Comment::class, random_int(0,$replies))->state('reply')->create(['commentReplyId' => $comments->id]);
      });
    }

    return $comments;
  }
}
