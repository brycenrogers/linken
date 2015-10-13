//  ====================  Control Pane JS  ====================

//    created by:        Brycen Rogers ( brycenrogers@gmail.com )
//    creation data:     October 3, 2015

//    This file contains javascript for control pane of Linken

//  ===========================================================

$( document ).ready(function() {

    $('select#search-tags').select2({
        placeholder: "Tags"
    });

    $('#image-cropper').cropit({
        width: 150,
        height: 150
    });

    $('.select-image-btn').click(function() {
        $('.cropit-image-input').click();
    });

    $('.cropit-image-preview').click(function() {
        if ($(this).data('clicked') == false) {
            $('.cropit-image-input').click();
            $(this).data('clicked', true);
            $(this).css('cursor', 'move');
        }
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

});