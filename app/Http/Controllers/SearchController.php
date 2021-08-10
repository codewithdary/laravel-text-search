<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function add()
    {
        // this post should be indexed at Algolia right away!
        $post = new Post;
        $post->setAttribute('name', 'Another Post');
        $post->setAttribute('user_id', '1');
        $post->save();
    }

    public function query(Request $request)
    {
        if($request->has('search')){
            $posts = Post::search($request->get($request->search))->get();
        }else{
            $posts = Post::get();
        }

        return view('search.index', [
            'posts' => $posts
        ]);
    }
}
