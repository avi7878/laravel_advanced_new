<?php

namespace App\Http\Controllers;

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
        return view('note/index');
    }

    /**
     * List all notes based on the given request parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $notes = (new Note())->list($request->all(), auth()->id());
        return response()->json($notes);
    }


    /**
     * Show the form for creating a new note.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('note/create',['model' => auth()->user()]);
    }

    /**
     * Display the details of a specific note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view(Request $request)
    {
        // Find the note by ID
        $model = Note::find($request->input('id'));

        // Redirect if the note is not found
        if (!$model) {
            return redirect('note')->withError('error', 'No data found');
        }

        // Return the view with the note data
        return view('note/view', compact('model'));
    }

    /**
     * Show the form for editing an existing note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        // Find the note by ID
        $model = Note::find($request->input('id'));

        // Redirect if the note is not found
        if (!$model) {
            return redirect('note')->withError('error', 'No data found');
        }

        // Return the view with the note data to be updated
        return view('note/update', compact('model'));
    }

    /**
     * Save a new note or update an existing one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {  
        $result = (new Note())->store($request->only(['id', 'title', 'note']), auth()->id());
        if ($request->ajax()) {
            return response()->json($result);
        } else {
            if ($result['status'] === 1) {
                 return redirect('note')->with('success', $result['message']);
            } else {
                return redirect()->back()->withErrors($result['message'])->withInput();
            }
        }
    }


    /**
     * Delete a specific note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        // Find the note by ID
        $model = Note::find($request->input('id'));

        // Return error response if the note is not found
        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'Not found']);
        }

        // Delete the note
        $model->delete();

        // Return success response
        return response()->json(['status' => 1, 'message' => 'Note Deleted Successfully.', 'next' => 'table_refresh']);
    }
}
