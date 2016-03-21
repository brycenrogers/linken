/**
 *
 * Alerts
 *
 */

var successBackgroundColor = "#5cb85c";
var successTextColor = "#ffffff";
var errorBackgroundColor = "#dc4c4c";
var errorTextColor = "#ffffff";
var backgroundColor;
var textColor;

/**
 * Sets the appropriate colors for an alert
 *
 * @param type
 */
function setAlertStyle(type) {
    if (type == 'success') {
        backgroundColor = successBackgroundColor;
        textColor = successTextColor;
    } else if (type == 'error') {
        backgroundColor = errorBackgroundColor;
        textColor = errorTextColor;
    }
}

/**
 * Shows an alert. May show alert in Hitbox or using a Fixed Alert depending on viewport.
 *
 * @param type
 * @param message
 * @param callback
 */
function showAlert(type, message, callback) {
    // Set alert colors
    setAlertStyle(type);
    // Determine type of alert to show
    var hitboxElement;
    if ($('#welcome-blue-hitbox').length) {
        // Check to see if we are on the welcome page
        hitboxElement = $('#welcome-blue-hitbox');
    } else {
        hitboxElement = $('#blue-hitbox-add-pane');
    }
    if (isElementInViewport(hitboxElement)) {
        // Hitbox is in view, show alert there
        return showHitboxAlert(hitboxElement, message, callback);
    } else {
        // Hitbox is not in view, show a fixed alert
        return showFixedAlert(hitboxElement, message, callback);
    }
}

/**
 * Shows a Fixed alert
 *
 * @param element
 * @param message
 * @param callback
 */
function showFixedAlert(element, message, callback) {
    var alertPane = $('.alert-pane');
    var viewportWidth = $(window).width();
    var containerWidth = element.width();
    var alertLeftMargin = (viewportWidth - containerWidth) / 2;
    alertPane.css({
        'background-color': backgroundColor,
        'width': containerWidth + 'px'
    });
    alertPane.html(message);
    alertPane.fadeIn('fast').delay(2000).fadeOut('fast', function () {
        if (callback) {
            return ( callback() );
        }
    });
}

/**
 * Show Alert within the main Hitbox
 *
 * @param element
 * @param message
 * @param callback
 */
function showHitboxAlert(element, message, callback) {
    $('textarea#add').fadeOut('fast');
    $('textarea#add-welcome').fadeOut('fast');
    element.velocity({
        backgroundColor: backgroundColor
    }, 500, function () {

        $('#flash').html(message).css('color', textColor).fadeIn('fast');

        // Transition the background color back to normal
        setTimeout(function() {
            revertHitboxStyle(element, callback);
        }, 2000);
    });
}

/**
 * Revert the main Hitbox back to normal styling
 */
function revertHitboxStyle(element, callback) {
    $('#flash').fadeOut('fast');
    element.velocity({
        backgroundColor: "#448dff"
    }, 500, function () {
        $('textarea#add-welcome').fadeIn('fast');
        $('textarea#add').fadeIn('fast');
        if(callback) {
            return(callback());
        }
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

/**
 * Determine if specific element is in viewport
 *
 * @reference http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
 * @param el
 * @returns {boolean}
 */
function isElementInViewport (el) {

    //special bonus for those using jQuery
    if (typeof jQuery === "function" && el instanceof jQuery) {
        el = el[0];
    }

    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}