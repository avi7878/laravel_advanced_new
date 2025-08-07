{!! $messageList['links'] !!}
@foreach ($messageList['data'] as $message)
  {{view('chat/message',compact('message','userId'))}}
@endforeach