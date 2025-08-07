@extends('layouts.main')
@section('title')
Chat
@endsection
@section('content')


 <!-- / Content -->
 <div class="home-page__content messages-page mt-5">
  <div class="container h-100">
    <div class="d-flex justify-content-between">
      <!-- start message list section  -->
      <div class="col-12 col-md-5 col-lg-5 col-xl-4 px-0 cust-radius messages-page__list-scroll">
        <div class="messages-page__top">
          <div class="messages-page__header messages-page__header-fst mb-0 px-4 pt-3 pb-3">
            <span class="messages-page__title">Chats</span>
          </div>
          <div class="messages-page__header px-4 messages-page__header-second">
            <div class="cht-add">
              <a href="javascript:void(0);" onclick="app.showModalView('group/create_modal')" data-id='{{ @$conversationId }}' class="btn btn-primary">Chat <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path d="M11.8346 6.83366H6.83464V11.8337H5.16797V6.83366H0.167969V5.16699H5.16797V0.166992H6.83464V5.16699H11.8346V6.83366Z" fill="white" />
                </svg>
              </a>
            </div>
          </div>
        </div>
        <form onsubmit="event.preventDefault();chatApp.searchConversation()">
          <div class="messages-page__search mb-0 px-3">
            <div class="custom-form__search-wrapper">
              <input type="text" class="form-control custom-form" onkeyup="chatApp.searchConversation()" id="search_param" placeholder="Search…" autocomplete="off">
              <button type="submit" class="custom-form__search-submit">
                <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon svg-icon--search" viewBox="0 0 46.6 46.6">
                  <path d="M46.1,43.2l-9-8.9a20.9,20.9,0,1,0-2.8,2.8l8.9,9a1.9,1.9,0,0,0,1.4.5,2,2,0,0,0,1.5-.5A2.3,2.3,0,0,0,46.1,43.2ZM4,21a17,17,0,1,1,33.9,0A17.1,17.1,0,0,1,33,32.9h-.1A17,17,0,0,1,4,21Z" fill="#f68b3c" />
                </svg>
              </button>
            </div>
          </div>
        </form>
        <div class="messages-page__list pb-5 px-1 px-md-3" id="chat-list" >
        </div>
      </div>
      <!-- end message list section  -->
      <!-- start content section  -->
      <div class="chat col-12 col-md-6 col-lg-6 col-xl-8 px-0 pl-md-1 chatbox" style="display:none;">
        <div class="chat__container cust-radius">
          <div class="chat__wrapper">
            <div class="chat__messaging messaging-member--online " id="chat-detail">
            </div>
            <div class="chat__content">
              <div class="chat__list-messages" id="chat-message-list">
              </div>
            </div>
            <div class="chat__send-container">
              <div class="custom-form__send-wrapper">
                <div class="cust_send_left">
                  <div class="file-input">
                    <div id="file-input-container" style="display:none;">
                      <input type="file" name="file" id="chat-file-input" onchange="chatApp.previewFile(this);">
                    </div>
                    <label class="file-input__label" for="file-input" onclick="$('#file-input').attr('accept','')">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M4.04779 23.6162C3.03042 23.6162 2.05361 23.1951 1.27511 22.4159C-0.451013 20.6841 -0.451013 17.8675 1.27471 16.1369L14.7342 1.84415C16.8342 -0.259225 20.051 -0.0702254 22.3985 2.28027C23.4503 3.33402 24.0406 4.85315 24.0185 6.4499C23.9963 8.0298 23.3791 9.54177 22.3242 10.5985L12.1519 21.4278C11.8688 21.7312 11.3941 21.745 11.0918 21.4604C10.7903 21.1754 10.7757 20.6999 11.06 20.3976L21.2476 9.5519C22.0445 8.75352 22.5016 7.621 22.5184 6.42888C22.5353 5.23602 22.1044 4.11138 21.3379 3.343C19.8979 1.9 17.5534 1.14438 15.8104 2.8915L2.35129 17.1842C1.19404 18.3445 1.19442 20.2079 2.33629 21.3527C2.87177 21.8886 3.50739 22.1496 4.18427 22.1087C4.85402 22.0678 5.54252 21.7243 6.12302 21.1423L16.8322 9.74383C17.2204 9.35495 18.0004 8.4017 17.2065 7.60595C16.7569 7.15558 16.4411 7.18333 16.3373 7.19195C16.0406 7.2182 15.6941 7.42333 15.3345 7.78408L7.27389 16.357C6.98927 16.6596 6.51414 16.6742 6.21375 16.3888C5.91187 16.1046 5.89799 15.6283 6.18187 15.3268L14.2572 6.73783C14.892 6.09996 15.5412 5.7542 16.2019 5.69495C16.7175 5.64918 17.4844 5.75908 18.2667 6.54358C19.4277 7.70681 19.2833 9.41308 17.9085 10.7908L7.19929 22.1886C6.34429 23.0466 5.31189 23.5449 4.27614 23.6087C4.20002 23.6139 4.12389 23.6162 4.04777 23.6162L4.04779 23.6162Z" fill="#5A6DED" />
                      </svg>
                    </label>
                  </div>
                </div>
                <div class="cust_send_center">
                  <input type="text" class="form-control custom-form" id="chat-input" placeholder="Write a message..." autocomplete="off" onkeyup="chatApp.checkInputKey(event)">
                </div>
                <div class="cust_send_right">
                  <button type="button" onclick="chatApp.sendMessage()" class="btn-primary custom-form__send-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                      <path d="M12.9459 21.7814L21.4241 0.575266L0.218009 9.05348L5.16068 14.0103L17.1815 4.81791L7.98911 16.8387L12.9459 21.7814Z" fill="white" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  documentReady(function() {
    @if(isset($conversationId) && $conversationId)
      chatApp.conversationId = <?= $conversationId ?>;
    @endif
    chatApp.init();
  })
