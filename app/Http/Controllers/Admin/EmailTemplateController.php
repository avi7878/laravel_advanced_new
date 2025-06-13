<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the email templates.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin/email_template/index');
    }

    /**
     * Retrieve a list of email templates for admin.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json((new EmailTemplate)->listAdmin($request->all()));
    }

    /**
     * Show the form for editing an existing email template.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function update(Request $request)
    {
        $model = EmailTemplate::find($request->input('id'));
        // Redirect if not found
        if (!$model) {
            return redirect()->route('admin.email-template.index')->withErrors(['error' => 'No data found']);
        }
        $example = '{{parameter name}}';
        return view('admin/email_template/update', compact('model', 'example'));
    }

    /**
     * Store or update an email template.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json((new EmailTemplate)->store($request->only(['id', 'key', 'title', 'subject', 'body', 'params'])));
    }

    /**
     * Handle file upload for email template.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFile(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'upload' => 'required|' . $this->general->fileRules()
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
        }

        $fileName = $this->general->uploadFile($request->file('upload'), 'email');
        return response()->json(['status' => 1, 'fileName' => $fileName['file_name'], 'url' => $this->general->getFileUrl($fileName['file_name'], 'content')]);
    }

    /**
     * Preview the email template content.
     *
     * @param Request $request
     * @return mixed
     */
    public function view(Request $request)
    {
        $model = EmailTemplate::find($request->input('id'));
        if (!$model) {
            return redirect()->route('admin.email-template.index')->withErrors(['error' => 'No data found']);
        }
        $data = $model->parseTemplate($model);
        return $data['body'];
    }
}
