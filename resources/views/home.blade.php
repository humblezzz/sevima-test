@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="max-w-2xl mx-auto">
        @foreach($posts as $post)
            <div class="bg-white rounded-lg shadow-md mb-8" id="post-{{ $post->id }}">
                <div class="p-4 border-b">
                    <h3 class="font-bold">{{ $post->user->name }}</h3>
                </div>
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image" class="w-full">
                <div class="p-4">
                    <p class="mb-4">{{ $post->content }}</p>
                    <div class="flex items-center mb-4">
                        <button class="like-button text-red-500 mr-2" data-post-id="{{ $post->id }}">
                            <span class="like-count">{{ $post->likes->count() }}</span> Likes
                        </button>
                    </div>
                    <div class="comments mb-4">
                        @foreach($post->comments as $comment)
                            <div class="mb-2">
                                <span class="font-bold">{{ $comment->user->name }}:</span>
                                {{ $comment->content }}
                            </div>
                        @endforeach
                    </div>
                    <form class="comment-form flex" data-post-id="{{ $post->id }}">
                        <input type="text" name="content" placeholder="Add a comment..." required class="flex-grow mr-2 p-2 border rounded">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Post</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection