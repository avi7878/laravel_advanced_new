
var chatApp = {
    prefix: APP_UID + "_",
    userId: false,
    chatId: false,
    chatType: 0,
    chatUserIds: '',
    editor: false,
    init: function () {
        this.messageListContainer = $("#chat-message-list");
        this.chatListContainer = $("#chat-list");
        this.editor = $("#chat-input");

        this.chatList = new Pagination();
        this.chatList.setUrl=false;
        this.chatList.type=2;
        this.chatList.init('chat/list', '#chat-list');

        this.messageList = new Pagination();
        this.messageList.initLoadData = false;
        this.messageList.setUrl=false;
        this.messageList.type=3;
        this.messageList.init('chat/message-list', '#chat-message-list');

        // this.chatDetail = new View();
        // this.chatDetail.init('#chat-detail', 'chat/detail', {}, false);
    },

    connect: function (userId) {
        console.log("connected: " + userId);
        chatApp.userId = userId;
        chatApp.pubnub = new PubNub({
            subscribeKey: "sub-c-0ae29305-6f96-43b7-80ed-f9d542465eb1",
            publishKey: "pub-c-37a91020-5852-450e-955d-c640310eb3e4",
            uuid: chatApp.prefix + userId,
        });
        chatApp.pubnub.subscribe({
            channels: [chatApp.prefix + userId],
        });
        chatApp.pubnub.addListener({
            message: function (m) {
                console.log(m.message);
                chatApp.socketData(m.message.type, m.message.data);
            },
        });
    },
    sendSocketMessage: function (userId, type, data) {
        return chatApp.pubnub.publish({
            channel: chatApp.prefix + userId,
            message: {
                type: type,
                data: data,
                time: parseInt(Date.now() / 1000),
            },
            storeInHistory: false,
        });
    },
    sendSocketMessages: function (userIds, type, data) {
        $.each(userIds, function (index, userId) {
            chatApp.sendSocketMessage(userId, type, data);
        });
    },
    socketData: function (type, data) {
        if (type == "CHAT") {
            console.log(data.chat_id);
            var chatElement = $(".chat_" + data.chat_id);
            if (chatApp.chatId == data.chat_id) {
                if(chatApp.messageListContainer.find('.chat-message-'+data.message_id).length){
                    return false;
                }
                chatApp.messageListContainer.append(chatApp.userId==data.user_id?data.message_html:data.message_html_opponent);
                chatApp.scrollBottom();
                // chatApp.readStatusUpdate();
                chatElement.find(".chat_last_message").text(chatApp.userId==data.user_id?data.message_text:data.message_text_opponent);
            } else {
                if (!chatElement.length) {
                    chatApp.loadConversation();
                }
                $('.chat-unread-count').show();

                var messageCount = chatElement.find(".unread-count");
                if (messageCount.text() == "") {
                    messageCount.text('1');
                } else {
                    messageCount.text(parseInt(messageCount.text()) + 1);
                }
            }
        }
    },

    loadConversation: function () {
        this.chatList.loadList(1, function () {
            // if (chatApp.chatId == false) {
            //     chatApp.conversationList.find(".chat").eq(0).click();
            // }
        });
    },

    loadChatHistory: function () {
        this.messageList.postData.chat_id = chatApp.chatId;
        this.messageList.loadList(1, function () {
            chatApp.scrollBottom();
            if (chatApp.chatType == 0) {
                //send read status to opponents
                chatApp.sendSocketMessages(chatApp.chatUserIds, "CHAT_READ", { chat_id: chatApp.chatId, user_id: chatApp.userId });
            }
            chatApp.chatListContainer.find('.chat_' + chatApp.chatId).find('.unread_count').text("");
            if (chatApp.chatListContainer.find('.unread_count:empty').length) {
                $('.chat-unread-count').hide();
            }
        });
    },
    scrollBottom: function () {
        chatApp.messageListContainer.parent().scrollTop(chatApp.messageListContainer[0].scrollHeight);
        setTimeout(function () {
            chatApp.messageListContainer.scrollTop(
                chatApp.messageListContainer[0].scrollHeight
            );
        }, 1000);
    },

    loadChatDetail: function () {
        this.chatDetail.load({ chat_id: chatApp.chatId });
    },

    startConversation: function (id, type, userIds) {
        if ($("body").width() < 768) {
            $(".messages-page__list-scroll").hide();
        }
        $(".chatbox").show();
        chatApp.chatId = id;
        chatApp.chatType = type;
        chatApp.chatUserIds = userIds.split(',');
        chatApp.messageList.ajaxContainer.html('');
        chatApp.loadChatHistory();
        chatApp.loadChatDetail();
    },

    readStatusUpdate: function () {
        $.ajax({
            url: "chat/message-read",
            method: "post",
            data: { _token: CSRF_TOKEN, chat_id: chatApp.chatId },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    chatApp.sendSocketMessages(chatApp.chatUserIds, "CHAT_READ", { chat_id: chatApp.chatId, user_id: chatApp.userId });
                }
            },
        });
    },

    send: function (data) {
        data['_token'] = CSRF_TOKEN;
        data['chat_id'] = chatApp.chatId;
        $.ajax({
            url: "chat/message-send",
            method: "post",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    chatApp.editor.val("");
                    chatApp.messageListContainer.append(response.data.message_html);
                    chatApp.scrollBottom();
                    chatApp.sendSocketMessages(chatApp.chatUserIds, "CHAT", response.data);
                    chatApp.chatListContainer.find('.chat_' + chatApp.chatId).find(".chat_last_message").html(response.data.message_text);
                } else {
                    app.showMessage(response.message, "error");
                }
            },
        });
    },

    sendMessage: function () {
        var messageText = chatApp.editor.val();
        if (messageText == "") {
            return false;
        }
        chatApp.send({ message: messageText });
    },
    checkInputKey: function (e) {
        if (e.keyCode == 13) {
            chatApp.sendMessage();
        }
    },
    sendFileMessage: function () {
        var messageText = chatApp.editor.val();
        console.log(chatApp.fileData);
        if (chatApp.fileData) {
            chatApp.send({
                message: messageText,
                data: chatApp.fileData,
                type: 1
            });
        }
    },

    file: false,
    fileData: false,
    uploadProcessRunning: false,
    previewFile: function (fileInput) {
        chatApp.file = fileInput.files[0];

        if (!chatApp.file) {
            console.log("No file selected.");
            return;
        }

        console.log("File selected:", chatApp.file);
        console.log("File name:", chatApp.file.name);
        console.log("File type:", chatApp.file.type);
        console.log("File size (bytes):", chatApp.file.size);

        // Reset file input so the same file can be reselected later
        $("#file-input-container").html(`
            <input type="file" name="file" id="file-input" onchange="chatApp.previewFile(this);" aria-label="Select a file to upload">
        `);

        // Start building modal content
        let html = `
            <div class="modal-header">
                <h1 class="modal-title fs-4">Send File</h1>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="theme/assets/images/modal_close.svg" alt="" class="close-images">
                </button>
            </div>
            <div class="modal-body text-center">
        `;

        // Check if it's an image
        if (chatApp.file.type.startsWith("image/")) {
            const imgURL = URL.createObjectURL(chatApp.file);
            html += `<img src="${imgURL}" alt="Preview" style="max-width:100%; height:auto; padding:15px;"><br>`;
        }
        // Check if it's an audio (MP3)
        else if (chatApp.file.type.startsWith("audio/") && chatApp.file.type === "audio/mpeg") {
            const audioURL = URL.createObjectURL(chatApp.file);
            html += `
                <audio controls style="max-width:100%; padding:15px;">
                    <source src="${audioURL}" type="${chatApp.file.type}">
                    Your browser does not support the audio element.
                </audio><br>
            `;
        }
        // Check if it's a video
        else if (chatApp.file.type.startsWith("video/")) {
            const videoURL = URL.createObjectURL(chatApp.file);
            html += `
                <video controls style="max-width:100%; padding:15px;">
                    <source src="${videoURL}" type="${chatApp.file.type}">
                    Your browser does not support the video element.
                </video><br>
            `;
        }
        // For other types, show a generic file icon
        else {
            html += `<span style="padding:30px;"><i class="bx bxs-file" style="font-size:80px;padding:15px;"></i></span><br>`;
        }

        // Safe filename display (escape any HTML)
        const safeFileName = $('<div>').text(chatApp.file.name).html();

        html += `
            <div><p>${safeFileName}</p></div>
            <div class="progress" id="file-progress-container" style="display:none;">
                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="chatApp.removeFile();" class="btn btn-outline-primary" id="common-modal-cancel">Cancel</button>
                <div id="file-upload-action">
                    <button class="btn btn-primary" onclick="chatApp.uploadFile();">Upload</button>
                </div>
            </div>
        `;

        app.setModalContent(html);
        app.showModal();
    },


    removeFile: function () {
        chatApp.fileData = false;
        $("#file-upload-container").html("").hide();
        app.hideModal();
    },

    closeConv: function () {
        $(".messages-page__list-scroll").show();
        $(".chatbox").hide();
    },
    uploadFile: function () {
        if (chatApp.uploadProcessRunning) {
            alert("Upload process is ongoing");
            return false;
        }
        chatApp.uploadProcessRunning = true;
        var file = chatApp.file;
        var uploadData = new FormData();
        uploadData.append("_token", CSRF_TOKEN);
        uploadData.append("conv_id", chatApp.convId);
        uploadData.append("to_user_id", chatApp.opponantId);
        uploadData.append("file", file);
        $("#file-progress-container").show();
        $.ajax({
            url: "chat/upload_file",
            method: "post",
            data: uploadData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                chatApp.uploadProcessRunning = false;
                $("#file-progress-container").hide();
                if (response.status) {
                    chatApp.fileData = response.path;
                    $("#file-upload-action").html(
                        '<button class="btn btn-secondary" onClick="chatApp.sendFileMessage();"><i class="bx bxs-paper-plane mr-2"></i> Send</button>'
                    );
                } else {
                    app.hideModal();
                    Swal.fire("Warning", response.message, "warning");
                }
            },
            error: function (e) {
                chatApp.uploadProcessRunning = false;
                $("#file-progress-container").hide();
            },
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                // Upload progress
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = parseInt(
                                (evt.loaded / evt.total) * 100
                            );
                            //Do something with upload progress
                            $("#file-progress-container .progress-bar").css({
                                width: percentComplete + "%",
                            });
                            // console.log(percentComplete);
                        }
                    },
                    false
                );
                return xhr;
            },
        });
    },
    // searchConversation: function () {
    //     // Check if chatApp.searchInput is not null and not undefined
    //     if (chatApp.searchInput && chatApp.searchInput.val().trim() !== "") {
    //         $.each(
    //             chatApp.chatListContainer.find("li"),
    //             function (index, element) {
    //                 var name = $(element).data("name");
    //                 if (name != null) {
    //                     if (
    //                         $(element)
    //                             .data("name")
    //                             .toLowerCase()
    //                             .match(
    //                                 chatApp.searchInput
    //                                     .val()
    //                                     .trim()
    //                                     .toLowerCase()
    //                             )
    //                     ) {
    //                         $(element).removeClass("notmatched");
    //                     } else {
    //                         $(element).addClass("notmatched");
    //                     }
    //                 } else {
    //                     console.log(1);
    //                     $(element).addClass("notmatched");
    //                 }
    //             }
    //         );
    //     } else {
    //         $("li.conversation.notmatched").removeClass("notmatched");
    //     }
    // },


    // dataToHtml: function (html, object) {
    //     try {
    //         $.each(object, function (index, value) {
    //             html = html.replaceAll("__" + index + "__", value);
    //         });
    //         return html.replace(/\__(.+?)\__/g, "");
    //     } catch (e) { }
    // },
    // renderHtmlData: function (template, data) {
    //     var html = "";
    //     if (template) {
    //         $.each(data, function (index, value) {
    //             html = html + chatApp.dataToHtml(template, value);
    //         });
    //     }
    //     return html;
    // },

    // start: function (id, type) {
    //     $.ajax({
    //         url: "chat/start",
    //         method: "post",
    //         data: { _token: CSRF_TOKEN, id: id, type: type },
    //         dataType: "json",
    //         success: function (response) {
    //             if (response.status) {
    //                 router.navigateTo("chat/chat?id=" + response.id);
    //             }
    //         },
    //     });
    // },
};
// function validateInput(input) {
//     // Trim the input value to remove leading and trailing spaces
//     var trimmedValue = input.value.trim();

//     // If the trimmed value is empty, clear the input
//     if (trimmedValue === "") {
//         input.value = "";
//     }
// }

// function scrollUp() {
//     // Scroll up logic
//     window.scrollTo({
//         top: 0,
//         behavior: 'smooth' // For smooth scrolling effect
//     });
// }