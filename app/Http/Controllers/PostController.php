<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use App\Services\LikeService;

class PostController extends Controller
{
    protected $postService;
    protected $likeService;

    public function __construct(PostService $postService, LikeService $likeService)
    {
        $this->postService = $postService;
        $this->likeService = $likeService;
    }

    /*
    |--------------------------------------------------------------------------
    | This Controller Contains all the Posts Management:
    | View All Posts with there Likes- Accept Post- Rejected Post- View One Post- Edit Post
    | Delete post- View My Posts- Add Post.
    | Add Like to post- Remove Like From post
    |--------------------------------------------------------------------------
    */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = $this->postService->getAllPaginated();
            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string|max:5000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = User::find(auth()->id());
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $postData = [
                'user_id' => $user->id,
                'post_category' => 'other',
                'state' => 'pending',
                'text' => $request->input('text'),
            ];

            if ($request->hasFile('image')) {

                $imagePath = $request->file('image')->store('photos', 'public');

                $postData['image'] = $imagePath;
            }

            $post = $this->postService->createPost($postData);

            $postResource = new PostResource($post);
            return response()->json(['data ' => $postResource], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $post = $this->postService->findPostById($id);
            $postResource = new PostResource($post);
            return response()->json(['data ' => $postResource], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'post_category' => 'in:story,activity,other',
                'state' => 'in:pending,approved,rejected',
                'text' => 'nullable|string|max:5000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $post = $this->postService->findPostById($id);

            $postData = [
                'post_category' => $request->input('post_category'),
                'state' => $request->input('state'),
                'text' => $request->input('text'),
            ];

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('photos', 'public');
                $postData['image'] = $imagePath;
            }

            $updatedPost = $this->postService->updatePost($post, $postData);

            $postResource = new PostResource($updatedPost);

            return response()->json(['data ' => $postResource], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->postService->deletePost($id);
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $postCategory = $request->input('post_category');

            $posts = $this->postService->filterPosts($postCategory);

            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function acceptPost($id)
    {
        try {
            $post = $this->postService->acceptPost($id);
            return response()->json(['message' => 'Post accepted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unacceptPost($id)
    {
        try {
            $post = $this->postService->unacceptPost($id);

            return response()->json(['message' => 'Post unaccepted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserPosts(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $posts = $this->postService->getUserPosts($user);

            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addLike(Request $request, $postId)
    {
        try {
            $userId = auth()->id();

            // Check if the user has already liked the post
            if ($this->likeService->checkIfUserLikedPost($userId, $postId)) {
                return response()->json(['message' => 'You have already liked this post'], 400);
            }

            // Add like for the post
            $this->likeService->addLike($userId, $postId);

            return response()->json(['message' => 'Like added successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeLike($postId)
    {
        try {
            $userId = auth()->id();

            // Find the like record for the user and post
            $like = $this->likeService->findUserLike($userId, $postId);

            // If the like record exists, delete it
            if ($like) {
                $this->likeService->deleteLike($like);
                return response()->json(['message' => 'Like removed successfully'], 200);
            } else {
                return response()->json(['message' => 'You have not liked this post'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
