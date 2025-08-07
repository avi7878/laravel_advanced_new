<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
	/**
	 * Show the blog index page.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('admin/blog/index');
	}

	/**
	 * List all blogs for the admin with filtering options.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function list(Request $request)
	{
		return response()->json((new Blog())->listAdmin($request->all(), auth()->id()));
	}

	/**
	 * Show the create blog page with available categories.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		$bcategory = BlogCategory::all();
		$oldImages = [];
		return view('admin/blog/create', compact('bcategory', 'oldImages'));
	}

	/**
	 * Show the update blog page with the existing blog data and categories.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function update(Request $request)
	{
		$id = $request->id;
		$blog = $id ? Blog::find($id) : null;

		$bcategory = BlogCategory::all();
		$oldFiles = [];

		// Check if the gallery exists and has content
		if (!empty($blog) && !empty($blog->gallery)) {
			foreach (explode(',', $blog->gallery) as $fileName) {
				if (!empty($fileName)) { // Additional check to ensure filename is valid
					$oldFiles[] = [
						'id' => $fileName,
						'name' => $fileName,
						'url' => $this->general->getFileUrl($fileName, 'blog'),
						'type' => $this->general->getFileMimeType($fileName, 'blog')
					];
				}
			}
			$oldFiles = json_encode($oldFiles);
		} else {
			// Set empty JSON array to avoid potential null errors in the view
			$oldFiles = json_encode([]);
		}

		return view('admin/blog/update', compact('blog', 'bcategory', 'oldFiles'));
	}

	/**
	 * Store or update the blog data.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function save(Request $request)
	{
		return response()->json((new Blog())->store($request->only(['id', 'title', 'description', 'category', 'image', 'gallery','tags']), auth()->id()));
	}

	/**
	 * Handle the file upload for the editor.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function saveEditorFile(Request $request)
	{
	    $fileValidator = Validator::make($request->all(), [
            'upload' => $this->general->fileRules(),
        ]);

        if ($fileValidator->fails()) {
            return response()->json(['status' => 200, 'code' => 0, 'message' => 'File is not valid']);
        }
		
		if ($request->hasFile('upload')) {
			$fileName = $this->general->upload($request->file('upload'), 'blog');
			return response()->json([
				'fileName' => $fileName,
				'uploaded' => 1,
				'url' => $this->general->getFileUrl($fileName, 'blog')
			]);
		}

		return response()->json(['uploaded' => 0, 'message' => 'No file uploaded']);
	}

	/**
	 * View the details of a specific blog post.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\View
	 */
	public function view(Request $request)
	{
		$model = Blog::find($request->input('id'));

		if (!$model) {
			return response()->json(['status' => 0, 'message' => 'No data found']);
		}

		$blogCategory = BlogCategory::find($model->blog_category_id);
		return view('admin/blog/view', compact('model', 'blogCategory'));
	}

	/**
	 * Delete a specific blog post.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete(Request $request)
	{
		$model = Blog::find($request->input('id'));

		if (!$model) {
			return response()->json(['status' => 0, 'message' => 'No data found']);
		}

		$model->delete();
		return response()->json([
			'status' => 1,
			'message' => 'Blog data deleted successfully.',
			'next' => 'table_refresh'
		]);
	}
}
