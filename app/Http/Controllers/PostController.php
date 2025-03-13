<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Cart;

class PostController extends Controller
{
    public function index($tableNumber = null)
    {
        \Log::info('Nomor meja yang diterima:', ['tableNumber' => $tableNumber]);

        // Jika tableNumber tidak ada di URL, coba ambil dari session
        if (!$tableNumber) {
            $tableNumber = session('tableNumber');
        }

        // Jika tetap tidak ada, redirect dengan pesan error
        if (!$tableNumber) {
            return redirect()->route('menu')->with('error', 'Nomor meja tidak ditemukan.');
        }

        // Simpan nomor meja ke session jika tidak null
        session(['tableNumber' => $tableNumber]);
        
        // // Menggunakan tableNumber yang diteruskan melalui session
        // $tableNumber = session('tableNumber');
        // \Log::info('Session Table Number:', ['tableNumber' => session('tableNumber')]);

        // if (!$tableNumber) {
        //     return redirect()->route('menu')->with('error', 'Table number not set!');
        // }

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
                "tableNumber" => $tableNumber
          ]);
    }

    public function show($slug, $tableNumber = null)  
    {
        \Log::info('Table Number received in PostController:', ['tableNumber' => $tableNumber]);

        if (!is_numeric($tableNumber)) {
            \Log::warning('Nomor meja tidak valid:', ['tableNumber' => $tableNumber]);
            $tableNumber = null; // Reset ke null jika tidak valid
        }

         // Simpan nomor meja ke session hanya jika diberikan dalam URL
        if ($tableNumber !== null) {
            session(['tableNumber' => $tableNumber]);
            \Log::info('💾 Nomor meja disimpan ke session:', ['tableNumber' => $tableNumber]);
        } else {
            // Ambil dari session jika tidak ada di URL
            $tableNumber = session('tableNumber', 1);
            \Log::info('🔄 Mengambil nomor meja dari session:', ['tableNumber' => $tableNumber]);
        }

        //  // Simpan nomor meja ke dalam session
        // session(['tableNumber' => $tableNumber]);
        
        // $post = Post::where('slug', $slug)->first();
        \Log::info('🔍 Slug yang dicari:', ['slug' => $slug]);

        $post = Post::where('slug', $slug)->firstOrFail();
        // dd($post);

        \Log::info('🔎 Post Query Result:', ['slug' => $slug, 'post' => $post]);

        // if (!$post) {
        //     return redirect()->route('menu')->with('error', 'Post not found!');
        // }

        // Jika post tidak ditemukan, kembalikan ke menu dengan nomor meja yang sesuai
        // if (!$post) {
        //     return redirect()->route('menu', ['tableNumber' => $tableNumber])->with('error', 'Post not found!');
        // }
        \Log::info('🔍 Post ditemukan sebelum pengecekan:', ['post' => $post]);


        // if (!$post) {
        //     \Log::warning('🚫 Post tidak ditemukan, redirect ke menu.', ['slug' => $slug, 'tableNumber' => $tableNumber]);
        //     return redirect()->route('menu', ['tableNumber' => $tableNumber])->with('error', '❌ Post tidak ditemukan');
        // }
        // var_dump($post);
        // var_dump(!$post);
        // exit;
        

        if (is_null($post)) {
            // \Log::warning('🚫 Pengecekan $post bernilai false:', ['post' => $post]);
            // return redirect()->route('menu', ['tableNumber' => $tableNumber])->with('error', '❌ Post tidak ditemukan');
            return abort(404, 'Post tidak ditemukan');
        }      

        // \Log::info('✅ Debugging Post:', ['post' => $post]);

        return view('post', [
            "title" => "Menu",
            "post" => $post, //ga perlu di query
            "active" => "post",
            "tableNumber" => $tableNumber
        ]);
        // return view('posts.show', compact('post'));
        \Log::info('Post ditemukan, menampilkan halaman.', ['slug' => $slug, 'tableNumber' => $tableNumber]);
        // return view('post', compact('post', 'tableNumber'));
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
