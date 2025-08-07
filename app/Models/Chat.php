<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\General;
use App\Helpers\Pagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class Chat
 * 
 * This model represents a chat instance in the application.
 * It is responsible for managing chat operations like creating a chat, fetching chats, and updating read statuses.
 * 
 * @package App\Models
 */
class Chat extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'chat_owner_id',
        'user_ids',
        'title',
        'image',
        'chat_reference_id',
        'last_message_id',
        'status',
        'created_at',
        'updated_at'
    ];

    

    /**
     * Get a list of chats for a user, optionally filtered by search text.
     *
     * @param int $id The user ID.
     * @param string $searchText The text to search for in chat messages.
     * 
     * @return Collection
     */
    public function list($postData, int $userId)
    {
        $query = DB::table($this->table)
            ->select([
                'chat.id as chat_id',
                'chat.user_ids',
                'chat.title',
                'chat.image',
                'chat.type as chat_type',
                'user.id as opponent_id',
                'user.first_name',
                'user.last_name',
                'user.image as opponent_image',
                'user.is_online',
                'chat_message.user_id',
                'chat_message.message',
                'chat_message.type',
                'chat_message.created_at'
            ])->leftJoin('chat_message', function ($join) {
                $join->on('chat.last_message_id', '=', 'chat_message.id');
            })
            ->leftJoin('user', 'user.id', '=', DB::raw("REPLACE(REPLACE(chat.user_ids,',',''),'" . $userId . "','') and chat.type=0"))
            ->whereRaw("FIND_IN_SET(?, chat.user_ids)", [$userId])
            ->orderBy('chat.updated_at', 'desc')
            ->orderBy('chat_message.created_at', 'desc');
        $searchText = isset($postData['search']) ? $postData['search'] : '';
        if ($searchText) {
            $query->where(function ($query) use ($searchText) {
                $query->where('chat.title', 'like', '%' . $searchText . '%')
                    ->orWhere('user.first_name', 'like', '%' . $searchText . '%')
                    ->orWhere('user.last_name', 'like', '%' . $searchText . '%');
            });
        }
        $result = (new Pagination())->getData($query, $postData);
        $chatMessageModel = new ChatMessage();
        $general = new General();
        foreach ($result['data'] as $key => $row) {
            $unreadCount = $chatMessageModel->getChatUnreadCount($row->chat_id, $userId);
            $result['data'][$key]->unread_count = $unreadCount ?: '';

            $result['data'][$key]->title = $row->title ?: $row->first_name . ' ' . $row->last_name;
            $result['data'][$key]->image = $general->getFileUrl($row->opponent_image, 'profile') ?: $general->getFileUrl($row->image, 'chat');

            $result['data'][$key]->chat_type_html = $row->chat_type==0 ? '' : '(Group)';
            $result['data'][$key]->message_text = $row->message ? $chatMessageModel->messageText($row, $userId) : '';
            $result['data'][$key]->created_at = $row->created_at ? date('h:i A', strtotime($row->created_at)) : '';
        }
        return $result;
    }

    /**
     * Get the chat between two users, filtered by reference ID.
     *
     * @param int $from The ID of the first user.
     * @param int $to The ID of the second user.
     * @param int|null $reference_id The reference ID of the chat.
     * 
     * @return Chat|null
     */
    public function getChat(int $from, int $to): ?Chat
    {
        return Chat::whereRaw("FIND_IN_SET(?, user_ids) > 0 and FIND_IN_SET(?, user_ids) > 0", [$from, $to])
            ->where('type', 0)
            ->first();
    }

    /**
     * Get an existing chat or create a new one if not found.
     *
     * @param int $from_id The ID of the first user.
     * @param int $to_id The ID of the second user.
     * 
     * @return Chat
     */
    public function getChatOrCreate(int $from_id, int $to_id): array
    {
        $chat = $this->getChat($from_id, $to_id);
        if ($chat) {
            return ['status' => 1, 'chat_id' => $chat->id];
        }
        $user = User::find($to_id);
        if (!$user) {
            return ['status' => 0, 'message' => 'Invalid request!'];
        }

        if ($from_id == $to_id) {
            return ['status' => 0, 'message' => 'Please try a different user'];
        }
        $chatId = Chat::create([
            'user_ids' => implode(',', [$from_id, $to_id]),
        ]);
        return ['status' => 1,'chat_id' => $chatId->id];
    }

    /**
     * Get chat data (name, image, and info) based on the chat type.
     *
     * @param object $row The chat record.
     * 
     * @return mixed
     */
    public function getDetail($chatId,$userId)
    {
        $chatData = Chat::find($chatId);
        if (!$chatData) {
            return ['status' => 0, 'message' => 'No data'];
        }
        $general = new General();
        if ($chatData->type == 0) {
            // Private chat
            $user = User::find($this->getOpponantId($userId, $chatData->user_ids));
            if (!$user) {
                return ['status' => 0, 'message' => 'No data'];
            }
            $chatData->title=trim($user->first_name . ' ' . $user->last_name);
            $chatData->image=$general->getFileUrl($user->image, 'profile');
            $chatData->type_html = '';
        }else{
            $chatData->image=$general->getFileUrl($chatData->image, 'chat');
            $chatData->type_html = '<div class="chef-button"><span class="btn"> Group</span></div>';
        }
        return ['status' => 1, 'data' => $chatData];
    }

    /**
     * Get the opponent ID from the chat's user IDs.
     *
     * @param int $id The current user ID.
     * @param string $userIds The comma-separated list of user IDs in the chat.
     * 
     * @return string
     */
    public function getOpponantId(int $id, string $userIds): string
    {
        return trim(str_replace($id, '', $userIds), ',');
    }

    

    


    /**
     * Update the read status for a chat message for the current user.
     *
     * @param int $chatId The ID of the chat.
     * 
     * @return int
     */
    public function updateReadStatus(int $chatId): int
    {
        $userId = auth()->id();
        return DB::update("
            UPDATE `chat_message` 
            SET `read_user_ids` = CONCAT(read_user_ids, ?, ?) 
            WHERE `chat_id` = ? 
            AND NOT FIND_IN_SET(?, read_user_ids)
        ", [',', $userId, $chatId, $userId]);
    }


    public function users()
    {
            return $this->belongsToMany(User::class, 'chat_user');

    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function lastMessage()
    {
         return $this->belongsTo(ChatMessage::class, 'last_message_id');
    }


}
