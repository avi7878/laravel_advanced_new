<style>
    .select2-container {
    z-index: 99999;
}
</style>
<form action="message/addusers" method="post" class="ajax-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Member</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <img src="theme/assets/images/modal_close.svg" alt="">
        </button>
    </div>
    <div class="modal-body p-0 body-searchbar">
        @csrf
        <input type="hidden" id="id" value="{{$chat->chat_id}}" name="chatId">
        <div class="form-group topic_input">
            <select class="form-control select2" id="autocompleteuser" name="chat_user_ids[]" multiple>
            </select>
        </div>
    </div>
    <div class="modal-footer left-right-icon">
        <div class="my_tags_btn create_tag">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </div>
</form>
<script>
    var defaultImageUrl = "{{ \Illuminate\Support\Facades\Storage::url('default/no-image.jpg') }}";
    documentReady(function() {
        var world_id = $('#id').val();
        $('#autocompleteuser').select2({
            placeholder: 'Enter User Name',
            ajax: {
                url: 'message/usersuggestion',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        chatId: world_id
                    };
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: true
            },
            templateResult: formatUser,
            templateSelection: formatUserSelection,
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    });

    function formatUser(user) {
        console.log(user);
        if (user.loading) {
            return user.text;
        }

        var imageUrl = user.image ? user.image : defaultImageUrl;
        var imageHTML = '<img src="' + imageUrl + '" class="user-avatar" style="width: 20px; height: 20px; margin-right: 10px;" />';
        var nameHTML = '<span>' + user.text + '</span>';
        return $('<div>').append(imageHTML, nameHTML).html();
    }

    function formatUserSelection(user) {
        return user.text || user.id;
    }
</script>
<script type="text/javascript">
    documentReady(function() {
        $('.ajax-form').validate({
            rules: {
                'chat_user_ids[]': {
                    required: true,
                },
            },
            messages: {
                'chat_user_ids[]': {
                    required: "Please Select The User.",
                },
            },
            submitHandler: function(form) {
                app.ajaxForm(form);
            }
        });
    });
</script>