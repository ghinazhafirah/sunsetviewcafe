<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Cart;
use App\Models\Qr;

class PostController extends Controller
{
    public function index($table = null)
    {
        $Request = request();
        \Log::info('Nomor meja yang diterima:', ['tableNumber' => $table]);
        
        //ambil nomor meja maks dari database
        $maxTableQr = Qr::where('key', 'max_table')->first();
        $maxTable = $maxTableQr ? (int) $maxTableQr->value : 0;


        // Jika $table ada di URL
        if ($table) { //kalo $table dikirim lewat url, pastikan tidak lebih dari max
            if (!is_numeric($table) || $table < 1 || $table > $maxTable) {
                abort(404, 'Nomor meja tidak valid');
            }
            session(['tableNumber' => $table]); //kalo $table ga ada di url, cek session
        } else { //kalo keduanya ga ada -> redirect ke hal. scan qr
            $table = session('tableNumber');
            if (!$table || !is_numeric($table) || $table < 1 || $table > $maxTable) {
                return redirect()->route('qr.form')->with('error', 'Nomor meja tidak valid atau belum diatur.');
            }
        }
            
        $posts = Post::with('category')->get(); // Ambil semua menu dengan kategorinya

            return view('posts', [
                "title" => "Menu",
                "posts" => Post::latest()->get(), // Pastikan kategori dimuat
                "active" => "posts",
                "tableNumber" => $table
          ]);
    }

    public function show($slug,  Request $request)  
    {
        // Ambil nomor meja dari session jika tidak ada di URL
        $tableNumber = session('tableNumber');

        // Ambil selectedCategory dan search dari query string
        $selectedCategory = $request->query('selectedCategory');
        $search = $request->query('search');

        \Log::info('Table Number received in PostController:', ['tableNumber' => $tableNumber]);

        if (!is_numeric($tableNumber)) {
            \Log::warning('Nomor meja tidak valid:', ['tableNumber' => $tableNumber]);
            return redirect()->route('menu')->with('error', 'Nomor meja tidak valid.');
        }        

        \Log::info('🔍 Slug yang dicari:', ['slug' => $slug]);

        $post = Post::where('slug', $slug)->first(); //cari menu berdasarkan slug

        if (!$post) {
            Log::warning('🚫 Post tidak ditemukan:', ['slug' => $slug]);
            return abort(404, 'Post tidak ditemukan');
        }            

        return view('post', [
            "title" => "Menu",
            "post" => $post, //ga perlu di query
            "active" => "post",
            "tableNumber" => $tableNumber,
            "selectedCategory" => $selectedCategory, // **TERUSKAN INI KE VIEW**
            "search" => $search // **TERUSKAN INI KE VIEW**
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

        Post::create($validatedData);
        return redirect()->route('posts.index')->with('success', 'Post berhasil ditambahkan!');
    }
}