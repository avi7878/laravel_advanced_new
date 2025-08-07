<li class="messaging-member messaging-member--new conversation conversation_{{ $chatId }}" onclick="chatApp.startConversation({{ $chatId }})">
    <div class="messaging-member__wrapper ">
      <div class="messaging-member__avatar">
        <img src="__image__" alt="img" loading="lazy">
        <div class="user-status user_{{$opponentId}} online_{{$is_online}}"></div>
        <div class="user-count">{{$count_unread_msg}}</div>
      </div>
      <div class="member__name-top">
        <div class="messaging-member__name">{{$title}}</div>
        <div class="messaging-member__message  conversation_last_message___chat_id__">{{$message_text}}</div>
      </div>
      <!-- <div class="chef-button">
        <a href="#" class="btn">Master Chef</a>
      </div> -->
    </div>
  </li>