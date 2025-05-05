<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

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

    // qr function

    public function getQrModal(Request $request)
    {
        $id = $request->id;
        $userData = User::find($id);
        $data = (new General())->generateTFAQrcode($userData->user_name);
        $secretKey = $data['secretKey'];
        $qrCode = $data['qrCode'];
        $qrKey = $secretKey;
        return view('common/verify_authenticator_modal', compact('secretKey', 'qrCode', 'qrKey', 'id'));
    }

    public function viewOtpModal(Request $request)
    {
        $secretKey = $request->secretKey;
        $id = $request->id;
        return view('common/verify_otp_modal', compact('secretKey', 'id'));
    }

    public function optVerifyProcess(Request $request)
    {
        $otp = str_replace(',', '', $request->otp);
        $secretKey = $request->secretKey;
        $data = (new General())->verifyTotp($secretKey, $otp);

        if ($data) {
            $userModel = User::find($request->id);
            $userModel->google_auth_key = $secretKey;
            $userModel->google_auth_created = time();
            $backupCodes = [];
            for ($i = 0; $i < 5; $i++) {
                $backupCodes[] = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            }

            $userModel->backup_code = implode(',', $backupCodes);

            $status = $userModel->save();
            return response()->json(['status' => 1, 'message' => 'Verify successfully.', 'next' => 'refresh']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Verify fail.']);
        }
    }
}
