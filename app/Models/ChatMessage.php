<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Message
 *
 * @package App\Models
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $message
 * @property int $type
 * @property string $data
 * @property string $reference_id
 * @property string $read_user_ids
 * @property string $created_at
 * @property string $updated_at
 */
class ChatMessage extends Model
{
    use HasFactory;

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_message';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'type',
        'data',
        'reference_id',
        'read_user_ids',
        'created_at',
        'updated_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    protected $dateFormat = 'U';

    protected $casts = [
         'data' => 'array',
        'created_at' => 'integer', // or 'integer' if stored as timestamp
    ];

    

    /**
     * Get the message history for a given chat ID.
     *
     * @param int $chatId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list($postData, $userId)
    {
        $query = DB::table($this->table)->select('chat_message.*', 'user.image')
            ->where('chat_id', $postData['chat_id'])
            ->leftJoin('user', 'user.id', '=', 'chat_message.user_id')
            ->orderBy('id', 'desc');
        $result = (new Pagination())->getData($query, $postData,3);
        $result['data'] = $result['data']->reverse();
        return $result;
    }
    

    /**
     * Get the unread message count for a specific user in a given chat.
     *
     * @param int $chatId
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $chatId, int $userId): int
    {
        return self::where('chat_id', $chatId)
            ->where(DB::raw("NOT FIND_IN_SET(?, read_user_ids)", [$userId]))
            ->count();
    }

    /**
     * Get the unread message count for a chat, directly using a raw query.
     *
     * @param int $chatId
     * @param int $userId
     * @return int
     */
    public function getChatUnreadCount(int $chatId, int $userId): int
    {
        return DB::select('select count(*) as unread_count from chat_message where chat_id =? and not FIND_IN_SET(?,read_user_ids)', [$chatId, $userId])[0]->unread_count;
    }

    public function getLatestMessage($chatId, $userId)
    {
        return $this->where('chat_id', $chatId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Generate the text content for the message.
     *
     * @param \App\Models\Message $message
     * @param int $userId
     * @return string
     */
    public function messageText($message, int $userId): string
    {
        $msg = $message->message ?? '';

        if ($message->type) {
            $msg = '[File]';
        }

        if ($msg && $userId == $message->user_id) {
            $msg = 'Me: ' . $msg;
        }

        return $msg;
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

}
