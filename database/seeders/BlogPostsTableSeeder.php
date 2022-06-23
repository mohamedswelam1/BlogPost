<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
          $blogCount= (int) $this->command->ask('how many blog posts would you like ?', 20);
          $users = User::all();
          BlogPost::factory($blogCount)->make()->each( function($post) use($users) {
            $post->user_id = $users->random()->id;
            $post->save();
           } )  ;
    }
}
