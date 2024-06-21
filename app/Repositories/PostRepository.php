<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{

    public function getAllPaginated()
    {
        return Post::where('state', 'approved')
            ->withCount('likes')
            ->paginate(10);
    }

    public function getAll()
    {
        return Post::where('state', 'pending')
            ->withCount('likes')
            ->paginate(10);
    }

    public function create(array $attributes)
    {
        return Post::create($attributes);
    }

    public function findById($id)
    {
        return Post::withCount('likes')->findOrFail($id);
    }

    public function update(Post $post, array $attributes)
    {
        $post->update($attributes);
        return $post;
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
    }

    public function filterPosts($postCategory)
    {
        $query = Post::query();

        if ($postCategory) {
            $query->where('post_category', '=', $postCategory);
        }

        return $query->withCount('likes')->get();
    }

    public function acceptPost($id)
    {
        $post = Post::findOrFail($id);
        $post->state = 'approved';
        $post->save();
        return $post;
    }

    public function unacceptPost($id)
    {
        $post = Post::findOrFail($id);
        $post->state = 'rejected';
        $post->save();
        return $post;
    }

    public function getUserPosts($user)
    {
        return $user->posts()->paginate(10);
    }
}