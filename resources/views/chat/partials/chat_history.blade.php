<div class="chat-history-wrapper">
    <div class="chat-history-header border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex overflow-hidden align-items-center">
                <i class="icon-base bx bx-menu icon-lg cursor-pointer d-lg-none d-block me-4" data-bs-toggle="sidebar" data-overlay="" data-target="#app-chat-contacts"></i>
                <div class="flex-shrink-0 avatar avatar-online">
                    @if ($avatar)
                        <img id="chat-avatar-{{ $chat->id }}" src="{{ $avatar }}" alt="Avatar" class="rounded-circle">
                        <span id="chat-initials-{{ $chat->id }}" class="avatar-initial rounded-circle bg-label-primary d-none"></span>
                    @else
                        <span id="chat-initials-{{ $chat->id }}" class="avatar-initial rounded-circle bg-label-primary">{{ $initials }}</span>
                        <img id="chat-avatar-{{ $chat->id }}" src="" alt="Avatar" class="rounded-circle d-none">
                    @endif
                </div>
                <div class="chat-contact-info flex-grow-1 ms-4"> 
                    <h6 class="m-0 fw-normal" id="chat-username-{{ $chat->id }}">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-messages" id="chat-messages-{{ $chat->id }}">
        @foreach ($messages as $message)
            <div class="chat-message @if($message->user_id == auth()->id()) outgoing @else incoming @endif">
                <div class="message-content">
                    <p>{{ $message->message }}</p>
                    <small>{{ $message->user->first_name }} - {{ $message->created_at->diffForHumans() }}</small>
                </div>
            </div>
        @endforeach
    </div>
</div>
