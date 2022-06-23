<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagCount= Tag::all()->count();
        if($tagCount == 0)
        {
            $this->command->info('no tags found ,skipping assigning to blog posts');
            return;
        }
        $howManyMin= (int)$this->command->ask('minimum tags to blog post  ?',1);
        $howManymax= min((int)$this->command->ask('maximum tags to blog post  ?',   2 ),$tagCount);
        BlogPost::all()->each(function (BlogPost $post) use($howManymax,$howManyMin) {
         $take = random_int($howManyMin , $howManymax);
         $tags = Tag::inRandomOrder()->take($take)->get()->pluck('id');
         $post->tags()->sync($tags);
        });
    }
}
