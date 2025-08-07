@foreach ($chatList['data'] as $row)
<div class="messaging-member messaging-member--new chat chat_{{ $row->chat_id }}" onclick="chatApp.startConversation({{ $row->chat_id }},{{ $row->chat_type }},'{{ $row->user_ids }}')">
  <div class="messaging-member__wrapper ">
    <div class="messaging-member__avatar">
      <img src="{{$row->image}}" alt="img" loading="lazy">
      <div class="user-status user_{{$row->opponent_id}} online_{{$row->is_online}}"></div>
      <div class="unread-count">{{$row->unread_count}}</div>
    </div>
    <div class="member__name-top">
      <div class="messaging-member__name">{{$row->title}} {!! $row->chat_type_html !!}</div>
      <div class="messaging-member__message chat_last_message">{{$row->message_text}}</div>
      <span>{{ $row->created_at }}</span>
    </div>
    <!-- <div class="chef-button">
      <a href="#" class="btn">Master Chef</a>
    </div> -->
  </div>
</div>
@endforeach
{!! $chatList['links'] !!}