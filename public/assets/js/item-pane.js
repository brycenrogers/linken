$( document ).ready(function() {

    var settingsValueTextarea = $('#settings-value');
    var settingsDescriptionTextarea = $('#settings-description');
    var tagsSelect = $('#edit-tags');
    var destroyItem = $('#destroy-item');
    var settingsItemIdInput = $('#settings-item-id');

    $('#item-settings-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var type = button.data('type');
        var value = button.data('value');
        var description = button.data('description');
        var itemId = button.data('itemid');
        var tagsStr = button.data('tags');
        var tags = tagsStr.split(',');

        var modal = $(this);
        modal.find('.modal-title').text(type + ' Settings');
        settingsValueTextarea.val(value);
        settingsDescriptionTextarea.val(description);
        settingsItemIdInput.val(itemId);
        destroyItem.attr('href', '/item/destroy/' + itemId);

        autosize(settingsValueTextarea);
        autosize(settingsDescriptionTextarea);

        // Tags
        var tagsInput = $('#edit-tags');
        tagsInput.html("");
        $.each(tags, function(key, tag) {
            if (tag != '') {
                var child = "<option value='" + tag + "' selected>" + tag + "</option>";
                tagsInput.append(child);
            }
        });

        tagsInput.select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Tags",
            ajax: {
                url: '/tags/search',
                delay: 100,
                processResults: function (data) {
                    return { results: data.items }
                }
            }
        });
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
                tags: tagsSelect.val()
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

    $('#destroy-item').on("click", function() {
        var itemSettingsModal = $('#item-settings-modal');
        var errorDiv = $('#item-settings-errors');
        errorDiv.hide();
        var csrf = $('input#csrf_token').val();
        var itemId = settingsItemIdInput.val();

        $.ajax({
            url: "/item/destroy/" + itemId,
            cache: false,
            method: 'get'
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
            showHitboxAlert('success', response.message);

            // Update the UI
            var oldPane = $('#item-pane-' + itemId);
            oldPane.hide();
            $(response).insertAfter('#item-pane-' + itemId);
            oldPane.remove();
        });
    });

    $('#item-share-modal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);

        $('#share-emails').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Enter emails"
        });

        var a2a_config = a2a_config || {};
        a2a_config.linkname = button.data('title');
        a2a_config.linkurl = button.data('url');
    });
});