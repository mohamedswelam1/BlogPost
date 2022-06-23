<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() 
    {
        // \App\Models\User::factory(10)->create();
        // User::factory()->has(BlogPost::factory()->count(3))->create();

      //   User::factory(10)->create()->each(function ($user) {
      //      $user->blogpost()->save(BlogPost::factory()->make());
      // });
     
      if($this->command->confirm('Do you want  to refresh  the database ?'))
      {
        $this->command->call('migrate:refresh');
        $this->command->info('Database was refreshed');
      }
      Cache::tags(['blog-post'])->flush();
      $this->call([UsersTableSeeder::class ,
      BlogPostsTableSeeder::class,
      CommentsTableSeeder::class   ,
      TagsTableSeeder::class,
      BlogPostTagTableSeeder::class,
       ]);
      
     


    }
}
