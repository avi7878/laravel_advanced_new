<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\General;


/**
 * Class Note
 * 
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Note extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'note';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    // public $timestamps = true;

    /**
     * The format of the stored date attributes.
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'user_id',
        'note',
        'created_at',
        'updated_at'
    ];

    /**
     * Retrieves a paginated list of notes with search functionality.
     *
     * @param array $postData The data posted from the frontend.
     * @param int $userId The ID of the user whose notes are being fetched.
     * @return array The paginated data, including the notes and their actions.
     */
    public function list(array $postData, int $userId): array
    { 
        $query = DB::table($this->table)
            ->select('*')
            ->where('user_id', $userId);

        // Search functionality
        $searchText = $postData['search']['value'] ?? '';
        if (strlen($searchText) > 2) {
            $searchText = '%' . $searchText . '%';
            $query->where(function ($query) use ($searchText) {
                $query->orWhere("title", 'like', $searchText)
                    ->orWhere("description", 'like', $searchText)
                    ->orWhere(DB::raw("FROM_UNIXTIME(created_at, '%d-%m-%Y')"), 'LIKE', '%' . $searchText . '%');
            });
        }
        // Get the paginated results
        $result = (new Pagination())->getDataTable($query, $postData);
        $general = new General();
        // Format the created_at field and add action buttons
        foreach ($result['data'] as $key => $row) {
             $result['data'][$key]->created_at = date(config('setting.date_time_format'), strtotime($row->created_at));
            $result['data'][$key]->action = $this->generateActionButtons($row->id);
        }
       
        return $result;
    }

    /**
     * Retrieves a paginated list of notes for the admin with search functionality.
     *
     * @param array $requestData The data posted from the frontend.
     * @param int $currentUserId The ID of the current user (admin).
     * @return array The paginated data, including the notes and their actions.
     */
    public function listAdmin(array $postData, int $currentUserId): array
    { 
        $query = DB::table($this->table)
            ->select('*')
            ->where('user_id', $currentUserId);

        // Search functionality

        $searchText = isset($postData['search']['value']) ? $postData['search']['value'] : '';

            if (strlen($searchText) > 2) {
                $searchText = '%' . $searchText . '%';
            
                $query->where(function ($query) use ($searchText) {
                    $query->where("title", 'like', $searchText)
                      ->orWhere("description", 'like', $searchText)
                      ->orWhereRaw("DATE_FORMAT(FROM_UNIXTIME(created_at), '%d-%m-%Y') LIKE ?", [$searchText]);
                });
            }


        // Get the paginated results
                $result = (new Pagination())->getDataTable($query, $postData);
        $general = new General();
        foreach ($result['data'] as $key => $row) {
             $result['data'][$key]->created_at = date(config('setting.date_time_format'), strtotime($row->created_at));
            $result['data'][$key]->action = $this->AdmingenerateActionButtons($row->id);
        }
        
        return $result;
    }

    /**
     * Generates action buttons for a note record.
     *
     * @param int $id The ID of the note.
     * @return string The HTML for action buttons.
     */
    private function generateActionButtons(int $id): string
    {
        return '<a class="text-body router pjax" title="View" onclick="app.showModalView(\'note/view?id=' . $id . '\')"><i class="bx bxs-show icon-base"></i></a>
                <a class="text-body router ms-1 pjax" title="Update" onclick="app.showModalView(\'note/update?id=' . $id . '\')"><i class="bx bxs-edit icon-base"></i></a>
                <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="note/delete" data-id="' . $id . '" class="text-body ms-1 pjax" title="Delete"><i class="bx bxs-trash icon-base"></i></button>';
    }

    /**
     * Generates action buttons for a note record in the admin view.
     *
     * @param int $id The ID of the note.
     * @return string The HTML for action buttons.
     */
    private function AdmingenerateActionButtons(int $id): string
    {
       $sessionUser = auth()->user();
        
        $actionButtons= '';
        
        if($sessionUser->type == 0 && $sessionUser->role== 1){
             // If the user is admin, show both Update and Delete buttons
            $actionButtons = '<div class="act-btns">
                <a href="admin/notes/view?id=' . $id . '" class="text-body router pjax" title="Update"><i class="bx bxs-show icon-base"></i></a> &nbsp;
                <a href="admin/notes/update?id=' . $id . '" class="text-body router pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>
                <button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/notes/delete" data-id="' . $id . '" class="text-body pjax" title="Delete"><i class="bx bxs-trash icon-base"></i></button>
            </div>';
        }else{
            
            if($sessionUser->hasPermission('admin/notes/view')){
                $actionButtons .= '<a href="admin/notes/view?id=' . $id . '" class="text-body act-btns router pjax" title="View"><i class="bx bxs-show icon-base"></i></a>&nbsp;&nbsp;&nbsp';
            }
            if($sessionUser->hasPermission('admin/notes/update')){
                $actionButtons .= '<a href="admin/notes/update?id=' . $id . '" class="text-body act-btns router pjax" title="Update"><i class="bx bxs-edit icon-base"></i></a>&nbsp;&nbsp;&nbsp';
            }
            if($sessionUser->hasPermission('admin/notes/delete')){
                $actionButtons .= '<a href="admin/notes/delete?id=' . $id . '" class="text-body act-btns router pjax" title="delete"><i class="bx bxs-trash icon-base"></i></a>';
            }
        }
        
        return $actionButtons;
    
    }

    /**
     * Saves a note record (create or update).
     *
     * @param array $postData The data to save.
     * @param int $userId The user ID associated with the note.
     * @return array The status and message of the operation.
     */
    public function store(array $postData, int $userId): array
    {
        $validator = Validator::make($postData, [
            'title' => 'required',
            'note' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => $validator->errors()->first(),
            ];
        }

        $id = $postData['id'];
        $model = $id ? Note::find($id) : new Note();

        $model->user_id = $userId;
        $model->title = $postData['title'];
        $model->description = $postData['note'];
        $model->save();

        return [
            'status' => 1,
            'message' => $id ? 'Your note has been updated successfully.' : 'Your note has been created successfully.',
        ];

    }

    /**
     * Saves a note record (create or update) for the admin.
     *
     * @param array $postData The data to save.
     * @param int $userId The user ID associated with the note.
     * @return array The status and message of the operation.
     */
    public function Adminstore(array $postData, int $userId): array
    {
        $validator = Validator::make($postData, [
            'title' => 'required',
            'note' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => $validator->errors()->first(),
            ];
        }

        $id = $postData['id'];
        $model = $id ? Note::find($id) : new Note();

        $model->user_id = $userId;
        $model->title = $postData['title'];
        $model->description = $postData['note'];
        $model->save();

        return [
            'status' => 1,
            'message' => $id ? 'Note updated successfully.' : 'Note created successfully.',
            'next' => 'load',
            'url' => 'admin/notes'
        ];
    }

    /**
     * Helper function to get the error messages from the validator.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator The validator instance.
     * @return string The error messages.
     */
    public function getError($validator): string
    {
        return implode(' ', $validator->errors()->all());
    }
}
