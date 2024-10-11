<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // Web
    public function toggleLike(Post $post)
    {
        $existing_like = $post->likes()->where('user_id', Auth::id())->first();

        if ($existing_like) {
            $existing_like->delete();
            $message = 'Post unliked successfully!';
        } else {
            $post->likes()->create([
                'user_id' => Auth::id(),
            ]);
            $message = 'Post liked successfully!';
        }

        return back()->with('success', $message);
    }

    // API
    public function apiToggleLike(Post $post)
    {
        $existing_like = $post->likes()->where('user_id', Auth::id())->first();

        if ($existing_like) {
            $existing_like->delete();
            $message = 'Post unliked successfully!';
            $status = 200;
        } else {
            $post->likes()->create([
                'user_id' => Auth::id(),
            ]);
            $message = 'Post liked successfully!';
            $status = 201;
        }

        return response()->json(['message' => $message], $status);
    }
}