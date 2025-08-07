<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $quary = Post::with(['user', 'media'])
            ->where('published_at' , '<=' , now())
            ->orWhereNull('published_at')
            ->withCount('claps')
            ->latest();
        if ($user) {
            $ids = $user->following()->pluck('users.id');
            $quary->whereIn('user_id', $ids);
        }

        $posts = $quary->simplepaginate(5);
        return view('post.index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('post.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data  = $request->validated();

        // $image = $data['image'];
        // unset($data['image']);

        $data['user_id'] = Auth::id();

        // $imagePath = $request->file('image')->store('posts', 'public');
        // $data['image'] = $imagePath;

        $post = Post::create($data);

        $post->addMediaFromRequest('image')
            ->toMediaCollection();

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        return view('post.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post , Category $category)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        return view('post.edit', [
            'post' => $post,
            'categories' => $category::get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post) {

        if($post->user_id !== Auth::id()){
        abort(403);
        }

        $data  = $request->validated();

        $data['slug'] = Str::slug($data['title']);

        $post->update($data);

        if($data['image']  ?? false) {
            $post->addMediaFromRequest('image')
                ->toMediaCollection();
            }
        return redirect()->route('posts');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        $post->delete();
        return redirect()->route('dashboard');
    }

    public function category(Category $category)
    {

        $user = Auth::user();

        $quary = $category->posts()
            ->where('published_at' , '<=' , now())
            ->with(['user', 'media'])
            ->withCount('claps')
            ->latest();

            if ($user) {
            $ids = $user->following()->pluck('users.id');
            $quary->whereIn('user_id', $ids);
        }

        $posts = $quary->simplepaginate(5);

        return view('post.index', [
            'posts' => $posts
        ]);
    }

    public function myPosts()
    {

        $user = Auth::user();
        $posts = $user->posts()
            ->with(['user', 'media'])
            ->withCount('claps')
            ->latest()
            ->simplepaginate(5);

        return view('post.index', [
            'posts' => $posts
        ]);
    }
}
