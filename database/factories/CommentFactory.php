<?php

namespace Database\Factories;
use Illuminate\Support\Str;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;


class CommentFactory extends Factory
{
    protected $model = \App\Models\Comment::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content'=> $this->faker->text,
            'created_at'=>$this->faker->dateTimeBetween('-3 months'),

        ];
    }
}
