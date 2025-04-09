<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Email_Template;

class FrontController extends Controller
{
    /**
     * Display the front index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('front.index');
    }

    /**
     * Display a specific page based on the slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function page(Request $request)
    {
        $page = Page::where('slug', $request->slug)->firstOrFail();
        return view('front.page', compact('page'));
    }

    public function email_template(Request $request)
    {
        $email = Email_Template::where('title', $request->title)->firstOrFail();
        return view('front.email_template', compact('email'));
    }

    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('front.contact');
    }

    /**
     * Process the contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactProcess(Request $request)
    {
        return response()->json((new \App\Services\GeneralService())->contactProcess($request->only(['name', 'email', 'subject', 'message'])));
    }
  
}
