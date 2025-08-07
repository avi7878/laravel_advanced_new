<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;  // Added for logging
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Display the chat index page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $user = User::find($userId);
        $chatId = null;
        $userIdParam = $request->input('user_id');
        $selectedChatUser = null;

        // Helper closure to get unique chats by other user
        $filterUniqueChats = function ($chats, $userId) {
            $unique = collect();
            $seen = [];
            foreach ($chats as $chat) {
                $otherUser = $chat->users->firstWhere('id', '!=', $userId);
                if ($otherUser && !in_array($otherUser->id, $seen)) {
                    $unique->push($chat);
                    $seen[] = $otherUser->id;
                }
            }
            return [$unique, $seen];
        };


        // Load all chats for user
        $chats = Chat::with(['users', 'lastMessage'])
            ->whereHas('users', fn($q) => $q->where('user_id', $userId))
            ->orderByDesc('updated_at')
            ->get();

        // Filter unique chats
        [$chats, $seenUserIds] = $filterUniqueChats($chats, $userId);

        if ($userIdParam) {
            $chat = $chats->first(fn($chat) => $chat->users->pluck('id')->contains($userIdParam) && $chat->users->pluck('id')->contains($userId));
            if (!$chat) {
                $chat = new Chat();
                $chat->type = 0; // private chat
                $chat->status = 1;
                $chat->save();

                $now = time();
                $chat->users()->attach([
                    $userId => ['created_at' => $now, 'updated_at' => $now],
                    $userIdParam => ['created_at' => $now, 'updated_at' => $now],
                ]);

                // Reload chats after creating
                $chats = Chat::with(['users', 'lastMessage'])
                    ->whereHas('users', fn($q) => $q->where('user_id', $userId))
                    ->orderByDesc('updated_at')
                    ->get();

                [$chats, $seenUserIds] = $filterUniqueChats($chats, $userId);
            }

            $chatId = $chat->id;
            $selectedChatUser = $chat->users->firstWhere('id', '!=', $userId);
        } elseif ($chats->isNotEmpty()) {
            $chat = $chats->first();
            $chatId = $chat->id;
            $selectedChatUser = $chat->users->firstWhere('id', '!=', $userId);
        }

        $messages = [];
        foreach ($chats as $chat) {
            $messages[$chat->id] = $chat->messages()
                ->with('user')
                ->where(function ($query) {
                    $query->whereNotNull('message')->where('message', '!=', '')
                        ->orWhereNotNull('data');
                })
                ->orderBy('id')
                ->get();
        }

        // Load messages only for active chat (optional optimization)
        // $messages = $chatId
        //     ? Chat::find($chatId)->messages()->with('user')->whereNotNull('message')->where('message', '!=', '')->orderBy('id')->get()
        //     : collect();

        // Contacts excluding current user and those already chatted with
        $contacts = User::where('id', '!=', $userId)
           // ->where('type', '!=', '0')
            ->whereNotIn('id', $seenUserIds)
            ->get();

        return view('chat.index', compact('chatId', 'user', 'chats', 'contacts', 'messages', 'selectedChatUser'));
    }


    public function sendMessage(Request $request)
    {

        //dd($request->all());

        $request->validate([
            'chat_id' => 'required|exists:chat,id',
            //  'message' => 'required|string|max:2000',
        ]);

        if (!$request->filled('message') && !$request->hasFile('attachment')) {
            return redirect()->back()->withErrors('Please enter a message or attach a file.');
        }

        $userId = auth()->id();
        $chat = Chat::find($request->chat_id);

        // Optional: verify user belongs to chat
        if (!$chat->users->pluck('id')->contains($userId)) {
            return redirect()->back()->withErrors('Unauthorized');
        }

        $fileData = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $size = (string) $file->getSize();
            $targetDir = public_path('upload/attachments/' . date('Y/m'));

            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($targetDir, $fileName);
            $relativePath = 'upload/attachments/' . date('Y/m') . '/' . $fileName;

            $fileData = [
                'file_name' => $relativePath,
                'name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'size' => $size,
            ];
            //$fileData = json_encode($fileMetaData);   
        } else {
            $fileData = null;
        }

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => $userId,
            'message' => $request->message  ?? '',
            'type' => 0,
            'read_user_ids' => $chat->id,
            'reference_id' => null,
            'data' =>  $fileData,
        ]);


        $chat->update([
            'last_message_id' => $message->id,
            'updated_at' => now(),
        ]);

        return redirect()->route('chat', ['id' => $chat->id])
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Get the list of chats for the authenticated user, optionally filtering by search text.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $userId = auth()->id();
        $chatModel = new Chat();
        $messageModel = new ChatMessage();
        $searchText = trim($request->input('search_text', ''));
        $chatList = $chatModel->list($request->all(), $userId);
        return view('chat/list', compact('chatList'));
    }


    /**
     * Retrieve chat details, including opponent information and header HTML.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        $userId = auth()->id();
        $chatId = $request->input('chat_id');

        $chatModel = new Chat();
        $chat = $chatModel->getDetail($chatId, $userId);
        return view('chat/detail', ['chat' => $chat['data'], 'userId' => $userId]);
    }


    /**
     * Displays a list of all users.
     *
     * @return \Illuminate\View\View The view for displaying the list of users.
     */
    public function chat_user_list()
    {
        $users = User::all(); // Get all users from the database
        return view('chat/user_list', compact('users'));
    }

    /**
     * Creates a new chat group.
     *
     * @param Request $request The incoming request containing group data.
     * @return \Illuminate\Http\JsonResponse JSON response with group creation status.
     */
    public function groupCreate(Request $request)
    {
        $userId = auth()->id();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'user_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            $general = new General();
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
        }

        $fileName = null;
        // dd($userId,$request->all());
        if ($request->has('image')) {
            $general = new General();
            // $fileName = $general->uploadCropimg($request->image, 'profile');
            $fileName = $general->uploadFile($request->image, 'profile');
            // dd($fileName);

        }

        $chatModel = new Chat();
        $chatModel->type = 1;
        $chatModel->image = $fileName['file_name'];
        // $chatModel->chat_owner_id = $userId;
        $chatModel->title = $request->title;
        // $chatModel->chat_reference_id = null;
        $chatModel->user_ids = implode(',', $request->user_ids) . ',' . $userId;
        $chatModel->status = 1;
        // $chatModel->created_at = now();
        // $chatModel->updated_at = now();
        $chatModel->save();

        return response()->json([
            'status' => 1,
            'message' => 'Group Created successfully',
            'next' => 'redirect',
            'url' => route('chat/chat', ['id' => $chatModel->id]),
        ]);
    }

    /**
     * Adds users to a chat.
     *
     * @param Request $request The incoming request containing chat ID and user IDs.
     * @return \Illuminate\Http\JsonResponse JSON response with add status.
     */
    public function addUsers(Request $request)
    {
        $chatId = $request->input('chatId');
        $userIds = $request->input('user_ids');

        $chat = Chat::find($chatId);
        if (!$chat) {
            return response()->json(['status' => 0, 'message' => 'Chat not found']);
        }

        $currentChatUserIds = $chat->user_ids ? explode(',', $chat->user_ids) : [];
        $newChatUserIds = array_unique(array_merge($currentChatUserIds, $userIds));
        $chat->user_ids = implode(',', $newChatUserIds);
        $chat->save();

        return response()->json(['status' => 1, 'message' => 'Users added successfully.', 'next' => 'reload']);
    }


    /**
     * Removes a user from a chat.
     *
     * @param Request $request The incoming request containing chat ID and user ID to be removed.
     * @return \Illuminate\Http\JsonResponse JSON response with remove status.
     */
    public function remove(Request $request)
    {
        $chatId = $request->input('chatId');
        $userId = $request->input('user_id');

        $chat = Chat::find($chatId);
        if (!$chat) {
            return response()->json(['status' => 0, 'message' => 'Chat not found']);
        }

        $currentChatUserIds = explode(',', $chat->user_ids);
        $key = array_search($userId, $currentChatUserIds);

        if ($key !== false) {
            unset($currentChatUserIds[$key]); // Remove the user ID from the array
        } else {
            return response()->json(['status' => 0, 'message' => 'User not found in chat']);
        }

        // Update the user_ids in the database
        $chat->user_ids = implode(',', $currentChatUserIds);
        $chat->save();

        return response()->json(['status' => 1, 'message' => 'User removed from chat successfully.', 'next' => 'reload']);
    }

    /**
     * Suggests users who are not part of a given chat.
     *
     * @param Request $request The incoming request containing the chat ID.
     * @return \Illuminate\Http\JsonResponse JSON response with user suggestions.
     */
    public function userSuggestion(Request $request)
    {
        $chatId = $request->input('chatId');
        $chat = Chat::find($chatId);
        if (!$chat) {
            return response()->json(['status' => 0, 'message' => 'Chat not found']);
        }

        $chatUserIds = explode(',', $chat->user_ids);
        $userSuggestions = User::whereNotIn('id', $chatUserIds)->get();

        $formattedUsers = $userSuggestions->map(function ($user) {
            $general = new General();
            $imageUrl = $user->image ? $general->getFileUrl($user->image, 'profile') : null;

            return [
                'id' => $user->id,
                'text' => $user->first_name . ' ' . $user->last_name . ' (' . $user->email . ')',
                'image' => $imageUrl,
            ];
        });

        return response()->json($formattedUsers);
    }


    /**
     * Displays the 'add user' modal for a given chat.
     *
     * @param Request $request The incoming request containing the chat ID.
     * @return \Illuminate\View\View The view for adding a user to the chat.
     */
    public function addUser(Request $request)
    {
        $chatId = $request->input('id');
        $chat = Chat::find($chatId);
        if (!$chat) {
            return response()->json(['status' => 0, 'message' => 'Chat not found']);
        }

        return view('chat/adduser', compact('chat'));
    }


    /**
     * Deletes a chat by its ID.
     *
     * @param Request $request The incoming request containing the chat ID.
     * @return \Illuminate\Http\JsonResponse JSON response with delete status.
     */
    public function destroy(Request $request)
    {
        $chatId = Chat::find($request->input('id'));
        if (!$chatId) {
            return response()->json(['status' => 0, 'message' => 'No data found']);
        }

        $chatId->delete();

        return response()->json(['status' => 1, 'message' => 'Chat deleted successfully.', 'next' => 'reload']);
    }



    /**
     * Displays the group creation modal with user list for group creation.
     *
     * @param Request $request The incoming request.
     * @return \Illuminate\View\View The view for the group creation modal.
     */
    public function groupCreateModal(Request $request)
    {
        $userId = auth()->id();
        $userIds = [];

        $convList = DB::select('SELECT user_ids FROM chat WHERE FIND_IN_SET(?, user_ids)', [$userId]);

        foreach ($convList as $conv) {
            foreach (explode(',', $conv->user_ids) as $uid) {
                if ($uid && $uid != $userId && !in_array($uid, $userIds)) {
                    $userIds[] = $uid;
                }
            }
        }

        $data['userList'] = [];
        if ($userIds) {
            $data['userList'] = DB::table('user')->whereIn('id', $userIds)->select('id', 'first_name', 'last_name', 'email')->get();
        }
        // dd($data);

        return view('chat/group_create', $data);
    }

    /* Message code */
    /**
     * Retrieve chat message history for a specific chat.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function messageList(Request $request)
    {
        $userId = auth()->id();
        $messageModel = new ChatMessage();
        $messageList = $messageModel->list($request->all(), $userId);
        return view('chat/message', compact('messageList', 'userId'));
    }

    /**
     * Update the read status for messages in a chat.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function readStatus(Request $request)
    {
        $chatId = $request->input('chat_id');
        (new Chat())->updateReadStatus($chatId);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    /**
     * Send a new message in a chat.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function messageSend(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $chatId = $request->input('chat_id');
        $textMessage = $request->input('message');
        $type = $request->input('type', 0);
        $data = $request->input('data') ? json_encode($request->input('data')) : '';

        $chatModel = new Chat();
        $chatData = $chatModel->find($chatId);

        if ($chatData) {
            $messageModel = new ChatMessage();
            $messageData = [
                'message' => $textMessage ?: ' ',
                'chat_id' => $chatId,
                'user_id' => $userId,
                'type' => $type,
                'data' => $data,
                'read_user_ids' => $userId
            ];

            $message = $messageModel->create($messageData);
            $chatData->update([
                'last_message_id' => $message->id,
                'updated_at' => now()
            ]);
            $message->image = $user->image;

            $data = [
                'message_id' => $message->id,
                'chat_id' => $message->chat_id,
                'user_id' => $message->user_id,
                'message_text' => $messageModel->messageText($message, $userId),
                'message_html' => view('chat/message', ['message' => $message, 'userId' => $userId])->render(),
                'message_text_opponent' => $messageModel->messageText($message, 0),
                'message_html_opponent' => view('chat/message', ['message' => $message, 'userId' => 0])->render(),
            ];

            return response()->json(['status' => 1, 'data' => $data,]);
        }

        return response()->json(['status' => 0, 'message' => 'No Chat yet']);
    }


    /**
     * Uploads a file for chat and returns its path.
     *
     * @param Request $request The incoming request with the file to upload.
     * @return \Illuminate\Http\JsonResponse JSON response with upload status and path.
     */
    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,png,pdf,gif,jpg,doc,docx,xls,xlsx,zip,wav,mp4,webp|max:10000', // Adjust max size if needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'File validation failed',
                'errors' => $validator->errors(),
            ]);
        }

        if ($request->hasFile('file')) {
            $general = new General();
            $file = $request->file('file');
            $path = $general->uploadFile($file, 'chat', 'date'); // Adjust path if needed

            $fileMimeType = $file->getClientMimeType();

            return response()->json([
                'status' => 1,
                'message' => 'File uploaded successfully',
                'path' => ['file_name' => $path['file_name'], 'name' => $path['name'], 'file_type' => $path['file_type'], 'size' => $path['size']],
                'file_type' => $fileMimeType,
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'File not found in the request',
        ]);
    }




    /**
     * Sends a message to a specific chat using PubNub service.
     *
     * @param array $chatData Chat information for the message.
     * @param array $message The message to be sent.
     * @return string Status of message sending.
     */
    private function pubnubChat(array $chatData, array $message)
    {
        $pubnub = new Pubnub();

        try {
            if (!empty($chatData['user_ids'])) {
                foreach (explode(',', $chatData['user_ids']) as $chatUser) {
                    if ($chatUser != auth()->id()) {
                        $pubnub->sendSocketData($chatUser, $message);
                    }
                }
            }

            return 'Sent';
        } catch (\Exception $e) {
            \Log::error("PubNub send error: " . $e->getMessage());
        }
    }

    /**
     * Tests the PubNub notification with a user message.
     *
     * @param Request $request The incoming request.
     */
    public function test(Request $request)
    {
        $message = [
            "type" => "USER_NOTIFICATION",
            "data" => [
                "user_id" => auth()->id(),
                "header" => auth()->user()->name . " Message",
                'text' => 'Hello Yash',
            ],
        ];

        $pubnub = new Pubnub();
        $pubnub->sendSocketData(auth()->id(), $message);
    }

    /**
     * Retrieves chat user data for the given chat ID.
     *
     * @param Request $request The incoming request.
     * @param int $id The chat ID.
     * @return \Illuminate\View\View The view with chat user data.
     */
    public function userdata(Request $request, int $id)
    {
        $chat = Chat::find($id);
        if (!$chat) {
            abort(404, 'Chat not found');
        }

        $chatModel = new Chat();
        $chatData = $chatModel->getChatData($chat);
        $userIds = explode(',', $chat->user_ids);
        $users = User::whereIn('id', $userIds)->get();

        return view('message/userdata', [
            'chat' => $chat,
            'chatData' => $chatData,
            'users' => $users,
        ]);
    }
}
