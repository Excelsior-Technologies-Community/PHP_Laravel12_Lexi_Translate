<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $posts = Post::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $posts->where('slug', 'like', "%{$search}%")
                ->orWhereHas('translations', function ($query) use ($search) {

                    $query->where('text', 'like', "%{$search}%");

                });
        }

        $posts = $posts->oldest()->paginate(4);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show crea1te form.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|max:255',
            'title_en' => 'required',
            'description_en' => 'required',
        ]);

        $post = Post::create([
            'slug' => $request->slug
        ]);

        $post->setTranslations([
            'en' => [
                'title' => $request->title_en,
                'description' => $request->description_en,
            ],

            'hi' => [
                'title' => $request->title_hi,
                'description' => $request->description_hi,
            ],

            'gu' => [
                'title' => $request->title_gu,
                'description' => $request->description_gu,
            ]
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post Created Successfully');
    }

    /**
     * Show single post.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show edit form.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update post.
     */
    public function update(Request $request, Post $post)
    {
        $post->update([
            'slug' => $request->slug
        ]);

        if (
            $request->filled('title_en') &&
            $request->filled('description_en')
        ) {

            $post->setTranslations([
                'en' => [
                    'title' => $request->title_en,
                    'description' => $request->description_en,
                ],
                'hi' => [
                    'title' => $request->title_hi ?? '',
                    'description' => $request->description_hi ?? '',
                ],
                'gu' => [
                    'title' => $request->title_gu ?? '',
                    'description' => $request->description_gu ?? '',
                ],
            ]);
        }

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post Updated Successfully');
    }

    /**
     * Delete post.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post Deleted Successfully');
    }
}