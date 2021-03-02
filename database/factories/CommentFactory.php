<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\Comment::class, function (Faker $faker) {
  return [
    'title' => $faker->title,
    'body' => $faker->text,
    'postId' => 1,
    'ip' => $faker->ipv4
  ];
});

$factory->state(\App\Models\Comment::class, 'reply', function (Faker $faker) {
  return [
    'commentReplyId' => factory(\App\Models\Comment::class)
  ];
});
