<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPaginated()
    {
        return $this->postRepository->getAllPaginated();
    }

    public function getAll()
    {
        return $this->postRepository->getAll();
    }

    public function createPost(array $attributes)
    {
        return $this->postRepository->create($attributes);
    }

    public function findPostById($id)
    {
        return $this->postRepository->findById($id);
    }

    public function updatePost($post, array $attributes)
    {
        return $this->postRepository->update($post, $attributes);
    }

    public function deletePost($id)
    {
        $this->postRepository->delete($id);
    }

    public function filterPosts($postCategory)
    {
        return $this->postRepository->filterPosts($postCategory);
    }

    public function acceptPost($id)
    {
        return $this->postRepository->acceptPost($id);
    }

    public function unacceptPost($id)
    {
        return $this->postRepository->unacceptPost($id);
    }

    public function getUserPosts($user)
    {
        return $this->postRepository->getUserPosts($user);
    }
}