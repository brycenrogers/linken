$( document ).ready(function() {

    var settingsValueTextarea = $('#settingsValue');
    var settingsDescriptionTextarea = $('#settingsDescription');
    var destroyItem = $('#destroyItem');
    var settingsItemIdInput = $('#settingsItemId');

    $('#itemSettingsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var type = button.data('type');
        var value = button.data('value');
        var description = button.data('description');
        var itemId = button.data('itemid');

        var modal = $(this);
        modal.find('.modal-title').text(type + ' Settings');
        settingsValueTextarea.val(value);
        settingsDescriptionTextarea.val(description);
        settingsItemIdInput.val(itemId);
        destroyItem.attr('href', '/item/destroy/' + itemId);

        autosize(settingsValueTextarea);
        autosize(settingsDescriptionTextarea);
    });

    $('#updateLinkSettingsSubmit').on("click", function() {
        var itemSettingsModal = $('#itemSettingsModal');
        var errorDiv = $('#itemSettingsErrors');
        errorDiv.hide();
        var csrf = $('input#csrf_token').val();
        var itemId = settingsItemIdInput.val();

        $.ajax({
            url: "/item/update",
            cache: false,
            method: 'post',
            data: {
                _token: csrf,
                itemId: itemId,
                value: settingsValueTextarea.val(),
                description: settingsDescriptionTextarea.val()
            }
        })
        .fail(function (response) {
            if (response.status == 403) {
                showHitboxAlert('error', response.responseText);
                // Close modal and show success message
                itemSettingsModal.modal('hide');
                return;
            }
            var errors = "";
            var responseText = JSON.parse(response.responseText);
            $.each(responseText, function (fieldName, errorText) {
                errors += errorText[0] + '<br>';
            });
            errors.slice(0,-4);
            errorDiv.html(errors).fadeIn("fast");
        })
        .done(function( response ) {
            // Close modal and show success message
            itemSettingsModal.modal('hide');
            showHitboxAlert('success', 'Saved!');

            // Update the UI
            var oldPane = $('#item-pane-' + itemId);
            oldPane.hide();
            $(response).insertAfter('#item-pane-' + itemId);
            oldPane.remove();
        });
    });
});