<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Profile;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model =\App\Models\Author::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
    public function configure()
    {

        
        return $this->afterCreating(function (Author $author) { 
        
        });
    }

}
//  $author->profile->save(profile::factory ,) 
