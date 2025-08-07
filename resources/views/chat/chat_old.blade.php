@extends('layouts.main')
@section('title')
Messages
@endsection
@section('content')
<link href="assets/css/chat.css" rel="stylesheet">
<style>
    footer {
        display: none;
    }
</style>
<style>
    @media (min-width: 0px) {
        * {
            box-sizing: border-box;
        }

        .modal-body-audio {
            margin: 0;
            padding: 0;
            background-color: lightcyan;
            color: #414142;
            position: relative;
            font-family: monospace;
        }

        .title {
            font-size: 30px;
            margin-bottom: 55px;
            text-align: center;
        }

        .audio-recording-container {
            width: 100%;
            height: 100vh;
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .start-recording-button {
            font-size: 70px;
            color: #435f7a;
            cursor: pointer;
            opacity: .5;
            margin-bottom: 30px;
        }

        .start-recording-button:hover {
            opacity: 1;
        }

        .recording-contorl-buttons-container {
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            width: 334px;
            margin-bottom: 30px;
        }

        .cancel-recording-button,
        .stop-recording-button {
            font-size: 70px;
            cursor: pointer;
        }

        .cancel-recording-button {
            color: red;
            opacity: 0.7;
        }

        .cancel-recording-button:hover {
            color: rgb(206, 4, 4);
        }

        .stop-recording-button {
            color: #33cc33;
            opacity: 0.7;
        }

        .stop-recording-button:hover {
            color: #27a527;
        }

        .recording-elapsed-time {
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .red-recording-dot {
            font-size: 25px;
            color: red;
            margin-right: 12px;
            animation-name: flashing-recording-dot;
            -webkit-animation-name: flashing-recording-dot;
            -moz-animation-name: flashing-recording-dot;
            -o-animation-name: flashing-recording-dot;
            animation-duration: 2s;
            -webkit-animation-duration: 2s;
            -moz-animation-duration: 2s;
            -o-animation-duration: 2s;
            animation-iteration-count: infinite;
            -webkit-animation-iteration-count: infinite;
            -moz-animation-iteration-count: infinite;
            -o-animation-iteration-count: infinite;
        }

        @keyframes flashing-recording-dot {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @-webkit-keyframes flashing-recording-dot {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @-moz-keyframes flashing-recording-dot {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @-o-keyframes flashing-recording-dot {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .elapsed-time {
            font-size: 32px;
        }

        .recording-contorl-buttons-container.hide {
            display: none;
        }

        .overlay-modal {
            position: absolute;
            top: 0;
            height: 100vh;
            width: 100%;
            background-color: rgba(82, 76, 76, 0.35);
            /*targeting Chrome & Safari*/
            display: -webkit-flex;
            /*targeting IE10*/
            display: -ms-flex;
            display: flex;
            justify-content: center;
            /*horizontal centering*/
            align-items: center;
        }

        .overlay-modal.hide {
            display: none;
        }

        .browser-not-supporting-audio-recording-box {
            /*targeting Chrome & Safari*/
            display: -webkit-flex;
            /*targeting IE10*/
            display: -ms-flex;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /*horizontal centering*/
            align-items: center;
            width: 317px;
            height: 119px;
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
        }

        .close-browser-not-supported-box {
            cursor: pointer;
            background-color: #abc1c05c;
            border-radius: 10px;
            font-size: 16px;
            border: none;
        }

        .close-browser-not-supported-box:hover {
            background-color: #92a5a45c;
        }

        .close-browser-not-supported-box:focus {
            outline: none;
            border: none;
        }

        .audio-element.hide {
            display: none;
        }

        .text-indication-of-audio-playing-container {
            height: 20px;
        }

        .text-indication-of-audio-playing {
            font-size: 20px;
        }

        .text-indication-of-audio-playing.hide {
            display: none;
        }

        /* 3 Dots animation*/
        .text-indication-of-audio-playing span {
            /*transitions with Firefox, IE and Opera Support browser support*/
            animation-name: blinking-dot;
            -webkit-animation-name: blinking-dot;
            -moz-animation-name: blinking-dot;
            -o-animation-name: blinking-dot;
            animation-duration: 2s;
            -webkit-animation-duration: 2s;
            -moz-animation-duration: 2s;
            -o-animation-duration: 2s;
            animation-iteration-count: infinite;
            -webkit-animation-iteration-count: infinite;
            -moz-animation-iteration-count: infinite;
            -o-animation-iteration-count: infinite;
        }

        .text-indication-of-audio-playing span:nth-child(2) {
            animation-delay: .4s;
            -webkit-animation-delay: .4s;
            -moz-animation-delay: .4s;
            -o-animation-delay: .4s;
        }

        .text-indication-of-audio-playing span:nth-child(3) {
            animation-delay: .8s;
            -webkit-animation-delay: .8s;
            -moz-animation-delay: .8s;
            -o-animation-delay: .8s;
        }

        @keyframes blinking-dot {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @-webkit-keyframes blinking-dot {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @-moz-keyframes blinking-dot {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @-o-keyframes blinking-dot {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }
    }
</style>
<div class="gray-simple chat-page">
    <div class="message-page chat-message-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 message-inner1">
                    <div class="row">
                        <div class="col-12 col-xxl-3 col-lg-3 col-md-5 mb-11 mb-lg-0 inbox-sidebar">
                            <!-- Sidebar Start -->
                            <div class="p-0 mb-0 simple-sidebar">
                                <div class="widgets side-sub-menu ">
                                    <ul class="px-0 chat-list" id="conversation-list">

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Main Body -->
                        <div class="col-12 col-xxl-6 col-lg-6 col-md-7 chat-section">
                            <div class="col-md-12 cust-toggle">
                                <div class="sidebar-btns">
                                    <button class="openbtn" onclick="openNav()">☰</button>
                                    <button class="openbtn-right" onclick="openNav2()">
                                        Plate Detail</button>
                                </div>
                                <div id="mySidepanel" class="bg-white sidepanel">
                                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                                    <ul class="px-0 chat-list" id="conversation-mob-list">
                                    </ul>
                                </div>

                                <div id="mySidepanel2" class="bg-white sidepanel">
                                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav1()">×</a>
                                    <div class="bg-white shadow-9 rounded-4 ad-listmob" id="ad-mob-list">
                                    </div>
                                </div>


                            </div>
                            <section class="dashboard-wraper msger">
                                <header class="msger-header border-bottom" id="chat-header">
                                </header>
                                <main class="msger-chat" id="message-container">
                                </main>
                                <div id="file-input-container" style="display:none;">
                                    <input type="file" name="file" id="file-input" onchange="chatApp.previewFile(this);">
                                </div>
                                <div class="msger-inputarea">
                                    <div class="reply-emojis" onclick="$('#file-input').click();"><button onclick="chatApp.sendMessage()" type="button" class="msger-send-btn btn btn-primary"><i class="fas fa-paperclip"></i></button></div>
                                    <!--<img src="assets/images/upload.png">-->

                                    <input id="message-input" onkeyup="chatApp.checkInputKey(event)" type="text" class="msger-input form-control" placeholder="Enter your message">
                                    <button class="msger-send-btn btn btn-primary" id="emojisecond" type="button"><i class="fas fa-smile mr-1 first-btn123" aria-hidden="true"></i></button>

                                    <button class="msger-send-btn btn btn-primary" onclick="audioRecorder.modal()" type="button"><i class="fas fa-microphone mr-1 " aria-hidden="true"></i></button>

                                    <button onclick="chatApp.sendMessage()" type="button" class="msger-send-btn btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </section>
                            <!-- form end -->
                        </div>
                        <div id="mySidepanel" class="col-12 col-xxl-3 col-lg-3 col-md-5 mb-11 mb-lg-0 inbox-sidebar chat-section last-sidebar">
                            <div class="bg-white shadow-9 rounded-4" id="ad-list">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="audioModal" role="dialog" aria-labelledby="audioModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="audioModalLabel">Tap to record</h5>
                <button type="button" class="close" onclick="closeAudioModal()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body-audio">
                <div class="audio-recording-container">

                    <span class="start-recording-button fa fa-microphone" aria-hidden="true"></span>
                    <div class="recording-contorl-buttons-container hide">
                        <span class="cancel-recording-button fa fa-times-circle-o" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" id="cancel">
                                <path d="M24 4C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4zm10 27.17L31.17 34 24 26.83 16.83 34 14 31.17 21.17 24 14 16.83 16.83 14 24 21.17 31.17 14 34 16.83 26.83 24 34 31.17z">
                                </path>
                                <path fill="none" d="M0 0h48v48H0z"></path>
                            </svg></span>
                        <div class="recording-elapsed-time">
                            <i class="red-recording-dot fa fa-circle" aria-hidden="true"></i>
                            <p class="elapsed-time"></p>
                        </div>
                        <span class="stop-recording-button fa fa-stop-circle-o" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" id="stop">
                                <path fill="none" d="M0 0h48v48H0z"></path>
                                <path d="M12 12h24v24H12z"></path>
                            </svg></span>
                    </div>
                    <div class="text-indication-of-audio-playing-container">
                        <p class="text-indication-of-audio-playing hide">Audio is playing<span>.</span>
                            <span>.</span><span>.</span>
                        </p>
                    </div>
                </div>
                <div class="overlay-modal hide">
                    <div class="browser-not-supporting-audio-recording-box">
                        <p>
                            To record audio, use browsers like Chrome and Firefox that support audio recording</p>
                        <button type="button" data-dismiss="modal" onclick="closeAudioModal()" class="close-browser-not-supported-box">Ok.</button>
                    </div>
                </div>

                <audio controls class="audio-element hide">
                </audio>
            </div>

        </div>
    </div>
</div>

<script type="text/html" id="conversation-template">
    <li class="message-list conversation___conv_id__" onclick="chatApp.startConversation(__conv_id__)">
        <div class="d-flex media align-items-center  routed">
            <div class="side-profile-img">
                <div class="profile-img d-block">
                    <img src="__image__" alt="img">
                    <span class="user-status user__user_id__ __is_online_class__"></span>
                </div>
            </div>
            <div class="side-uname px-3">
                <h4 class="mb-0">__name__ # __ad_id__</h4>
                <p class="mb-0 heading-default-color conversation_last_message___conv_id__">__message_text__</p>
            </div>
            <div class="right-innof">
                <span class="time">__created_at__</span> <br>
                <span class="notification">__count_unread_msg__</span>
            </div>
        </div>
    </li>
</script>

<script type="text/html" id="message-template">
    <div class="msg chat___chat_id__ __message_class__">
        <div class="msg-bubble">
            <div class="msg-text type-__message_type__">
                __message__ <i class="read-status read__status__  fa-solid fa-check"></i>
            </div>
            <div class="msg-info">
                <div class="msg-info-name">__name__</div>
                <div class="msg-info-time">__created_at__</div>
            </div>
        </div>
    </div>
</script>

<script src="assets/js/audio.js"></script>

<script>
    function openNav() {
        document.getElementById("mySidepanel").style.width = "100%";
        document.getElementById("mySidepanel").style.left = "0";
    }

    function closeNav() {
        document.getElementById("mySidepanel").style.width = "0";
    }

    function openNav2() {
        document.getElementById("mySidepanel2").style.width = "100%";
        document.getElementById("mySidepanel2").style.left = "0";
    }

    function closeNav1() {
        document.getElementById("mySidepanel2").style.width = "0";
    }

    function closeAudioModal() {
        $('#audioModal').modal('hide');
    }


    documentReady(function() {
        $('#audioModal').on('hidden.bs.modal', function() {
            cancelAudioRecording();
        })
        <?php if (isset($conversationId) && $conversationId) { ?>

            chatApp.conversationId = <?= @$conversationId ?>;
        <?php } ?>
        chatApp.init();
    })
</script>
@endsection