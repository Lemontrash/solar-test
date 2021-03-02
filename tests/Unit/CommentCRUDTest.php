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
    $this->withExceptionHandling();

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
    $this->withExceptionHandling();

    $this->seedComments(true);

    $this->get('api/post/' . 1 . 'comment');

    $this->assertEquals(22, Comment::all()->count());
  }


  public function seedComments(bool $withReplies)
  {
    $comments = factory(Comment::class, random_int(0,20))->create();

    $comments->each(function ($comments) {
      factory(Comment::class, random_int(0,5))->state('reply')->create(['commentReplyId' => $comments->id]);
    });
  }
}
