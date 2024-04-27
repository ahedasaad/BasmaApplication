<?php

namespace App\Services;

use App\Repositories\LikeRepository;

class LikeService
{
    protected $likeRepository;

    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function checkIfUserLikedPost($userId, $postId)
    {
        return $this->likeRepository->checkIfUserLikedPost($userId, $postId);
    }

    public function findUserLike($userId, $postId)
    {
        return $this->likeRepository->findUserLike($userId, $postId);
    }

    public function addLike($userId, $postId)
    {
        if ($this->checkIfUserLikedPost($userId, $postId)) {
            throw new \Exception('User already liked this post.');
        }

        return $this->likeRepository->addLike($userId, $postId);
    }

    public function deleteLike($like)
    {
        return $this->likeRepository->deleteLike($like);
    }
}