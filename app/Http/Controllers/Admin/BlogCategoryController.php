<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Validator;

/**
 * BlogCategoryController handles CRUD operations for Blog Categories.
 * 
 * @package App\Http\Controllers\Admin
 */
class BlogCategoryController extends Controller
{
    /**
     * Show the index page for Blog Categories.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin/blog_category/index');
    }

    /**
     * Get a list of Blog Categories for admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        // Pass the authenticated user's ID along with the request data
        return response()->json((new BlogCategory())->listAdmin($request->all(), auth()->id()));
    }

    /**
     * Show the create page for Blog Category.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/blog_category/create');
    }

    /**
     * Show the update page for a specific Blog Category.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function update(Request $request)
    {
        // Retrieve the Blog Category by ID
        $id = $request->id;
        $blogcat = BlogCategory::find($id);

        // If category not found, redirect or handle error as needed
        if (!$blogcat) {
            return redirect()->route('admin.blog_category.index')->with('error', 'Blog Category not found.');
        }

        return view('admin/blog_category/update', compact('blogcat'));
    }

    /**
     * Store a new Blog Category or update an existing one.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function save(Request $request)
    {
        return response()->json((new BlogCategory())->store($request->only(['id', 'category', 'status']), auth()->id()));
    }

    /**
     * Delete a Blog Category.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        // Validate the incoming request to ensure the ID is provided
        // $validationResult = $this->general->validate($request->all(), [
        //     'id' =>  'required',
        // ]);
        
         $validationResult = Validator::make($request->all(), [
            'id' =>  'required',
        ]);
        // Handle failed validation
           if ($validationResult->fails()) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'], 400);
        }
        // if (!$validationResult['status']) {
        //     return response()->json(['status' => 0, 'message' => 'Something went wrong.'], 400);
        // }

        // Attempt to find the BlogCategory by ID and delete
        $blogcatModel = BlogCategory::find($request->id);

        // If not found, return error
        if (!$blogcatModel) {
            return response()->json(['status' => 0, 'message' => 'Blog Category not found.'], 404);
        }

        // Perform deletion
        $blogcatModel->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Blog Category Deleted Successfully.',
            'next' => 'load',
            'url' => route('admin/blog_category/index')
        ]);
    }
}
