<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$categories = Category::get();
        //dump($Categories);
        //dd($Categories);

        $user = Auth::user();

        //$query = Post::latest();
        $query = Post::with(['user', 'media'])
        ->where('published_at', '<=', now())
        ->withCount('claps')
        ->latest();
        if ($user) {
            $ids = $user->following()->pluck('users.id');
            $query->whereIn('user_id', $ids);
        }

        //$posts = Post::latest()->get();
        //$posts = Post::orderBy("created_at","desc")->paginate(5);
         $posts = $query->simplePaginate(5);
        //return view('dashboard', compact('categories','posts'));

        return view('post.index', [
           //'categories' => $categories,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $post = $request->validated();
        /*$post = $request->validate([
            //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'title' => 'required',
            'content' => 'required',
            'category_id' => ['required', 'exists:categories,id'],
            'published_at'=> ['nullable','datetime'],
        ]);*/

        //$image = $post['image'];
        //unset($post['image']);
        //$post['user_id'] = auth()->id();
        $post['user_id'] = Auth::id();
        //$post['slug'] = Str::slug($post['title']); //THis is removed by we 
                                                    // have a suggable library alredy used

        //$imagePath = $image->store('posts','public');
        //$post['image'] = $imagePath;

        $post = Post::create($post);

        $post->addMediaFromRequest('image')
            ->toMediaCollection();

        return redirect()->route('dashboard');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        return view('post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            abort(403);
        }
        $categories = Category::get();
        return view('post.edit', compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if ($post->user_id != Auth::id()) {
            abort(403);
        }

        $data = $request->validated();

        $post->update($data);

        if ($data['image'] ?? false) {
            $post->addMediaFromRequest('image')
                ->toMediaCollection();
        }

        return redirect()->route('myPosts');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            abort(403);
        }
        $post->delete();
        return redirect()->route('dashboard');
    }

    public function category(Category $category)
    {
        $user = auth()->user();
        $query = $category->posts()
        ->where('published_at', '<=', now())
        ->with(['user', 'media'])
        ->withCount('claps')->latest();
        

        if ($user) {
            $ids = $user->following()->pluck('users.id');
            $query->whereIn('user_id', $ids);
        }

        $posts = $query->simplePaginate(5);

        return view('post.index', [
            'posts'=> $posts,
        ]);
    }

    public function myPosts()
    {
        $user = Auth::user();
        $posts = $user->posts()
        ->with(['user', 'media'])
        ->withCount('claps')->latest()
        ->simplePaginate(5);

        return view('post.index', [
            'posts'=> $posts,
        ]);
    }
}
