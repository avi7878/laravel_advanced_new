<?php
if(isset($messageList)){
    echo @$messageList['links'];
}else{
    $messageList['data']=[$message];
}
?>
@foreach ($messageList['data'] as $message)
<?php
$msg = $message->message ?? '';
$message_class = $message->user_id ==$userId ?'me': 'you';
if ($message->type) {
    $data = json_decode($message->data, true);
    if ($data) {
        // Ensure 'file_type' exists in the array before accessing it
        $fileType = $data['file_type'] ?? '';
        $fileName = $data['file_name'] ?? ''; // Default empty string if file_name doesn't exist
        $fileUrl = $general->getFileUrl($fileName, 'chat');
        // Check the file type and create the appropriate HTML
        if (strpos($fileType, 'image') !== false) {
            $msg .= '<img class="chat-message-image" src="' . $fileUrl . '">';
        } elseif (strpos($fileType, 'mp3') !== false) {
            $msg .= '<audio controls src="' . $fileUrl . '"></audio>';
        } else {
            $msg .= '<span style="padding:30px;"><i class="bx bxs-file" style="font-size:100px;"></i></span><br>';
        }
        // Ensure 'name' exists in the array before appending the download link
        $fileNameDisplay = $data['name'] ?? 'File'; // Use 'File' as fallback
        $msg .= '<a download href="' . $fileUrl . '">
        <i class="fa-solid fa-download"></i> ' . $fileNameDisplay . '</a>';
    }
} 
?>
<div class="message chat-message-sender-{{ $message_class }} chat-message-{{ $message->id }}">
  <div class="chat_userimage"><img src="{{ $message->image }}" alt="" loading="lazy"></div>
  <div class="chatbubble ">{!! $msg !!}</div>
  <div class="chat__time ">{{ date(config('setting.date_time_format'),$message->created_at)  }}</div>
</div>
@endforeach