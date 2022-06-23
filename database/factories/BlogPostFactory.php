<?php

namespace Database\Factories;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{

    protected $model =\App\Models\BlogPost::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->sentence(2),
            'content'=> $this->faker->paragraph(2 , true),
            'created_at'=>$this->faker->dateTimeBetween('-3 months'),
        ];
    }
    public function change()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'new title',
                'content'=>'content of the blog post',
            ];
        });
    }
}
