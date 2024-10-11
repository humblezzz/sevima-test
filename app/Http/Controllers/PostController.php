<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Web
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])
                     ->orderBy('created_at', 'desc')
                     ->get();
        return view('home', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        Auth::user()->posts()->create([
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('home')->with('success', 'Post created successfully!');
    }

    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        Storage::disk('public')->delete($post->image_path);
        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully!');
    }

    // API
    public function apiIndex()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])
                     ->orderBy('created_at', 'desc')
                     ->get();
        return response()->json($posts);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        $post = Auth::user()->posts()->create([
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return response()->json($post, 201);
    }

    public function apiShow(Post $post)
    {
        $post->load(['user', 'comments.user', 'likes']);
        return response()->json($post);
    }

    public function apiDestroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($post->image_path);
        $post->delete();

        return response()->json(null, 204);
    }
}