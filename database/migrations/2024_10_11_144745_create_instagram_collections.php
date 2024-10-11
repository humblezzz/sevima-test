<?php
// database/migrations/2023_10_11_000000_create_instagram_collections.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateInstagramCollections extends Migration
{
    public function up()
    {
        // Users Collection
        Schema::connection('mongodb')->create('users', function (Blueprint $collection) {
            $collection->index('name');
            $collection->unique('email');
            $collection->index('created_at');
        });

        // Posts Collection
        Schema::connection('mongodb')->create('posts', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->index('created_at');
        });

        // Comments Collection
        Schema::connection('mongodb')->create('comments', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->index('post_id');
            $collection->index('created_at');
        });

        // Likes Collection
        Schema::connection('mongodb')->create('likes', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->index('post_id');
            $collection->unique(['user_id', 'post_id']);
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('users');
        Schema::connection('mongodb')->dropIfExists('posts');
        Schema::connection('mongodb')->dropIfExists('comments');
        Schema::connection('mongodb')->dropIfExists('likes');
    }
}