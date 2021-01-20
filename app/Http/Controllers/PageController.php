<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PageController extends Controller
{
    public function posts()
    {
        $posts = Post::get();
        return view('posts',[
                'posts' => $posts->load('user')->first()->paginate()/*
            Cargamos la relacion establecida en el modelo Post, ordenandolos del mas reciente al más antiguo
            y le damos un formato de paginacion*/
        ]);
    }

    public function post(Post $post)
    {
        return view('post', [
            'post' => $post
        ]);
    }
}
