
function showHitboxAlert(type, message) {

    var successBackgroundColor = "#5cb85c";
    var successTextColor = "#ffffff";
    var errorBackgroundColor = "#dc4c4c";
    var errorTextColor = "#ffffff";

    var backgroundColor;
    var textColor;

    if (type == 'success') {
        backgroundColor = successBackgroundColor;
        textColor = successTextColor;
    } else if (type == 'error') {
        backgroundColor = errorBackgroundColor;
        textColor = errorTextColor;
    }

    $('textarea#add').fadeOut('fast');

    $('div#blue-hitbox-add-pane').velocity({
        backgroundColor: backgroundColor
    }, 500, function () {

        $('#flash').html(message).css('color', textColor).fadeIn('fast');

        // Transition the background color back to normal
        setTimeout(function() {
            revertHitboxStyle();
        }, 2000);
    });
}
function revertHitboxStyle() {
    $('#flash').fadeOut('fast');
    $('div#blue-hitbox-add-pane').velocity({
        backgroundColor: "#448dff"
    }, 500, function () {
        $('textarea#add').fadeIn('fast');
    });
}