<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Display the note index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    { 
        // Return the index view for managing notes
        return view('admin/note/index');
    }

    /**
     * List all notes based on the given request parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        // Fetch and return the list of notes for the authenticated user
        return response()->json((new Note())->listAdmin($request->all(), auth()->id()));
    }

    /**
     * Show the form for creating a new note.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return the create note view
        return view('admin/note/create');
    }

    /**
     * Display the details of a specific note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view(Request $request)
    {
        // Find the note by ID from the request
        $model = Note::find($request->input('id'));

        // Redirect to index page if note not found
        if (!$model) {
            return redirect('admin/note/index')->withError('error', 'No data found');
        }

        // Return the view with the note details
        return view('admin/note/view', compact('model'));
    }

    /**
     * Show the form for editing an existing note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        // Find the note by ID from the request
        $model = Note::find($request->input('id'));

        // Redirect to index page if note not found
        if (!$model) {
            return redirect('admin/note')->withError('error', 'No data found');
        }

        // Return the update view with the note data to be updated
        return view('admin/note/update', compact('model'));
    }

    /**
     * Save a new note or update an existing one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        // Validate and store/update the note using the Note model
        return response()->json((new Note())->Adminstore($request->only(['id', 'title', 'note']), auth()->id()));
    }

    /**
     * Delete a specific note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        // Find the note by ID from the request
        $model = Note::find($request->input('id'));

        // Return error response if note not found
        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'Not found']);
        }

        // Delete the note
        $model->delete();

        // Return success response with a message to refresh the table
        return response()->json(['status' => 1, 'message' => 'Note Deleted Successfully.', 'next' => 'table_refresh']);
    }
}
