//  ====================  Control Pane JS  ====================

//    created by:        Brycen Rogers ( brycenrogers@gmail.com )
//    creation data:     October 3, 2015

//    This file contains javascript for control pane of Linken

//  ===========================================================

$( document ).ready(function() {

    $('#image-cropper').cropit({
        width: 150,
        height: 150
    });

    $('.select-image-btn').click(function() {
        $('.cropit-image-input').click();
    });

    $('#updatePhotoSubmit').click(function() {
        var dataURI = $('#image-cropper').cropit('export', {
            type: 'image/png',
            quality: .9,
            originalSize: false
        });
        $('#photoDataURI').val(dataURI);
        $('#uploadPhotoForm').submit();
    });

    $('#tags-dropdown').click(function() {
        if ($(this).attr('aria-expanded') == "false") {
            // Show spinner
            var spinner = addSpinnerToElement(document.getElementById('tags-pane-spinner'), "133px", "#448dff");
            $.ajax({
                url: "/tags/pane",
                cache: false,
                method: 'get'
            })
            .done(function(response) {
                spinner.stop();
                spinner = null;
                $('#tags-dropdown-pane').html(response);
            });
        }
    });

    $('#change-password-submit').click(function() {
        var errorDiv = $('#changePasswordError');
        var csrf = $('input#csrf_token').val();
        var old = $('input#oldPassword').val();
        var password = $('input#newPassword').val();
        var confirm = $('input#newPasswordConfirmation').val();

        errorDiv.hide();

        $.ajax({
            url: "/settings/changePassword",
            cache: false,
            method: 'post',
            data: {
                _token: csrf,
                old: old,
                password: password,
                password_confirmation: confirm
            }
        })
        .fail(function (response) {
            var errors = "";
            var responseText = JSON.parse(response.responseText);
            $.each(responseText, function (fieldName, errorText) {
                errors += errorText[0] + '<br>';
            });
            errors.slice(0,-4);
            errorDiv.html(errors).fadeIn("fast");
        })
        .done(function( response ) {
            var changePasswordModal = $('#changePasswordModal');
            // Close modal and show success message
            changePasswordModal.modal('hide');
            showAlert('success', 'Password changed!');
            // Clear out fields
            $('input#oldPassword').val("");
            $('input#newPassword').val("");
            $('input#newPasswordConfirmation').val("");
        });
    });

    $('#user-settings-submit').click(function() {
        var csrf = $('#csrf_token').val();
        var discoverySetting = $('#discovery-settings-select').val();

        $.ajax({
            url: "/settings/userSettings",
            cache: false,
            method: 'post',
            data: {
                _token: csrf,
                discoverySetting: discoverySetting
            }
        })
        .fail(function (response) {
            var errors = "";
            var responseText = JSON.parse(response.responseText);
            $.each(responseText, function (fieldName, errorText) {
                errors += errorText[0] + '<br>';
            });
            errors.slice(0,-4);
            errorDiv.html(errors).fadeIn("fast");
        })
        .done(function (response) {
            var userSettingsModal = $('#user-settings-modal');
            // Close modal and show success message
            userSettingsModal.modal('hide');
            showAlert('success', 'User settings saved');
        });
    });
});
