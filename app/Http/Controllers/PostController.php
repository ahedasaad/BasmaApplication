<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
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
            $posts = Post::withCount('likes')->paginate(10);
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

            $user = User::find(auth()->user()->id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $post = new Post();
            $post->fill([
                'user_id' => $user->id,
                'post_category' => 'other',
                'state' => 'pending',
                'text' => $request->input('text'),
            ]);

            if ($request->hasFile('image')) {
                $photoPath = $request->file('image')->store('photos', 'public');
                $post->image = $photoPath;
            }

            $post->save();

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
            $post = Post::withCount('likes')->findOrFail($id);
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

            $post = Post::findOrFail($id);

            if ($request->hasFile('image')) {
                $photoPath = $request->file('image')->store('photos', 'public');
                $post->image = $photoPath;
            }

            $post->update([
                'post_category' => $request->input('post_category'),
                'state' => $request->input('state'),
                'text' => $request->input('text'),
            ]);
            $postResource = new PostResource($post);
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
            $post = Post::findOrFail($id);
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $post_category_id = $request->input('post_category_id');

            $query = Post::query();

            if ($post_category_id) {
                $query->where('post_category_id', '=', $post_category_id);
            }

            $posts = $query->paginate(10);
            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function acceptPost($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->state = 'approved';
            $post->save();
            return response()->json(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unacceptPost($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->state = 'rejected';
            $post->save();
            return response()->json(['message' => 'Done'], 200);
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

            $posts = $user->posts()->paginate(10);

            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addLike(Request $request, $postId)
    {
        try {
            // Check if the user has already liked the post
            $existingLike = Like::where('user_id', auth()->id())->where('post_id', $postId)->first();

            // If the user has already liked the post, return a response indicating that
            if ($existingLike) {
                return response()->json(['message' => 'You have already liked this post'], 400);
            }

            // Create a new like record
            $like = new Like();
            $like->user_id = auth()->id();
            $like->post_id = $postId;
            $like->save();

            return response()->json(['message' => 'Like added successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeLike($postId)
    {
        try {
            // Find the like record for the authenticated user and the specified post
            $like = Like::where('user_id', auth()->id())->where('post_id', $postId)->first();

            // If the like record exists, delete it
            if ($like) {
                $like->delete();
                return response()->json(['message' => 'Like removed successfully'], 200);
            } else {
                return response()->json(['message' => 'You have not liked this post'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