</script> 

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("app-chat-sidebar-left");
    const openBtns = document.querySelectorAll('[data-target="#app-chat-sidebar-left"]');
    const overlay = document.createElement("div");
    overlay.classList.add("sidebar-overlay");

    // Append overlay to body
    document.body.appendChild(overlay);

    // Open sidebar
    openBtns.forEach(btn => {
      btn.addEventListener("click", () => {
        sidebar.classList.add("active");
        overlay.classList.add("active");
      });
    });

    // Close sidebar on overlay or close button click
    overlay.addEventListener("click", closeSidebar);
    document.querySelectorAll('.close-sidebar').forEach(btn => {
      btn.addEventListener("click", closeSidebar);
    });

    function closeSidebar() {
      sidebar.classList.remove("active");
      overlay.classList.remove("active");
    }
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const chatList = document.getElementById('chat-list');
  const chatLinks = chatList.querySelectorAll('a.chat-list-link');

  function showChatById(selectedChatId) {
    document.querySelectorAll('.chat-conversation').forEach(div => {
      if (div.getAttribute('data-chat-id') === selectedChatId) {
        div.classList.remove('d-none');
        div.classList.add('d-block');
      } else {
        div.classList.add('d-none');
        div.classList.remove('d-block');
      }
    });
  }
  chatLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();

      // Remove active class from all li
      chatList.querySelectorAll('li').forEach(li => li.classList.remove('active'));

      // Add active class to clicked li
      link.parentElement.classList.add('active');

      // Get selected chat id
      const selectedChatId = link.getAttribute('data-chat-id');

      // Show the corresponding chat conversation
      showChatById(selectedChatId);
    });
  });

  // On page load, show the active chat if any
  const activeLink = chatList.querySelector('li.active a');
  if (activeLink) {
    showChatById(activeLink.getAttribute('data-chat-id'));
  }
});


document.addEventListener("DOMContentLoaded", function() {
    const fileInput = document.getElementById("attach-doc");
    const form = document.querySelector(".form-send-message");
    const messageInput = form.querySelector(".message-input");
    const submitButton = form.querySelector("button[type='submit']");

    // Auto-submit if a file is chosen
    fileInput.addEventListener("change", function() {
        if (fileInput.files.length > 0) {
            form.submit();
        }
    });

    // Prevent empty form submission
    form.addEventListener("submit", function(e) {
        const message = messageInput.value.trim();
        const hasFile = fileInput.files.length > 0;

        if (!message && !hasFile) {
            e.preventDefault();
            alert("Please enter a message or attach a file.");
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.bx-menu[data-bs-toggle="sidebar"]').forEach(menuBtn => {
    menuBtn.addEventListener("click", () => {
      // Change selector below if your sidebar ID is different
      const sidebarRight = document.querySelector('.app-chat-sidebar-right.show') 
        ? document.querySelector('.app-chat-sidebar-right.show') 
        : document.querySelector('.app-chat-sidebar-right');
      
      if (sidebarRight) {
        sidebarRight.classList.toggle("show");
      }
    });
  });
});
</script>

@endsection

