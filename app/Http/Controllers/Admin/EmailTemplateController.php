<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller{

    public function index()
    {
        return view('admin/email_template/index');
    }

       /**
     * Retrieve a list of email_template for admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return response()->json((new EmailTemplate)->listAdmin($request->all()));
    }

    public function update(Request $request)
    {
        $model = EmailTemplate::find($request->input('id'));
        // Redirect if not found
        if (!$model) {
            return redirect()->route('admin.email-template.index')->withErrors(['error' => 'No data found']);
        }
        return view('admin/email_template/update', compact('model'));
    }
    
    public function save(Request $request)
    {
        return response()->json((new EmailTemplate)->store($request->only(['id','key','title','subject','body','params'])));
    }

    public function saveFile(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'upload' => 'required|'.$this->general->fileRules()
        ]);

        if($validator->fails()){
            return response()->json(['status'=>0, 'message'=> $validator->errors()->first()]);
        }

        $fileName = $this->general->uploadFile($request->file('upload'),'email');
        return response()->json(['status'=>1, 'fileName' => $fileName, 'url'=>$this->general->getFileUrl($fileName['file_name'],'content')]);

    }
    
    public function view(Request $request){
        
        $model = EmailTemplate::find($request->input('id'));
        if (!$model) {
            return redirect()->route('admin.email-template.index')->withErrors(['error' => 'No data found']);
        }
        $data=$model->parseTemplate($model);
        return $data['body'];
    }
    
}

?>