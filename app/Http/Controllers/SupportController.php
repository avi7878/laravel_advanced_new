<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\General;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $response = [];
        $user = Auth()->user();
     
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => config('setting.SUPPORT_API_URL') . 'tickets?requester=' . urlencode(Auth::user()->email),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'token: ' . config('setting.SUPPORT_API_TOKEN'),
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = @json_decode($response, true);
           
            $response = @$response['data'];
            if (!empty($response)) {
                foreach ($response as $k => $row) {
                    $response[$k]['created_at'] = $this->DateFormatChange($row['created_at']);
                    $response[$k]['updated_at'] = $this->DateFormatChange($row['updated_at']);
                }
            }
        } catch (Excepton $e) {
        }

        $perPage = 10;
        $page = $request->query('page', 1);
        $total = count($response);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $paginatedData = array_slice($response, $offset, $perPage);

        return view('support/tickets', ['data' => $response, 'data' => $paginatedData, 'currentPage' => $page, 'totalPages' => $totalPages]);
    }

    public function newTickets()
    {
        return view('support/new');
    }

    public function ticketDetail(Request $request)
    {
        $id = $request['id'];
        $user = Auth()->user();
        $response = [];
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => config('setting.SUPPORT_API_URL') . 'tickets/' . $id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => http_build_query(['requester' => ['name' => $user->name, 'email' => $user->email,]]),
                CURLOPT_HTTPHEADER => array(
                    'token: ' . config('setting.SUPPORT_API_TOKEN')
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = @json_decode($response, true);
            $response = @$response['data'];

            // if($response['requester']['email'] != auth()->user()->email){
            //     abort(404);
            // }
            // dd($response);
            if (!empty($response)) {
                $response['created_at'] = $this->DateFormatChange($response['created_at']);
                $response['updated_at'] = $this->DateFormatChange($response['updated_at']);
                $response['requester']['created_at'] = $this->DateFormatChange($response['requester']['created_at']);
                $response['requester']['updated_at'] = $this->DateFormatChange($response['requester']['updated_at']);
                foreach ($response['comments'] as $k => $comment) {
                    $response['comments'][$k]['created_at'] = $this->DateFormatChange($comment['created_at']);
                    $response['comments'][$k]['updated_at'] = $this->DateFormatChange($comment['updated_at']);
                    if (!empty($response['comments'][$k]['user'])) {
                        $response['comments'][$k]['user']['created_at'] = $this->DateFormatChange($comment['user']['created_at']);
                        $response['comments'][$k]['user']['updated_at'] = $this->DateFormatChange($comment['user']['updated_at']);
                    }
                }
            }
        } catch (Excepton $e) {
        }

        return view('support/detail', ['data' => $response]);
    }

    public function create(Request $request)
    {

          $rules = [
             'title' => 'required',
            'body' => 'required',
            'team_id' => 'required',
            ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth()->user();
        $postData = [
            'requester' => [
                'name' => $user->name,
                'email' => $user->email
            ],
            'title' => $request['title'],
            'body' => $request['body'],
            'team_id' => $request['team_id']
        ];
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => config('setting.SUPPORT_API_URL') . 'tickets',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => array(
                    'token: ' . config('setting.SUPPORT_API_TOKEN'),
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = @json_decode($response, true);
            $response = @$response['data'];
            if ($response) {
                return redirect()->route('support')->with('success', 'Ticket added successfully');
            } else {
                return redirect()->route('support')->with('error', 'Failed to add ticket. Please try again.');
            }
        } catch (Excepton $e) {
        }
        return response()->json(['status' => 0, 'message' => 'Something went wrong']);
    }

    public function comment_create(Request $request)
    {
         $rules = [
                'body' => 'required',
                 'id' => 'required'
            ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = Auth()->user();
            
        $postData = [
            'requester' => [
                'name' => $user->name,
                'email' => $user->email
            ],
            'body' => $request['body'],
        ];
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => config('setting.SUPPORT_API_URL') . 'tickets/' . $request['id'] . '/comments',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => array(
                    'token: ' . config('setting.SUPPORT_API_TOKEN'),
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);
            $response = @json_decode($response, true);
            $response = @$response['data']['id'];
            if ($response) {
                return redirect()->route('support')->with('success', 'Comment added successfully');
            } else {
                return redirect()->route('support')->with('error', 'Failed to add comment. Please try again.');
            }
        } catch (Excepton $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function DateFormatChange($date)
    {
        return date('M j Y g:i A', strtotime($date));
    }
}
