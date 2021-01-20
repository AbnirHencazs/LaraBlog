<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //salvar
        $post = Post::create([
            'user_id' => auth()->user()->id //al crear un post debemos configurarle un user id, usaremos el usuario loggeado
        ] + $request->all() );
       
        //image
        if($request->file('file'))
        {
            $post->image = $request->file('file')->store('posts', 'public');
            /**
             * Si recibimos un archivo entonces salvamos éste archivo, pero la configuración es la siguiente:
             * ése archivo debemos salvarlo dentro de nuestro proyecto para generar una ruta, y esa ruta es 
             * la que guardaremos en nuestra db. Nunca guardamos un archivo en la db directamente
             */
            $post->save();
        }

        //retornamos
        return back()->with('status', 'Creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->all());

        //Al actualizar se necesita borrar la imágen anterior y salvar la nueva

        if($request->file('file'))
        {
            Storage::disk('public')->delete($post->image);
            $post->image = $request->file('file')->store('posts', 'public');
            $post->save();
        }

        return back()->with('status', 'Actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //Eliminación del post
        $post->delete();
        //Eliminación de la imágen
        Storage::disk('public')->delete($post->image); //En la DB se guarda la ruta de la imagen dentro de la aplicación
        //regresamos
        return back()->with('status', 'Eliminado con éxito');
    }
}
