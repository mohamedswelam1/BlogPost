<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{     
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('no plog posts yet!');
    }
    public function testSee1BlogPostWhenThereIs1() 
    {
        // Arrange
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Content of the blog post';
        // $post->save();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('second post');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'second post'
        ]);
    }
}
