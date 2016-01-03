/**
 *
 * Alerts
 *
 */

/**
 * Show Alert within the main Hitbox
 *
 * @param type
 * @param message
 */
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

/**
 * Revert the main Hitbox back to normal styling
 */
function revertHitboxStyle() {
    $('#flash').fadeOut('fast');
    $('div#blue-hitbox-add-pane').velocity({
        backgroundColor: "#448dff"
    }, 500, function () {
        $('textarea#add').fadeIn('fast');
    });
}

/**
 * Spinners
 */

/**
 * Add a javascript Spinner to the desired element
 *
 * @param element
 * @param left
 * @param color
 * @returns {*}
 */
function addSpinnerToElement(element, left, color)
{
    var opts = {
        lines: 13 // The number of lines to draw
        , length: 5 // The length of each line
        , width: 3 // The line thickness
        , radius: 8 // The radius of the inner circle
        , scale: 1 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#fff' // #rgb or #rrggbb or array of colors
        , opacity: 0.25 // Opacity of the lines
        , rotate: 0 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        , top: '50%' // Top position relative to parent
        , left: '50%' // Left position relative to parent
        , shadow: false // Whether to render a shadow
        , hwaccel: true // Whether to use hardware acceleration
        , position: 'absolute' // Element positioning
    };

    if (color) {
        opts.color = color;
    }

    if (left) {
        opts.left = left;
    }

    var spinner = new Spinner(opts).spin(element);

    return spinner;
}