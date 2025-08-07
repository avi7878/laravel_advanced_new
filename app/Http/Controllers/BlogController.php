<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\BlogCategory;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Display the list of blogs with filters applied.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Fetching the selected blog tag from the request
        $tags = $request->blog_tag;
        // Retrieving all blog categories for the filter
        $categoryList = BlogCategory::all();

        // Fetching all tags from the settings table for the filter
        $allTags = (new Setting())->getOne('blog_tag');

        // Fetching the top 2 most viewed blogs
        $topArticles = Blog::orderBy('view_count', 'desc')->limit(2)->get();

        // Fetching the blog list with the applied filters from the request
        $blogList = (new Blog())->list($request->all());
        // dd($blogList);
        // Returning the view with the blog data and filters
        return view('blog/index', compact('blogList', 'categoryList', 'allTags', 'tags', 'topArticles'));
    }

    /**
     * Display the list of blogs with pagination.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function list(Request $request)
    {
        // Fetching the blog list based on the request filters and pagination
        $blogList = (new Blog())->list($request->all());

        // Returning the view with the blog list
        return view('blog/list', ['blogList' => $blogList]);
    }

    /**
     * Display a single blog post based on the given slug.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view(Request $request, $slug)
    {
        // Fetching the blog post by slug
        $model = Blog::where('slug', $slug)->first();

        // If the blog post is not found, redirect to the blog index with an error message
        if (!$model) {
            return redirect('blog')->withErrors('No data found');
        }

        // Incrementing the view count for the blog post
        $model->increment('view_count');

        // Fetching the related blog category
        $blogCategory = BlogCategory::find($model->blog_category_id);

        // Returning the view with the blog post and category data
        return view('blog/view', compact('model', 'blogCategory'));
    }
}
    