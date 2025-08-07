
<div class="chat__previous d-flex d-md-none"  onclick="chatApp.closeConv();">
    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
      <path d="M3.99929 6.00035L8 9.99965L6.00035 12.0007L0 6.00035L6.00035 0L8 2.00106L3.99929 6.00035Z" fill="#5A6DED" />
    </svg>
  </div>

  <div class="chat__infos pl-2 pl-md-0">
    <div class="chat-member__wrapper" data-online="true">
      <div class="chat-member__avatar">
        <img src="{{ $chat->image}}" id="chat_avatar" alt="Bessie Cooper" loading="lazy">
        <div class="user-status user-status--large"></div>
      </div>
      <div class="chat-member__details">
        <span class="chat-member__name">{{ $chat->title}} {!! $chat->chat_type_html !!}</span>
        <span class="chat-member__status"></span>
      </div>
    </div>
  </div>
  <div class="chat__actions">
      @if ($chat->chat_type == 0 || ($chat->chat_type == 1 && $chat->chat_owner_id == $userId))
      <li class="video-hdr">
        <a href="javascript:void(0);" class="btn del-btn" onclick="app.confirmAction(this)" data-id="{{$chat->id}}" data-action="message/delete">
          <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 20" fill="none">
            <path d="M6.18182 3.7781H9.81818C9.81818 3.2624 9.62662 2.76783 9.28565 2.40317C8.94467 2.03852 8.48221 1.83366 8 1.83366C7.51779 1.83366 7.05533 2.03852 6.71435 2.40317C6.37338 2.76783 6.18182 3.2624 6.18182 3.7781ZM5.09091 3.7781C5.09091 2.95299 5.3974 2.16166 5.94296 1.57822C6.48852 0.994769 7.22846 0.666992 8 0.666992C8.77154 0.666992 9.51148 0.994769 10.057 1.57822C10.6026 2.16166 10.9091 2.95299 10.9091 3.7781H15.4545C15.5992 3.7781 15.7379 3.83956 15.8402 3.94896C15.9425 4.05835 16 4.20673 16 4.36144C16 4.51615 15.9425 4.66452 15.8402 4.77392C15.7379 4.88331 15.5992 4.94477 15.4545 4.94477H14.5018L13.6167 16.3027C13.5523 17.1289 13.2 17.8991 12.6295 18.4605C12.059 19.022 11.312 19.3336 10.5367 19.3337H5.46327C4.68799 19.3336 3.94105 19.022 3.37055 18.4605C2.80005 17.8991 2.44767 17.1289 2.38327 16.3027L1.49818 4.94477H0.545455C0.400791 4.94477 0.262052 4.88331 0.15976 4.77392C0.0574673 4.66452 0 4.51615 0 4.36144C0 4.20673 0.0574673 4.05835 0.15976 3.94896C0.262052 3.83956 0.400791 3.7781 0.545455 3.7781H5.09091ZM3.47055 16.2054C3.51214 16.7401 3.74009 17.2385 4.1092 17.6018C4.47831 17.9652 4.96161 18.1669 5.46327 18.167H10.5367C11.0384 18.1669 11.5217 17.9652 11.8908 17.6018C12.2599 17.2385 12.4879 16.7401 12.5295 16.2054L13.408 4.94477H2.59273L3.47055 16.2054ZM6.36364 7.66699C6.5083 7.66699 6.64704 7.72845 6.74933 7.83785C6.85162 7.94724 6.90909 8.09562 6.90909 8.25033V14.8614C6.90909 15.0161 6.85162 15.1645 6.74933 15.2739C6.64704 15.3833 6.5083 15.4448 6.36364 15.4448C6.21897 15.4448 6.08024 15.3833 5.97794 15.2739C5.87565 15.1645 5.81818 15.0161 5.81818 14.8614V8.25033C5.81818 8.09562 5.87565 7.94724 5.97794 7.83785C6.08024 7.72845 6.21897 7.66699 6.36364 7.66699ZM10.1818 8.25033C10.1818 8.09562 10.1244 7.94724 10.0221 7.83785C9.91977 7.72845 9.78103 7.66699 9.63636 7.66699C9.4917 7.66699 9.35296 7.72845 9.25067 7.83785C9.14838 7.94724 9.09091 8.09562 9.09091 8.25033V14.8614C9.09091 15.0161 9.14838 15.1645 9.25067 15.2739C9.35296 15.3833 9.4917 15.4448 9.63636 15.4448C9.78103 15.4448 9.91977 15.3833 10.0221 15.2739C10.1244 15.1645 10.1818 15.0161 10.1818 14.8614V8.25033Z" fill="white" />
          </svg>
        </a>
      </li>
      @endif
      <li class="hlp-hdr">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V9H11V15ZM11 7H9V5H11V7Z" fill="#5A6DED" />
        </svg>
      </li>

      <div class="more d-none">
        <div class="dropdown show">
          <a class="dropdown-toggle" href="javascript:void(0);" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="4" viewBox="0 0 16 4" fill="none">
              <circle cx="14" cy="2" r="2" transform="rotate(90 14 2)" fill="#5A6DED" />
              <circle cx="8" cy="2" r="2" transform="rotate(90 8 2)" fill="#5A6DED" />
              <circle cx="2" cy="2" r="2" transform="rotate(90 2 2)" fill="#5A6DED" />
            </svg>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <ul class="m-0">
              <li class="call-hdr call-hdr-mobile">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3.62 7.79C5.06 10.62 7.38 12.93 10.21 14.38L12.41 12.18C12.68 11.91 13.08 11.82 13.43 11.94C14.55 12.31 15.76 12.51 17 12.51C17.55 12.51 18 12.96 18 13.51V17C18 17.55 17.55 18 17 18C7.61 18 0 10.39 0 1C0 0.45 0.45 0 1 0H4.5C5.05 0 5.5 0.45 5.5 1C5.5 2.25 5.7 3.45 6.07 4.57C6.18 4.92 6.1 5.31 5.82 5.59L3.62 7.79Z" fill="#5A6DED" />
                </svg>
              </li>
              <li class="video-hdr video-hdr-mobile">
                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14 4.5V1C14 0.45 13.55 0 13 0H1C0.45 0 0 0.45 0 1V11C0 11.55 0.45 12 1 12H13C13.55 12 14 11.55 14 11V7.5L18 11.5V0.5L14 4.5Z" fill="#5A6DED" />
                </svg>

              </li>

              <li class="hlp-hdr hlp-hdr-mobile">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V9H11V15ZM11 7H9V5H11V7Z" fill="#5A6DED" />
                </svg>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </ul>



  </div>