<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\General;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Helpers\Pubnub;

class ChatMessageController extends Controller
{
    /**
     * Retrieve chat message history for a specific chat.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $userId = auth()->id();
        $messageModel = new ChatMessage();
        $messageList = $messageModel->list($request->all(), $userId);
        return view('chat/message_list', compact('messageList', 'userId'));
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
    public function send(Request $request)
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
            $message->created_at = $message->created_at->timestamp;
            $message = $messageModel->setData($message, $userId);
            $messageHtml = view('chat/message', compact('message', 'userId'))->render();

            $message->message_class = 'you';
            $youMessageHtml = view('chat/message', compact('message', 'userId'))->render();

            return response()->json(['status' => 1, 'data' => ['message_html' => $messageHtml, 'opponent_message_html' => $youMessageHtml],]);
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
            $path = $general->uploadFile($file, 'chat','date'); // Adjust path if needed

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
