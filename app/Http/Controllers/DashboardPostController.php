<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DashboardPostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('user_id', auth()->user()->id);

        // Filter gabungan berdasarkan favorit & status
        if ($request->has('filter') && $request->filter !== '') {
            switch ($request->filter) {
                case 'fav_available':
                    $query->where('favorite', true)->where('status', 'available');
                    break;
                case 'fav_not_available':
                    $query->where('favorite', true)->where('status', 'not_available');
                    break;
                case 'not_fav_available':
                    $query->where('favorite', false)->where('status', 'available');
                    break;
                case 'not_fav_not_available':
                    $query->where('favorite', false)->where('status', 'not_available');
                    break;
            }
        }

        return view('dashboard.posts.index', [
            'title' => 'Manejemen Menu',
            'image' => 'logocafe.png',
            'posts' => $query->get() //ambilkan data post yang user id = user yg login
            // 'posts' => $query->paginate(15) // Pagination: 10 menu per halaman


        ]);
    }

    public function create() //create untuk nampilin viewnya
    {
        return view('dashboard.posts.create',[
            "image" => "logocafe.png",
            "title" => "All Posts",
            'categories' => Category::all(),

            
    


        ]);
    }

    public function store(Request $request) //store untuk proses datanya
    {

        $validatedData = $request->validate([
            'title' => 'required|max:255|regex:/^[A-Za-z\s]+$/',
            'slug' => 'required|unique:posts',
            'category_id' => 'required',
            'image' => 'image|file|max:1024', //size file foto berapa?
            'body' => 'required',
            'status' => 'required|in:available,not_available', // Validasi status
            'price' => 'required|numeric|min:0', // Validasi harga
            'favorite' => 'nullable|boolean'
        ]);

        if($request->file('image')){ //kalo kosong = null/false, gimanaaa?? gambarnya apa?
            $validatedData['image'] = $request->file('image')->store('post-images');
        }

        $validatedData['user_id'] = auth()->user()->id; //PART 19 (11.10)
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body) );
        $validatedData['favorite'] = $request->has('favorite') ? $request->favorite : 0;

        Post::create($validatedData);

        return redirect('/dashboard/posts')->with('success', 'New Menu has been added!');
    }

    public function show(Post $post) //DETAIL DESKRIPSI TIAP MENU
    {
        return view('dashboard.posts.show', [
            "image" => "logocafe.png",
            "title" => "All Posts",
            'post' => $post

        ]);
    }

    public function edit(Post $post) //edit untuk nampilin viewnya
    {
        return view('dashboard.posts.edit',[
            "image" => "logocafe.png",
            "title" => "All Posts",
            'post' => $post,
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Post $post) //update untuk proses ubah datanya
    {
        $rules = [
            'title' => 'required|max:255|regex:/^[A-Za-z\s]+$/',
            'category_id' => 'required',
            'image' => 'image|file|max:1024', //size file foto berapa?
            'body' => 'required',
            'status' => 'required|in:available,not_available', // Validasi status
            'price' => 'required|numeric|min:0', // Validasi harga
            'favorite' => 'nullable|boolean'
        ];
        
        if($request->slug != $post->slug){
            $rules['slug'] = 'required|unique:posts';
        }

        $validatedData = $request->validate($rules);
        $validatedData['favorite'] = $request->has('favorite') ? $request->favorite : 0;

        if($request->file('image')) { //kalo kosong = null/false, gimanaaa?? gambarnya apa?

            if ($post->image != null) {
                Storage::delete($post->image);
            }
            $validatedData['image'] = $request->file('image')->store('post-images');
        }

        $validatedData['user_id'] = auth()->user()->id; //PART 19 (11.10)
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body));
        // $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 20);

        Post::where('id', $post->id)
            ->update($validatedData);

        return redirect('/dashboard/posts')->with('success', 'New Menu has been updated!');

    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::delete($post->image);
        }
        
        Post::destroy($post->id);

        return redirect('/dashboard/posts')->with('success', 'New Menu has been deleted!');

    }

    //PART 18 (21.07)
    public function checkSlug(Request $request)
    {

        $slug = SlugService::createSlug(Post::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);

    }
}
