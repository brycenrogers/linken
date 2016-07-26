$( document ).ready(function() {

    var imageElement = $('#settings-image');
    var settingsValueTextarea = $('#settings-value');
    var settingsDescriptionTextarea = $('#settings-description');
    var tagsTextarea = $('#settings-tags');
    var destroyItem = $('#destroy-item');
    var settingsItemIdInput = $('#settings-item-id');
    var settingsUrlTextarea = $('#settings-url');

    autosize($('.share-emails'));

    function initializePopovers() {
        $("[data-toggle=popover]").popover();
    }

    initializePopovers();

    $('#item-settings-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var type = button.data('type');
        var image = button.data('image');
        var value = button.data('value');
        var description = button.data('description');
        var itemId = button.data('itemid');
        var tagsStr = button.data('tags');
        var tags = tagsStr.split(',');
        var lastTagElement = tags[tags.length - 1];
        var url = button.data('url');

        if (lastTagElement == "") {
            tags.splice(tags.length - 1, 1);
        }

        var modal = $(this);
        modal.find('.modal-title').text(type + ' Settings');

        imageElement.css('background-image', "url('/assets/images/thumbs/" + image + "')");
        settingsValueTextarea.val(value);
        settingsDescriptionTextarea.val(description);
        settingsItemIdInput.val(itemId);
        settingsUrlTextarea.html(url);
        destroyItem.attr('href', '/item/destroy/' + itemId);

        autosize(settingsValueTextarea);
        autosize(settingsDescriptionTextarea);
        autosize(tagsTextarea);

        // Tags
        if (tags.length > 1) {
            tagsTextarea.html(tags.join(', '));
        } else {
            tagsTextarea.html(tags[0]);
        }
    });

    $('#update-link-settings-submit').on("click", function() {
        var itemSettingsModal = $('#item-settings-modal');
        var errorDiv = $('#item-settings-errors');
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
                description: settingsDescriptionTextarea.val(),
                tags: tagsTextarea.val()
            }
        })
        .fail(function (response) {
            if (response.status == 403) {
                showAlert('error', response.responseText);
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
            showAlert('success', 'Saved!');

            // Update the UI
            var oldPane = $('#item-pane-' + itemId);
            oldPane.hide();
            $(response).insertAfter('#item-pane-' + itemId);
            oldPane.remove();
            initializePopovers();
        });
    });

    $('#destroy-item').on("click", function() {
        var itemSettingsModal = $('#item-settings-modal');
        var errorDiv = $('#item-settings-errors');
        errorDiv.hide();
        var itemId = settingsItemIdInput.val();

        $.ajax({
            url: "/item/destroy/" + itemId,
            cache: false,
            method: 'get'
        })
        .fail(function (response) {
            if (response.status == 403) {
                showAlert('error', response.responseText);
                // Close modal and show error message
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
            var itemPane = $('#item-pane-' + itemId);
            itemPane.css('opacity', 0.5);
            showAlert('success', response.message, function() {
                itemPane.slideUp('fast', function() {
                    $(this).remove();
                });
            });
        });
    });

    $('.share-send-email').on("click", function() {
        var csrf = $('input#csrf_token').val();
        var itemId = $(this).data('itemid');
        var itemShareModal = $('#item-share-modal-' + itemId);
        var emails = $('#share-emails-' + itemId).val();
        var errorDiv = $('#item-share-errors-' + itemId);
        errorDiv.hide();

        $.ajax({
            url: "/item/email",
            cache: false,
            method: 'post',
            data: {
                _token: csrf,
                itemId: itemId,
                emails: emails
            }
        })
        .error(function (response) {
            if (response.status == 403) {
                showAlert('error', response.responseText);
                // Close modal and show error message
                itemShareModal.modal('hide');
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
        .success(function( response ) {
            // Close modal and show success message
            itemShareModal.modal('hide');
            showAlert('success', response.message);
        });
    });
});