//  ====================  Settings JS  ====================

//    created by:        Brycen Rogers ( brycenrogers@gmail.com )
//    creation data:     September 25, 2015

//    This file contains javascript for the base Add pane of Linken

//  ===========================================================

$( document ).ready(function() {

    $('#settings-image-refresh-button').click(function () {

        var imageEle = $('#settings-image');
        var url = $('#settings-url').val();
        var csrf = $('input#csrf_token').val();
        var originalUrl = $(this).css("background-image");
        var nextImageNumber = parseInt(imageEle.attr('data-image-number'));

        imageEle.css("background-image", "url('')");
        var infoPaneSpinner = addSpinnerToElement($('#settings-image-container').get(0), '50px', '#448dff');

        $.ajax({
            url: "/link/parse",
            cache: false,
            method: 'post',
            data: { url: url, _token: csrf, image: true, image_number: nextImageNumber }
        })
        .done(function( response ) {
            if (response.image == '') {
                container.css("background-image", originalUrl);
            } else {
                imageEle
                    .css('background-image', 'url(' + response.image + ')')
                    .attr('data-image-number', response.image_number);
            }
            infoPaneSpinner.stop();
        });
    });

});
