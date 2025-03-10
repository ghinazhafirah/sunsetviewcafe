<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Cart;

class PostController extends Controller
{
    public function index()
    {
        // dd(Cart::latest()->get());
            return view('posts', [
                "title" => "Menu",
                "posts" => Post::latest()->get(), // Pastikan kategori dimuat
                // "image" => "logocafe.png",
                "images" => ["logocafe.png", "ayampenyet.png", "magelangan.jpg"], // Kirim lebih dari satu gambar
                "active" => "posts"
          ]);
    }

    public function show(Post $post) //route model (Binding) Post tadi mengirimkan model dan diikat modelnya yang harus sama dengan variable 
    {
        return view('post', [
            "title" => "Menu",
            "post" => $post, //ga perlu di query
            "active" => "post"
        ]);
    }

    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'slug' => 'required|unique:posts',
        'image' => 'image|file|max:2048', // Pastikan hanya file gambar
        'body' => 'required'
    ]);

    // if ($request->file('image')) {
    //     $validatedData['image'] = $request->file('image')->store('posts'); 
    // }

    // $validatedData['user_id'] = auth()->user()->id;
    // $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 200);

    // Post::create($validatedData);

    // return redirect('/posts')->with('success', 'Post berhasil ditambahkan!');
    }

}
