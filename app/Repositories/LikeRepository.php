<?php

namespace App\Repositories;

use App\Models\Like;

class LikeRepository
{
    public function checkIfUserLikedPost($userId, $postId)
    {
        return Like::where('user_id', $userId)->where('post_id', $postId)->exists();
    }

    public function findUserLike($userId, $postId) {
        return Like::where('user_id', $userId)->where('post_id', $postId)->first();
    }

    public function addLike($userId, $postId)
    {
        $like = new Like();
        $like->user_id = $userId;
        $like->post_id = $postId;
        $like->save();

        return $like;
    }

    public function deleteLike($like)
    {
        $like->delete();
        return true;
    }
}