<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Cart;

class PostController extends Controller
{
    public function index($table = null)
    {
        $Request = request();
        \Log::info('Nomor meja yang diterima:', ['tableNumber' => $table]);

        // Jika $table ada di URL, simpan ke session
        if ($table) {
            session(['tableNumber' => $table]);
        } else {
            // Jika tidak ada, ambil dari session
            $table = session('tableNumber');
        }

        // Jika tetap tidak ada, redirect dengan pesan error
        if (!$table) {
            return redirect()->route('menu')->with('error', 'Nomor meja tidak ditemukan.');
        }
        
        $posts = Post::with('category')->get(); // Ambil semua menu dengan kategorinya

        // dd(Cart::latest()->get());
        $images = [  // Contoh array gambar (bisa diambil dari database jika ada)
            'image1.jpg',
            'image2.jpg',
            'image3.jpg'
        ];
            return view('posts', [
                "title" => "Menu",
                "posts" => Post::latest()->get(), // Pastikan kategori dimuat
                // "image" => "logocafe.png",
                // "images" => ["logocafe.png", "ayampenyet.png", "magelangan.jpg"], // Kirim lebih dari satu gambar
                "images" => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
                "active" => "posts",
                "tableNumber" => $table
          ]);
    }

    public function show($slug,  Request $request)  
    {
        // Ambil nomor meja dari session jika tidak ada di URL
        $tableNumber = session('tableNumber');

        \Log::info('Table Number received in PostController:', ['tableNumber' => $tableNumber]);

        if (!is_numeric($tableNumber)) {
            \Log::warning('Nomor meja tidak valid:', ['tableNumber' => $tableNumber]);
            return redirect()->route('menu')->with('error', 'Nomor meja tidak valid.');
        }        

        \Log::info('🔍 Slug yang dicari:', ['slug' => $slug]);

        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            Log::warning('🚫 Post tidak ditemukan:', ['slug' => $slug]);
            return abort(404, 'Post tidak ditemukan');
        }    

        return view('post', [
            "title" => "Menu",
            "post" => $post, //ga perlu di query
            "active" => "post",
            "tableNumber" => $tableNumber
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
    Post::create($validatedData);

    return redirect()->route('posts.index')->with('success', 'Post berhasil ditambahkan!');
    }

}
